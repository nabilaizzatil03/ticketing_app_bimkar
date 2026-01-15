<x-layouts.admin>
  <section class="max-w-5xl mx-auto py-12 px-6">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-xl font-semibold">Detail Pemesanan</h1>

      <div class="text-sm text-gray-500">
        Order #{{ $history->id }} •
        {{ \Carbon\Carbon::parse($history->order_date ?? $history->created_at)->translatedFormat('d F Y, H:i') }}
      </div>
    </div>

    <div class="card bg-base-100 shadow">
      <div class="card-body">
        <div class="grid grid-cols-12 gap-4 items-start">

          {{-- Event info --}}
          <div class="col-span-12 md:col-span-6">
            <div class="flex gap-4">
              <img
                class="w-24 h-24 rounded-lg object-cover"
                src="{{ $history->event?->gambar ? asset('storage/'.$history->event->gambar) : 'https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp' }}"
                alt="{{ $history->event?->judul ?? 'Event' }}"
              />

              <div>
                <div class="font-semibold text-lg">{{ $history->event?->judul ?? '-' }}</div>
                <div class="text-sm text-gray-500">{{ $history->event?->lokasi ?? '-' }}</div>
              </div>
            </div>
          </div>

          {{-- Items --}}
          <div class="col-span-12 md:col-span-4">
            @forelse($history->detailOrders as $detail)
              <div class="text-sm mb-3">
                <div class="font-semibold">{{ $detail->tiket?->tipe ?? '-' }}</div>

                {{-- biasanya field detail_orders = jumlah, bukan qty --}}
                <div class="text-gray-500">Qty: {{ $detail->jumlah ?? 0 }}</div>

                {{-- optional kalau ada subtotal_harga di detail_orders --}}
                @if(isset($detail->subtotal_harga))
                  <div class="text-gray-500">
                    Subtotal: Rp {{ number_format($detail->subtotal_harga ?? 0, 0, ',', '.') }}
                  </div>
                @endif
              </div>
            @empty
              <div class="text-sm text-gray-500">Tidak ada detail tiket.</div>
            @endforelse
          </div>

          {{-- Total --}}
          <div class="col-span-12 md:col-span-2 text-right">
            <div class="font-semibold">
              Rp {{ number_format($history->total_harga ?? 0, 0, ',', '.') }}
            </div>
          </div>

        </div>

        <div class="divider"></div>

        <div class="flex justify-between items-center">
          <div class="font-semibold">Total</div>
          <div class="font-semibold text-lg">
            Rp {{ number_format($history->total_harga ?? 0, 0, ',', '.') }}
          </div>
        </div>
      </div>
    </div>

    <div class="mt-8">
      <a href="{{ route('admin.histories.index') }}"
         class="btn btn-primary !bg-indigo-600 text-white">
        Kembali ke Riwayat Pembelian
      </a>
    </div>
  </section>
</x-layouts.admin>
