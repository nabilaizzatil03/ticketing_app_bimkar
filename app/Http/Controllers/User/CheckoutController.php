<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Tiket;
use App\Models\Pembelian;        // sesuaikan nama model kamu
use App\Models\DetailPembelian;  // sesuaikan nama model kamu
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => ['required', 'integer'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.tiket_id' => ['required', 'integer'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
        ]);

        $userId = auth()->id();

        $result = DB::transaction(function () use ($request, $userId) {
            // Lock tiket rows biar stok aman (ga dobel kebeli)
            $tiketIds = collect($request->items)->pluck('tiket_id')->toArray();

            $tikets = Tiket::whereIn('id', $tiketIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $total = 0;

            foreach ($request->items as $it) {
                $t = $tikets->get($it['tiket_id']);
                if (!$t) abort(422, 'Tiket tidak ditemukan.');

                if ((int)$it['qty'] > (int)$t->stok) {
                    abort(422, 'Stok tiket tidak mencukupi untuk: ' . $t->tipe);
                }

                $harga = (int)($t->harga ?? 0);
                $total += $harga * (int)$it['qty'];
            }

            // Simpan pembelian (sesuaikan kolom dengan tabel kamu)
            $pembelian = Pembelian::create([
                'user_id' => $userId,
                'event_id' => $request->event_id,
                'total' => $total,
                'status' => 'paid', // atau 'pending' kalau modul kamu pakai pending
            ]);

            // Simpan detail + kurangi stok
            foreach ($request->items as $it) {
                $t = $tikets[$it['tiket_id']];
                $qty = (int)$it['qty'];

                DetailPembelian::create([
                    'pembelian_id' => $pembelian->id,
                    'tiket_id' => $t->id,
                    'qty' => $qty,
                    'harga' => (int)($t->harga ?? 0),
                    'subtotal' => (int)($t->harga ?? 0) * $qty,
                ]);

                $t->decrement('stok', $qty);
            }

            return $pembelian;
        });

        return response()->json([
            'ok' => true,
            'redirect' => route('histories.index'), // sesuaikan nama route riwayat kamu
        ]);
    }
}
