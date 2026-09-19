<x-filament-panels::page>
    <div class="space-y-6">

        <div class="flex flex-col items-center">
            <div id="reader" style="width: 100%; max-width: 420px;"></div>

            <div class="flex gap-3 mt-4">
                <button
                    type="button"
                    id="start-scan-btn"
                    class="px-4 py-2 rounded-lg bg-primary-600 text-white font-medium hover:bg-primary-500"
                >
                    Mulai Scan
                </button>
                <button
                    type="button"
                    id="stop-scan-btn"
                    style="display: none;"
                    class="px-4 py-2 rounded-lg bg-gray-500 text-white font-medium hover:bg-gray-400"
                >
                    Berhenti Scan
                </button>
            </div>
        </div>

        @if ($message)
            <div class="text-center font-semibold {{ $alreadyMarked ? 'text-success-600' : 'text-danger-600' }}">
                {{ $message }}
            </div>
        @endif

        @if ($scannedStudent)
            <div class="max-w-sm mx-auto border border-gray-200 dark:border-gray-700 rounded-2xl p-6 text-center shadow-sm">
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Data Terpindai</p>
                <h2 class="text-xl font-bold">{{ $scannedStudent->nama }}</h2>
                <p class="text-gray-500 mb-4">{{ $scannedStudent->classroom->name ?? '-' }}</p>

                @if ($alreadyMarked)
                    <div class="inline-flex items-center gap-2 text-success-600 font-semibold">
                        ✔ Sudah absen hari ini
                    </div>
                @else
                    <button
                        wire:click="markPresent"
                        class="w-full px-4 py-2 rounded-lg bg-success-600 text-white font-semibold hover:bg-success-500"
                    >
                        Hadir
                    </button>
                @endif

                <button
                    wire:click="resetScan"
                    class="block mx-auto mt-3 text-sm text-gray-400 underline"
                >
                    Scan siswa lain
                </button>
            </div>
        @endif

    </div>

    @push('scripts')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        document.addEventListener('livewire:initialized', function() {
            alert('Livewire siap, inisialisasi scanner...');

            let html5QrCode = null;
            const startBtn = document.getElementById('start-scan-btn');
            const stopBtn = document.getElementById('stop-scan-btn');
            const readerElement = document.getElementById('reader');

            // Fungsi untuk membersihkan scanner
            function cleanupScanner() {
                if (html5QrCode) {
                    html5QrCode.stop().then(() => {
                        html5QrCode = null;
                    }).catch(() => {});
                }
            }

            startBtn.addEventListener('click', function() {
                // Bersihkan scanner sebelumnya jika ada
                cleanupScanner();

                // Cek apakah library tersedia
                if (typeof Html5Qrcode === 'undefined') {
                    alert('Library QR Code belum dimuat. Silakan refresh halaman.');
                    return;
                }

                // Bersihkan reader div
                if (readerElement) {
                    readerElement.innerHTML = '';
                }

                // Inisialisasi scanner dengan konfigurasi yang lebih baik
                html5QrCode = new Html5Qrcode('reader');

                const config = {
                    fps: 15,
                    qrbox: { width: 250, height: 250 },
                    aspectRatio: 1.0
                };

                html5QrCode.start(
                    { facingMode: 'environment' },
                    config,
                    function(decodedText, decodedResult) {
                        // Gunakan @this bukan $wire
                        @this.lookupBarcode(decodedText);

                        // Berhenti scan setelah berhasil
                        html5QrCode.stop().then(() => {
                            html5QrCode = null;
                            startBtn.style.display = 'inline-flex';
                            stopBtn.style.display = 'none';
                        });
                    },
                    function(errorMessage) {

                    }
                ).then(() => {
                    // fungsi agar orientasi kamera nya gak reverse kebalik gerakannya
                    const settings = html5QrCode.getRunningTrackSettings();
                    const videoElement = readerElement.querySelector('video');

                    if (videoElement && settings.facingMode !== 'environment') {
                        videoElement.style.transform = 'scaleX(-1)';
                    }
                })
                .catch(function(err) {
                    console.error('Gagal memulai kamera:', err);
                    alert('Gagal mengakses kamera: ' + err.message);
                    startBtn.style.display = 'inline-flex';
                    stopBtn.style.display = 'none';
                });

                startBtn.style.display = 'none';
                stopBtn.style.display = 'inline-flex';
            });

            stopBtn.addEventListener('click', function() {
                cleanupScanner();
                startBtn.style.display = 'inline-flex';
                stopBtn.style.display = 'none';
            });

            // Cleanup saat navigasi
            document.addEventListener('livewire:navigating', function() {
                cleanupScanner();
            });

            // Jika ada error di scanner, restart
            readerElement.addEventListener('error', function() {
                console.warn('Reader error, mencoba restart...');
                cleanupScanner();
                startBtn.style.display = 'inline-flex';
                stopBtn.style.display = 'none';
            });
        });
    </script>
    @endpush
</x-filament-panels::page>
