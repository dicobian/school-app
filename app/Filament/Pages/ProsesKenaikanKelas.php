<?php

namespace App\Filament\Pages;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\ElementaryStudent;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use BackedEnum;
use UnitEnum;

class ProsesKenaikanKelas extends Page
{
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-arrow-trending-up';
    protected static ?string $navigationLabel = 'Proses Kenaikan Kelas';
    protected static string | UnitEnum | null $navigationGroup = 'Data SD';
    protected static ?string $title = 'Proses Kenaikan Kelas';
    protected string $view = 'filament.pages.proses-kenaikan-kelas';

    // === State ===
    public ?int $dariTahunId = null;
    public ?int $keTahunId = null;

    // Mode pemilihan tahun tujuan
    public string $modeTahunTujuan = 'existing';  // 'existing' | 'new'

    // Input tahun baru
    public ?string $namaTahunBaru = null;
    public ?string $tanggalMulaiBaru = null;
    public ?string $tanggalSelesaiBaru = null;

    // Progres
    public bool $tahunTujuanSiap = false;
    public bool $sudahMuat = false;
    public array $siswaList = [];

    public function mount(): void
    {
        $active = AcademicYear::active();
        $this->dariTahunId = $active?->id;

        // Default tahun baru (suggest dari tahun aktif)
        if ($active && preg_match('/(\d{4})\/(\d{4})/', $active->name, $m)) {
            $tahun1 = (int) $m[1] + 1;
            $tahun2 = (int) $m[2] + 1;
            $this->namaTahunBaru = "{$tahun1}/{$tahun2}";
            $this->tanggalMulaiBaru = "{$tahun1}-07-15";
            $this->tanggalSelesaiBaru = "{$tahun2}-06-30";
        } else {
            // Fallback
            $this->namaTahunBaru = (date('Y') + 1) . '/' . (date('Y') + 2);
            $this->tanggalMulaiBaru = (date('Y') + 1) . '-07-15';
            $this->tanggalSelesaiBaru = (date('Y') + 2) . '-06-30';
        }
    }

    /**
     * Siapkan tahun ajaran tujuan (pilih / buat baru).
     */
    public function siapkanTahunTujuan(): void
    {
        // Validasi dasar
        $this->validate([
            'dariTahunId' => 'required|exists:academic_years,id',
        ]);

        if ($this->modeTahunTujuan === 'existing') {
            $this->validate([
                'keTahunId' => 'required|exists:academic_years,id|different:dariTahunId',
            ]);

            // Cek tahun tujuan belum punya siswa
            $jumlah = ElementaryStudent::where('academic_year_id', $this->keTahunId)->count();
            if ($jumlah > 0) {
                Notification::make()
                    ->title('Tahun ajaran tujuan sudah punya siswa')
                    ->body("Ada {$jumlah} siswa di tahun ini. Pilih tahun lain atau hapus datanya dulu.")
                    ->danger()->persistent()->send();
                return;
            }
        } else {
            // Buat tahun baru
            $this->validate([
                'namaTahunBaru'      => 'required|string|max:20|unique:academic_years,name',
                'tanggalMulaiBaru'   => 'required|date',
                'tanggalSelesaiBaru' => 'required|date|after:tanggalMulaiBaru',
            ]);

            $tahunBaru = AcademicYear::create([
                'name'            => $this->namaTahunBaru,
                'tanggal_mulai'   => $this->tanggalMulaiBaru,
                'tanggal_selesai' => $this->tanggalSelesaiBaru,
                'status'          => 'akan_datang',
            ]);

            $this->keTahunId = $tahunBaru->id;
        }

        $this->tahunTujuanSiap = true;

        Notification::make()
            ->title('Tahun ajaran tujuan siap')
            ->success()->send();
    }

