<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Menu HokBen</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6 font-sans">
    <div class="max-w-5xl mx-auto bg-white p-8 rounded-2xl shadow-xl">

        <!-- NAVIGASI PINTASAN ADMIN & KASIR -->
        <nav class="flex justify-between items-center bg-gray-900 text-white p-4 rounded-xl mb-6 shadow-md">
            <div class="flex items-center space-x-3">
                <span class="bg-yellow-400 text-red-900 font-black px-3 py-1 rounded-lg">HB</span>
                <span class="font-bold text-lg">HokBen Internal System</span>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('menus.index') }}" class="bg-red-600 text-white font-bold px-4 py-2 rounded-lg text-sm shadow">
                    📋 Kelola Menu
                </a>
                <a href="{{ route('kasir.index') }}" class="bg-gray-700 hover:bg-gray-600 text-yellow-400 font-bold px-4 py-2 rounded-lg text-sm transition-all">
                    🖥️ Layar Kasir
                </a>
                <a href="{{ route('kiosk') }}" target="_blank" class="bg-yellow-500 hover:bg-yellow-400 text-red-950 font-bold px-4 py-2 rounded-lg text-sm transition-all">
                    📱 Buka Kios Pemesanan
                </a>
            </div>
        </nav>

        <div class="flex justify-between items-center mb-8 border-b pb-4">
            <div>
                <h1 class="text-2xl font-black text-red-700">Manajemen Menu HokBen</h1>
                <p class="text-xs text-gray-500">Tambah, edit, dan hapus menu katalog self-service</p>
            </div>
            <a href="{{ route('menus.create') }}" class="bg-yellow-400 hover:bg-yellow-500 text-red-950 font-black px-5 py-2.5 rounded-xl shadow transition-all">
                + Tambah Menu Baru
            </a>
        </div>

        <!-- SECTION QR CODE MEJA -->
        <div class="mb-8 p-6 bg-amber-50 rounded-2xl border-2 border-yellow-300 flex flex-col md:flex-row items-center justify-between gap-4 shadow-sm">
            <div>
                <h2 class="text-lg font-black text-red-700">QR Code Meja Pemesanan</h2>
                <p class="text-xs text-gray-600 mt-1">Cetak QR Code ini untuk ditaruh di meja pelanggan</p>
            </div>
            <div class="p-3 bg-white border border-yellow-200 rounded-xl shadow-md">
                {!! QrCode::size(150)->generate('https://hokben-kik.loca.lt/') !!}
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-100 border border-emerald-400 text-emerald-800 px-4 py-3 rounded-xl mb-6 font-semibold text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto rounded-xl border border-gray-200">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-900 text-white text-xs uppercase tracking-wider">
                        <th class="p-4">Foto</th>
                        <th class="p-4">Nama Menu</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4">Harga</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm">
                    @forelse($menus as $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="p-4">
                            @if($item->foto)
                                <img src="{{ asset('storage/' . $item->foto) }}" class="w-14 h-14 object-cover rounded-xl shadow-sm border">
                            @else
                                <span class="text-gray-400 text-xs italic">Tanpa Foto</span>
                            @endif
                        </td>
                        <td class="p-4 font-bold text-gray-800">{{ $item->nama }}</td>
                        <td class="p-4">
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-700">
                                {{ $item->kategori }}
                            </span>
                        </td>
                        <td class="p-4 font-black text-gray-900">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td class="p-4 text-center space-x-2">
                            <a href="{{ route('menus.edit', $item->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-3 py-1.5 rounded-lg text-xs shadow">Edit</a>
                            <form action="{{ route('menus.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus menu ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold px-3 py-1.5 rounded-lg text-xs shadow">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-gray-400">Belum ada menu yang ditambahkan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
