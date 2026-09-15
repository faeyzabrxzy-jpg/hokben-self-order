<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Antrean HokBen</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-amber-400 via-yellow-400 to-amber-500 flex items-center justify-center min-h-screen p-4 font-sans">
    <div class="bg-white p-8 rounded-3xl shadow-2xl text-center max-w-sm w-full border-t-8 border-red-600 relative overflow-hidden">

        <div class="bg-red-600 text-yellow-300 font-black text-lg py-1 px-4 rounded-b-xl inline-block shadow mb-4">
            HokBen Self Order
        </div>

        <h2 class="text-gray-800 font-black text-xl">Bukti Pemesanan</h2>
        <p class="text-gray-400 text-xs mt-0.5">Simpan struk ini untuk mengambil pesanan</p>

        <div class="my-6 py-4 bg-amber-50 rounded-2xl border border-yellow-200 shadow-inner">
            <p class="text-xs font-bold text-red-700 uppercase tracking-wider">Nomor Antrean Anda</p>
            <h1 class="text-6xl font-black text-amber-500 my-1">#{{ sprintf("%03d", $order->nomor_antrean) }}</h1>
            <p class="text-base font-extrabold text-gray-800">Atas Nama: <span class="text-red-600">{{ $order->nama_pemesan }}</span></p>
        </div>

        <div class="text-left bg-gray-50 p-4 rounded-xl text-sm border border-gray-100 mb-6 space-y-2">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Rincian Item</span>
                <p class="font-semibold text-gray-800">{{ $order->detail_pesanan }}</p>
            </div>
            <div class="border-t border-gray-200 pt-2 flex justify-between items-center">
                <span class="text-xs text-gray-500">Metode Bayar:</span>
                <span class="font-bold text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-md">{{ $order->metode_pembayaran }}</span>
            </div>
            <div class="flex justify-between items-center pt-1">
                <span class="font-bold text-gray-700">Total Pembayaran:</span>
                <span class="font-black text-red-600 text-base">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
            </div>
        </div>

        <a href="{{ route('kiosk') }}" class="block w-full bg-red-600 hover:bg-red-700 text-white font-black py-3 rounded-xl shadow-lg transition-all active:scale-95">
            Buat Pesanan Baru
        </a>
    </div>
</body>
</html>