    /**
     * Muat daftar siswa dari tahun asal.
     */
    public function muatSiswa(): void
    {
        if (!$this->tahunTujuanSiap) {
            Notification::make()->title('Siapkan tahun ajaran tujuan dulu')->warning()->send();
            return;
        }

        $records = ElementaryStudent::where('academic_year_id', $this->dariTahunId)
            ->where('status', 'aktif')
            ->with(['classroom', 'academicYear'])
            ->get();

        if ($records->isEmpty()) {
            Notification::make()
                ->title('Tidak ada siswa aktif di tahun asal')
                ->warning()->send();
            return;
        }

        $this->siswaList = $records->groupBy('classroom_id')->map(function ($items, $classroomId) {
            $classroom = $items->first()->classroom;
            $nextClass = $classroom?->nextClassroom();
            $defaultKeputusan = $nextClass ? 'naik' : 'lulus';

            return [
                'classroom_id'    => $classroomId,
                'classroom_name'  => $classroom?->name ?? '-',
                'next_class_id'   => $nextClass?->id,
                'next_class_name' => $nextClass?->name,
                'is_last_class'   => $nextClass === null,
                'siswa'           => $items->map(fn ($s) => [
                    'student_id'       => $s->id,
                    'nama'             => $s->nama,
                    'keputusan'        => $defaultKeputusan,
                    'classroom_tujuan' => $nextClass?->id,
                ])->values()->toArray(),
            ];
        })->values()->toArray();

        $this->sudahMuat = true;
    }

    /**
     * Proses kenaikan kelas.
     */
   public function proses(): void
{
    if (!$this->sudahMuat || empty($this->siswaList)) {
        Notification::make()->title('Muat data dulu')->warning()->send();
        return;
    }

    $errors = [];

    DB::transaction(function () use (&$errors) {
        foreach ($this->siswaList as $kelas) {
            foreach ($kelas['siswa'] as $siswa) {
                $student = ElementaryStudent::find($siswa['student_id']);
                if (!$student) {
                    $errors[] = "Siswa {$siswa['nama']} tidak ditemukan.";
                    continue;
                }

                switch ($siswa['keputusan']) {
                    case 'naik':
                        if (empty($siswa['classroom_tujuan'])) {
                            $errors[] = "Siswa {$siswa['nama']}: kelas tujuan tidak ada, dilewati.";
                            continue 2;
                        }

                        // 👇 Ambil kelas tujuan untuk update tingkat_rombel
                        $kelasTujuan = Classroom::find($siswa['classroom_tujuan']);

                        $student->update([
                            'classroom_id'     => $siswa['classroom_tujuan'],
                            'academic_year_id' => $this->keTahunId,
                            'tingkat_rombel'   => $kelasTujuan?->name,
                            'status'           => 'aktif',
                        ]);
                        break;

                    case 'tinggal':
                        $student->update([
                            'academic_year_id' => $this->keTahunId,
                            'status'           => 'aktif',
                        ]);
                        break;

                    case 'lulus':
                        $student->update(['status' => 'lulus']);
                        break;

                    case 'pindah':
                        $student->update(['status' => 'pindah']);
                        break;
                }
            }
        }

        // Arsipkan tahun asal, aktifkan tahun tujuan
        AcademicYear::find($this->dariTahunId)->update(['status' => 'arsip']);
        AcademicYear::find($this->keTahunId)->update(['status' => 'aktif']);
    });

    // Notifikasi
    if (!empty($errors)) {
        Notification::make()
            ->title('Proses selesai dengan ' . count($errors) . ' catatan')
            ->body(implode("\n", array_slice($errors, 0, 5)))
            ->warning()->persistent()->send();
    } else {
        Notification::make()
            ->title('Proses kenaikan kelas berhasil')
            ->success()->send();
    }

    // Reset
    $this->sudahMuat = false;
    $this->siswaList = [];
    $this->tahunTujuanSiap = false;
    $this->mount();
}

    public function getTahunAjaranOptions(): array
    {
        return AcademicYear::orderBy('tanggal_mulai')->pluck('name', 'id')->toArray();
    }

    public function getKelasOptions(): array
    {
        return Classroom::orderBy('tingkat')->pluck('name', 'id')->toArray();
    }
}
