<x-filament-panels::page>
    <div class="space-y-6">
        {{-- ============================================ --}}
        {{-- LANGKAH 1: Siapkan Tahun Ajaran Tujuan       --}}
        {{-- ============================================ --}}
        <x-filament::section
            icon="heroicon-o-calendar-days"
            icon-color="primary">
            <x-slot name="heading">
                Langkah 1 — Tentukan Tahun Ajaran Tujuan
            </x-slot>
            <x-slot name="description">
                Pilih tahun ajaran asal (yang akan diarsipkan) dan tahun ajaran tujuan
                (yang akan diaktifkan setelah proses).
            </x-slot>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Dari Tahun --}}
                <div>
                    <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3 mb-2">
                        <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                            Dari Tahun Ajaran
                        </span>
                        <span class="text-danger-600 dark:text-danger-400">*</span>
                    </label>
                    <select wire:model="dariTahunId"
                            class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-950 shadow-sm transition duration-75 focus:border-primary-500 focus:ring-1 focus:ring-inset focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="">-- Pilih Tahun Ajaran --</option>
                        @foreach ($this->getTahunAjaranOptions() as $id => $nama)
                            <option value="{{ $id }}">{{ $nama }}</option>
                        @endforeach
                    </select>
                    @error('dariTahunId')
                        <p class="mt-2 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Spacer (untuk layout 2 kolom) --}}
                <div class="hidden md:block"></div>
            </div>

            {{-- Mode Tahun Tujuan --}}
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-950 dark:text-white mb-2">
                    Cara Menentukan Tahun Ajaran Tujuan
                </label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <label class="flex items-center gap-3 p-3 rounded-lg border cursor-pointer transition
                                  {{ $modeTahunTujuan === 'existing' ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/20' : 'border-gray-300 dark:border-gray-600' }}">
                        <input type="radio" wire:model.live="modeTahunTujuan" value="existing"
                               class="text-primary-600 focus:ring-primary-500">
                        <div>
                            <div class="font-medium text-sm">Pilih yang sudah ada</div>
                            <div class="text-xs text-gray-500">Tahun ajaran sudah dibuat sebelumnya</div>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-3 rounded-lg border cursor-pointer transition
                                  {{ $modeTahunTujuan === 'new' ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/20' : 'border-gray-300 dark:border-gray-600' }}">
                        <input type="radio" wire:model.live="modeTahunTujuan" value="new"
                               class="text-primary-600 focus:ring-primary-500">
                        <div>
                            <div class="font-medium text-sm">Buat baru</div>
                            <div class="text-xs text-gray-500">Sistem akan buat tahun ajaran baru</div>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Opsi: Existing --}}
            @if ($modeTahunTujuan === 'existing')
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-950 dark:text-white mb-2">
                        Pilih Tahun Ajaran Tujuan
                        <span class="text-danger-600">*</span>
                    </label>
                    <select wire:model="keTahunId"
                            class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="">-- Pilih Tahun Ajaran --</option>
                        @foreach ($this->getTahunAjaranOptions() as $id => $nama)
                            <option value="{{ $id }}">{{ $nama }}</option>
                        @endforeach
                    </select>
                    @error('keTahunId')
                        <p class="mt-2 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                    @enderror
                </div>
            @else
                {{-- Opsi: New --}}
                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-950 dark:text-white mb-2">
                            Nama Tahun Ajaran
                            <span class="text-danger-600">*</span>
                        </label>
                        <input type="text" wire:model="namaTahunBaru"
                               placeholder="2026/2027"
                               class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        @error('namaTahunBaru')
                            <p class="mt-2 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-950 dark:text-white mb-2">
                            Tanggal Mulai
                            <span class="text-danger-600">*</span>
                        </label>
                        <input type="date" wire:model="tanggalMulaiBaru"
                               class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        @error('tanggalMulaiBaru')
                            <p class="mt-2 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-950 dark:text-white mb-2">
                            Tanggal Selesai
                            <span class="text-danger-600">*</span>
                        </label>
                        <input type="date" wire:model="tanggalSelesaiBaru"
                               class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        @error('tanggalSelesaiBaru')
                            <p class="mt-2 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            @endif

            <div class="mt-6 flex justify-end">
                <x-filament::button wire:click="siapkanTahunTujuan" icon="heroicon-o-check">
                    Siapkan Tahun Ajaran Tujuan
                </x-filament::button>
            </div>
        </x-filament::section>

        {{-- ============================================ --}}
        {{-- LANGKAH 2: Muat Data Siswa                   --}}
        {{-- ============================================ --}}
        @if ($tahunTujuanSiap)
            <x-filament::section
                icon="heroicon-o-users"
                icon-color="info">
                <x-slot name="heading">
                    Langkah 2 — Muat Data Siswa
                </x-slot>
                <x-slot name="description">
                    Sistem akan menampilkan semua siswa aktif dari tahun ajaran asal.
                </x-slot>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 text-sm text-success-600 dark:text-success-400">
                        <x-heroicon-o-check-circle class="w-5 h-5" />
                        <span>Tahun ajaran tujuan siap</span>
                    </div>

                    <x-filament::button wire:click="muatSiswa" icon="heroicon-o-arrow-path">
                        Muat Data Siswa
                    </x-filament::button>
                </div>
            </x-filament::section>
        @endif

        {{-- ============================================ --}}
        {{-- LANGKAH 3: Tinjau & Proses                   --}}
        {{-- ============================================ --}}
        @if ($sudahMuat && !empty($siswaList))
            <x-filament::section
                icon="heroicon-o-clipboard-document-check"
                icon-color="warning">
                <x-slot name="heading">
                    Langkah 3 — Tinjau & Sesuaikan
                </x-slot>
                <x-slot name="description">
                    Ubah keputusan siswa bila perlu. Default: naik ke kelas berikutnya.
                </x-slot>

                <div class="space-y-4">
                    @foreach ($siswaList as $indexKelas => $kelas)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            {{-- Header Kelas --}}
                            <div class="bg-gray-50 dark:bg-gray-800 px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                                <div>
                                    <h3 class="font-semibold text-gray-950 dark:text-white">
                                        {{ $kelas['classroom_name'] }}
                                    </h3>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        {{ count($kelas['siswa']) }} siswa
                                    </p>
                                </div>

                                @if ($kelas['is_last_class'])
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium
                                                 bg-warning-100 text-warning-800
                                                 dark:bg-warning-900 dark:text-warning-200">
                                        <x-heroicon-o-academic-cap class="w-3.5 h-3.5" />
                                        Kelas Terakhir — akan lulus
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium
                                                 bg-primary-100 text-primary-800
                                                 dark:bg-primary-900 dark:text-primary-200">
                                        <x-heroicon-o-arrow-trending-up class="w-3.5 h-3.5" />
                                        Tujuan: {{ $kelas['next_class_name'] }}
                                    </span>
                                @endif
                            </div>

                            {{-- Tabel Siswa --}}
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-800/50">
                                    <tr>
                                        <th class="text-left px-4 py-2 font-medium text-gray-700 dark:text-gray-300 w-10">#</th>
                                        <th class="text-left px-4 py-2 font-medium text-gray-700 dark:text-gray-300">Nama Siswa</th>
                                        <th class="text-left px-4 py-2 font-medium text-gray-700 dark:text-gray-300 w-40">Keputusan</th>
                                        <th class="text-left px-4 py-2 font-medium text-gray-700 dark:text-gray-300 w-48">Kelas Tujuan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($kelas['siswa'] as $indexSiswa => $siswa)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                                            <td class="px-4 py-2 text-gray-500">{{ $indexSiswa + 1 }}</td>
                                            <td class="px-4 py-2 text-gray-950 dark:text-white">
                                                {{ $siswa['nama'] }}
                                            </td>
                                            <td class="px-4 py-2">
                                                <select wire:model.live="siswaList.{{ $indexKelas }}.siswa.{{ $indexSiswa }}.keputusan"
                                                        class="block w-full rounded-lg border border-gray-300 bg-white px-2 py-1.5 text-sm shadow-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500
                                                               dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                                    <option value="naik">Naik</option>
                                                    <option value="tinggal">Tinggal</option>
                                                    <option value="lulus">Lulus</option>
                                                    <option value="pindah">Pindah</option>
                                                </select>
                                            </td>
                                            <td class="px-4 py-2">
                                                <select wire:model="siswaList.{{ $indexKelas }}.siswa.{{ $indexSiswa }}.classroom_tujuan"
                                                        class="block w-full rounded-lg border border-gray-300 bg-white px-2 py-1.5 text-sm shadow-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500
                                                               dark:border-gray-600 dark:bg-gray-700 dark:text-white
                                                               disabled:opacity-50 disabled:cursor-not-allowed"
                                                        @if(in_array($siswa['keputusan'], ['lulus', 'pindah'])) disabled @endif>
                                                    <option value="">-- Pilih --</option>
                                                    @foreach ($this->getKelasOptions() as $id => $nama)
                                                        <option value="{{ $id }}">{{ $nama }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        Total: {{ collect($siswaList)->sum(fn($k) => count($k['siswa'])) }} siswa
                    </div>

                    <x-filament::button
                        wire:click="proses"
                        wire:confirm="Yakin proses kenaikan kelas? Tindakan ini tidak dapat dibatalkan."
                        color="danger"
                        size="lg"
                        icon="heroicon-o-arrow-trending-up">
                        Proses Kenaikan Kelas
                    </x-filament::button>
                </div>
            </x-filament::section>
        @endif
    </div>
</x-filament-panels::page>
