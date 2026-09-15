<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HokBen Self Order</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
</head>
<body class="bg-gradient-to-br from-amber-400 via-yellow-400 to-amber-500 font-sans min-h-screen pb-10">

    <!-- HEADER BAR -->
    <header class="bg-red-700 text-white py-4 px-6 shadow-xl sticky top-0 z-40 border-b-4 border-yellow-300 flex justify-between items-center">
        <div class="flex items-center space-x-3">
            <div class="bg-yellow-400 text-red-700 font-black text-xl px-3 py-1 rounded-lg shadow">HB</div>
            <div>
                <h1 class="text-2xl font-black tracking-wide leading-none">HokBen <span class="text-yellow-300">Self Order</span></h1>
                <p class="text-xs text-red-200 mt-0.5">Pesan cepat tanpa antre!</p>
            </div>
        </div>
    </header>

    <!-- CONTAINER UTAMA -->
    <main class="max-w-7xl mx-auto px-4 mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- KATALOG MENU (KOLOM KIRI) -->
        <section class="lg:col-span-2">
            <h2 class="text-lg font-bold text-red-900 mb-4 flex items-center gap-2">
                <span>🍽️</span> Pilih Menu Favorit
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                @foreach($menus as $menu)
                    <div class="bg-white rounded-2xl p-4 shadow-md hover:shadow-xl transition-all duration-300 flex flex-col justify-between border border-yellow-200/50 group">
                        <div>
                            <div class="relative overflow-hidden rounded-xl mb-3 bg-gray-100">
                                @if($menu->foto)
                                    <img src="{{ asset('storage/' . $menu->foto) }}" alt="{{ $menu->nama }}" class="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-40 flex items-center justify-center text-gray-400 text-sm font-semibold">No Image Available</div>
                                @endif
                                <span class="absolute top-2 left-2 bg-red-600/90 text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider backdrop-blur-sm">
                                    {{ $menu->kategori }}
                                </span>
                            </div>

                            <h3 class="font-extrabold text-gray-800 text-lg group-hover:text-red-600 transition-colors">{{ $menu->nama }}</h3>
                            <p class="text-red-600 font-black text-xl mt-1">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                        </div>

                        <button onclick="addToCart({{ $menu->id }}, '{{ $menu->nama }}', {{ $menu->harga }})"
                                class="mt-4 w-full bg-gradient-to-r from-yellow-400 to-amber-500 hover:from-yellow-500 hover:to-amber-600 text-red-950 font-black py-2.5 px-4 rounded-xl shadow-md active:scale-95 transition-all">
                            + Tambah Pesanan
                        </button>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- KERANJANG PESANAN (KOLOM KANAN) -->
        <section class="lg:col-span-1">
            <div class="bg-white p-6 rounded-2xl shadow-xl sticky top-24 border border-yellow-200">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                    <h2 class="text-xl font-black text-red-700 flex items-center gap-2">
                        <span>🛒</span> Ringkasan Pesanan
                    </h2>
                </div>

                <div id="cart-list" class="space-y-3 max-h-80 overflow-y-auto pr-1 min-h-[160px]">
                    <p class="text-gray-400 text-sm text-center py-10">Keranjang belanja masih kosong.</p>
                </div>

                <div class="border-t border-dashed border-gray-200 pt-4 mt-4 space-y-2">
                    <div class="flex justify-between items-center text-gray-800">
                        <span class="font-bold text-lg">Total Pembayaran</span>
                        <span class="font-black text-2xl text-red-600">Rp <span id="total-price">0</span></span>
                    </div>
                </div>

                <button onclick="openCheckoutModal()" class="w-full mt-6 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-black py-4 rounded-xl shadow-lg hover:shadow-red-600/30 transition-all active:scale-98">
                    Kirim Pesanan Sekarang
                </button>
            </div>
        </section>
    </main>

    <!-- MODAL POPUP CHECKOUT -->
    <div id="checkoutModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center p-4 z-50">
        <div class="bg-white w-full max-w-md rounded-3xl p-6 shadow-2xl transform transition-all">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h3 class="text-xl font-black text-gray-800">Detail Pemesanan</h3>
                <button onclick="closeCheckoutModal()" class="text-gray-400 hover:text-gray-600 font-bold text-xl">&times;</button>
            </div>

            <form id="orderForm" onsubmit="submitOrder(event)">
                @csrf
                <input type="hidden" name="cart_data" id="cart_data">

                <div class="mb-5">
                    <label class="block text-sm font-extrabold text-gray-700 mb-2">Atas Nama Pemesan</label>
                    <input type="text" name="nama_pemesan" required placeholder="Masukkan nama kamu..."
                           class="w-full border-2 border-gray-200 p-3 rounded-xl focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-extrabold text-gray-700 mb-2">Pilih Metode Pembayaran</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="border-2 border-gray-200 p-3 rounded-xl flex items-center space-x-2 cursor-pointer hover:border-yellow-400 hover:bg-yellow-50/50 transition-all">
                            <input type="radio" name="metode_pembayaran" value="Midtrans" checked class="text-red-600 focus:ring-red-500">
                            <span class="font-bold text-sm text-gray-800">QRIS / Online</span>
                        </label>
                        <label class="border-2 border-gray-200 p-3 rounded-xl flex items-center space-x-2 cursor-pointer hover:border-yellow-400 hover:bg-yellow-50/50 transition-all">
                            <input type="radio" name="metode_pembayaran" value="Kasir" class="text-red-600 focus:ring-red-500">
                            <span class="font-bold text-sm text-gray-800">Bayar di Kasir</span>
                        </label>
                    </div>
                </div>

                <div class="flex space-x-3">
                    <button type="button" onclick="closeCheckoutModal()" class="w-1/2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 rounded-xl transition-all">Batal</button>
                    <button type="submit" class="w-1/2 bg-red-600 hover:bg-red-700 text-white font-black py-3 rounded-xl shadow-lg transition-all">Proses Bayar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT KERANJANG & MIDTRANS -->
    <script>
        let cart = [];

        function addToCart(id, nama, harga) {
            let item = cart.find(i => i.id === id);
            if (item) { item.qty++; } else { cart.push({ id, nama, harga, qty: 1 }); }
            renderCart();
        }

        function updateQuantity(id, change) {
            let item = cart.find(i => i.id === id);
            if (item) {
                item.qty += change;
                if (item.qty <= 0) { cart = cart.filter(i => i.id !== id); }
            }
            renderCart();
        }

        function renderCart() {
            let list = document.getElementById('cart-list');
            let total = 0;
            list.innerHTML = '';

            if (cart.length === 0) {
                list.innerHTML = '<p class="text-gray-400 text-sm text-center py-10">Keranjang belanja masih kosong.</p>';
            } else {
                cart.forEach(item => {
                    total += item.harga * item.qty;
                    list.innerHTML += `
                    <div class="flex justify-between items-center bg-gray-50 p-3 rounded-xl border border-gray-100">
                        <div>
                            <span class="font-extrabold text-sm text-gray-800 block">${item.nama}</span>
                            <span class="text-xs text-red-600 font-bold">Rp ${item.harga.toLocaleString('id-ID')}</span>
                        </div>
                        <div class="flex items-center space-x-2 bg-white px-2 py-1 rounded-lg border shadow-sm">
                            <button onclick="updateQuantity(${item.id}, -1)" class="w-6 h-6 rounded bg-red-100 hover:bg-red-200 text-red-700 font-black text-xs flex items-center justify-center">-</button>
                            <span class="font-extrabold text-sm px-1">${item.qty}</span>
                            <button onclick="updateQuantity(${item.id}, 1)" class="w-6 h-6 rounded bg-green-100 hover:bg-green-200 text-green-700 font-black text-xs flex items-center justify-center">+</button>
                        </div>
                    </div>`;
                });
            }

            document.getElementById('total-price').innerText = total.toLocaleString('id-ID');
            document.getElementById('cart_data').value = JSON.stringify(cart);
        }

        function openCheckoutModal() {
            if (cart.length === 0) { alert("Pilih minimal 1 menu sebelum checkout!"); return; }
            document.getElementById('checkoutModal').classList.remove('hidden');
            document.getElementById('checkoutModal').classList.add('flex');
        }

        function closeCheckoutModal() {
            document.getElementById('checkoutModal').classList.remove('flex');
            document.getElementById('checkoutModal').classList.add('hidden');
        }

        function submitOrder(e) {
            e.preventDefault();
            if (cart.length === 0) return;

            let formData = new FormData(document.getElementById('orderForm'));

            fetch("{{ route('order.store') }}", {
                method: "POST",
                body: formData,
                headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
            })
            .then(response => response.json())
            .then(data => {
                closeCheckoutModal();
                if (data.snap_token) {
                    snap.pay(data.snap_token, {
                        onSuccess: function() { window.location.href = "/order/struk/" + data.order_id; },
                        onPending: function() { window.location.href = "/order/struk/" + data.order_id; },
                        onError: function() { alert("Pembayaran gagal!"); }
                    });
                } else {
                    window.location.href = "/order/struk/" + data.order_id;
                }
            });
        }
    </script>
</body>
</html>
