<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class OrderController extends Controller
{
    public function kiosk()
    {
        $menus = Menu::all();
        return view('order', compact('menus'));
    }

    public function store(Request $request)
{
    $request->validate([
        'nama_pemesan' => 'required|string|max:100',
        'metode_pembayaran' => 'required|string',
        'cart_data' => 'required'
    ]);

    $cart = json_decode($request->cart_data, true);
    if (empty($cart)) {
        return redirect()->back()->with('error', 'Pilih minimal 1 menu!');
    }

    $detail = [];
    $total = 0;
    foreach ($cart as $item) {
        $detail[] = $item['nama'] . " x" . $item['qty'];
        $total += ($item['harga'] * $item['qty']);
    }

    $today = now()->format('Y-m-d');
    $lastOrder = Order::whereDate('created_at', $today)->max('nomor_antrean');
    $nomorAntrean = ($lastOrder ?? 0) + 1;

    // 1. Simpan order ke database
    $order = Order::create([
        'nomor_antrean' => $nomorAntrean,
        'nama_pemesan' => $request->nama_pemesan,
        'detail_pesanan' => implode(", ", $detail),
        'total_harga' => $total,
        'metode_pembayaran' => $request->metode_pembayaran,
        'status' => 'Menunggu'
    ]);

    // 2. Cek apakah pengguna memilih Midtrans (Online)
    if ($request->metode_pembayaran === 'Midtrans') {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => 'HOKBEN-' . $order->id . '-' . time(),
                'gross_amount' => $total,
            ],
            'customer_details' => [
                'first_name' => $request->nama_pemesan,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return response()->json([
            'snap_token' => $snapToken,
            'order_id' => $order->id
        ]);
    }

    // 3. Jika memilih "Kasir", kembalikan response TANPA snap_token
    return response()->json([
        'snap_token' => null,
        'order_id' => $order->id
    ]);
}

    public function struk($id)
    {
        $order = Order::findOrFail($id);
        return view('struk', compact('order'));
    }


    // Halaman Dashboard Kasir
    public function kasir()
    {
        $orders = Order::where('status', '!=', 'Selesai')->orderBy('id', 'asc')->get();
        return view('kasir', compact('orders'));
    }

    // Update Status Pesanan (Dipanggil / Selesai)
    public function updateStatus($id, $status)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => $status]);
        return response()->json(['success' => true]);
    }
}
