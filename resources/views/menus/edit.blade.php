<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Menu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold mb-4 text-red-600">Edit Menu</h2>
        <form action="{{ route('menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block font-semibold">Nama Menu:</label>
                <input type="text" name="nama" value="{{ $menu->nama }}" required class="w-full border p-2 rounded focus:outline-none focus:border-red-500">
            </div>
            <div>
                <label class="block font-semibold">Kategori:</label>
                <select name="kategori" required class="w-full border p-2 rounded">
                    <option value="Makanan" {{ $menu->kategori == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                    <option value="Minuman" {{ $menu->kategori == 'Minuman' ? 'selected' : '' }}>Minuman</option>
                </select>
            </div>
            <div>
                <label class="block font-semibold">Harga (Rp):</label>
                <input type="number" name="harga" value="{{ $menu->harga }}" required class="w-full border p-2 rounded focus:outline-none focus:border-red-500">
            </div>
            <div>
                <label class="block font-semibold">Foto Menu (Biarkan kosong jika tidak diubah):</label>
                <input type="file" name="foto" accept="image/*" class="w-full border p-1 rounded">
            </div>
            <div class="flex justify-between">
                <a href="{{ route('menus.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</a>
                <button type="submit" class="bg-blue-600 text-white font-bold px-4 py-2 rounded">Update</button>
            </div>
        </form>
    </div>
</body>
</html>