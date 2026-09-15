<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Kasir HokBen</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950 text-white min-h-screen p-6 font-sans">

    <div class="max-w-7xl mx-auto">
        <!-- NAVIGASI PINTASAN ADMIN & KASIR -->
        <nav class="flex justify-between items-center bg-gray-900 border border-gray-800 p-4 rounded-xl mb-6 shadow-md">
            <div class="flex items-center space-x-3">
                <span class="bg-yellow-400 text-red-900 font-black px-3 py-1 rounded-lg">HB</span>
                <span class="font-bold text-lg text-yellow-400">HokBen Internal System</span>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('menus.index') }}" class="bg-gray-800 hover:bg-gray-700 text-gray-300 font-bold px-4 py-2 rounded-lg text-sm transition-all">
                    📋 Kelola Menu
                </a>
                <a href="{{ route('kasir.index') }}" class="bg-red-600 text-white font-bold px-4 py-2 rounded-lg text-sm shadow">
                    🖥️ Layar Kasir
                </a>
                <a href="{{ route('kiosk') }}" target="_blank" class="bg-yellow-500 hover:bg-yellow-400 text-red-950 font-bold px-4 py-2 rounded-lg text-sm transition-all">
                    📱 Buka Kios Pemesanan
                </a>
            </div>
        </nav>

        <!-- TOP BAR STATUS -->
        <header class="flex justify-between items-center border-b border-gray-800 pb-4 mb-8">
            <div>
                <h1 class="text-2xl font-black text-yellow-400 tracking-wide">Layar Pemanggilan Kasir</h1>
                <p class="text-gray-400 text-xs mt-1">Status pesanan akan ter-update otomatis dalam 5 detik</p>
            </div>
            <button onclick="location.reload()" class="bg-gray-800 hover:bg-gray-700 text-yellow-400 border border-yellow-500/30 px-4 py-2 rounded-xl text-sm font-bold flex items-center gap-2 transition-all">
                🔄 Refresh Sekarang
            </button>
        </header>

        <!-- GRID ANTREAN -->
        <main class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($orders as $order)
                <div class="bg-gray-900/90 border-2 {{ $order->status == 'Dipanggil' ? 'border-yellow-400 shadow-yellow-500/20' : 'border-gray-800' }} rounded-2xl p-6 shadow-xl flex flex-col justify-between transition-all">
                    <div>
                        <div class="flex justify-between items-start border-b border-gray-800 pb-3 mb-4">
                            <div>
                                <span class="text-xs uppercase tracking-wider text-gray-500 font-bold block">Nomor Antrean</span>
                                <span class="text-4xl font-black text-yellow-400">#{{ sprintf("%03d", $order->nomor_antrean) }}</span>
                            </div>
                            <div class="flex flex-col items-end gap-1">
                                <span class="px-2.5 py-1 text-[11px] font-bold rounded-full bg-blue-900/60 text-blue-300 border border-blue-700/50">
                                    {{ $order->metode_pembayaran }}
                                </span>
                                <span class="px-3 py-1 text-xs font-black rounded-full uppercase tracking-wider {{ $order->status == 'Dipanggil' ? 'bg-yellow-400 text-black' : 'bg-red-600 text-white' }}">
                                    {{ $order->status }}
                                </span>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-200">Pemesan: <span class="text-yellow-300 font-black">{{ $order->nama_pemesan }}</span></h3>
                        <div class="bg-gray-950/60 rounded-xl p-3 my-3 border border-gray-800/80">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Rincian Menu:</p>
                            <p class="text-sm text-gray-300 leading-relaxed">{{ $order->detail_pesanan }}</p>
                        </div>
                    </div>

                    <div class="pt-4 flex space-x-3">
                        <button onclick="panggilNama('{{ $order->nama_pemesan }}', {{ $order->nomor_antrean }}, {{ $order->id }})"
                                class="flex-1 bg-yellow-400 hover:bg-yellow-500 text-black font-black py-3 rounded-xl transition-all shadow-md flex items-center justify-center gap-1">
                            🔊 Panggil
                        </button>
                        <button onclick="selesaiOrder({{ $order->id }})"
                                class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-black py-3 rounded-xl transition-all shadow-md flex items-center justify-center gap-1">
                            ✓ Selesai
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center text-gray-500">
                    <p class="text-lg font-bold">Tidak ada antrean pesanan aktif saat ini.</p>
                </div>
            @endforelse
        </main>
    </div>

    <script>
        function panggilNama(nama, antrean, id) {
            let text = `Nomor antrean ${antrean}, atas nama ${nama}, silakan mengambil pesanan di kasir.`;
            let msg = new SpeechSynthesisUtterance(text);
            msg.lang = 'id-ID';
            msg.rate = 0.9;
            window.speechSynthesis.speak(msg);

            fetch(`/kasir/status/${id}/Dipanggil`).then(() => location.reload());
        }

        function selesaiOrder(id) {
            fetch(`/kasir/status/${id}/Selesai`).then(() => location.reload());
        }

        // FITUR AUTO REFRESH TIAP 5 DETIK
        setInterval(() => {
            // Hanya refresh otomatis jika suara tidak sedang membaca antrean
            if (!window.speechSynthesis.speaking) {
                location.reload();
            }
        }, 5000);
    </script>
</body>
</html>
