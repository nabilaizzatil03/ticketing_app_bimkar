<x-layouts.app>
  <section class="max-w-7xl mx-auto py-12 px-6">
    <h1 class="text-xl font-semibold mb-8 text-center">Riwayat Pembelian</h1>

    <div class="space-y-6">
      @forelse($histories as $history)
        <div class="card bg-base-100 shadow">
          <div class="card-body">
            <div class="grid grid-cols-12 gap-4 items-center">
              {{-- Image --}}
              <div class="col-span-12 md:col-span-3">
                <div class="flex items-center gap-4">
                  <img
                    class="w-24 h-24 rounded-lg object-cover"
                    src="{{ $history->event?->gambar ? asset('storage/'.$history->event->gambar) : 'https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp' }}"
                    alt="{{ $history->event?->judul ?? 'Event' }}"
                  />
                  <div class="md:hidden">
                    <div class="font-semibold">Order #{{ $history->id }}</div>
                    <div class="text-sm text-gray-500">
                      {{ \Carbon\Carbon::parse($history->created_at)->translatedFormat('d F Y, H:i') }}
                    </div>
                  </div>
                </div>
              </div>

              {{-- Middle info --}}
              <div class="col-span-12 md:col-span-6">
                <div class="hidden md:block font-semibold">Order #{{ $history->id }}</div>
                <div class="hidden md:block text-sm text-gray-500">
                  {{ \Carbon\Carbon::parse($history->created_at)->translatedFormat('d F Y, H:i') }}
                </div>

                <div class="mt-2 text-sm">
                  {{ $history->event?->judul ?? '-' }}
                </div>
              </div>

              {{-- Total + button --}}
              <div class="col-span-12 md:col-span-3 flex md:justify-end items-center gap-4">
                <div class="text-right flex-1 md:flex-none">
                  <div class="font-semibold">
                    Rp {{ number_format($history->total ?? 0, 0, ',', '.') }}
                  </div>
                </div>

                <a href="{{ route('histories.show', $history) }}"
                   class="btn btn-primary !bg-blue-900 text-white">
                  Lihat Detail
                </a>
              </div>
            </div>
          </div>
        </div>
      @empty
        <div class="alert alert-info">Belum ada riwayat pembelian.</div>
      @endforelse
    </div>
  </section>
</x-layouts.app>
