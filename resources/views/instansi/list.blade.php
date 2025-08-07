<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Kunjungan Instansi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Notifikasi Sukses atau Error --}}
            @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif
            @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
            @endif

            {{-- Pesan jika user sudah memilih --}}
            @if($pilihanUser)
            <div class="mb-6 p-4 bg-blue-100 border-l-4 border-blue-500 text-blue-700 rounded-r-lg">
                <p class="font-bold">Pilihan Final</p>
                <p>Anda telah memilih: <strong>{{ $pilihanUser->instansi->nama_instansi }}</strong>. Pilihan ini tidak
                    dapat diubah.</p>
            </div>
            @endif

            {{-- Grid untuk daftar instansi --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($instansis as $instansi)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col">
                    {{-- Gambar Instansi --}}
                    @if($instansi->images->isNotEmpty())
                    <img src="{{ asset($instansi->images->first()->path_gambar) }}" alt="{{ $instansi->nama_instansi }}"
                        class="w-full h-48 object-cover">
                    @else
                    <img src="https://placehold.co/600x400/e2e8f0/a0aec0?text=Gambar+Tidak+Tersedia"
                        alt="Gambar tidak tersedia" class="w-full h-48 object-cover">
                    @endif

                    <div class="p-6 text-gray-900 flex-grow">
                        <a href="{{ route('instansi.show', $instansi->id) }}">
                            <h3
                                class="font-semibold text-lg mb-2 text-gray-900 hover:text-indigo-600 transition-colors duration-300">
                                {{ $instansi->nama_instansi }}</h3>
                        </a>
                        <p class="text-sm text-gray-600 mb-4">{{ $instansi->alamat }}</p>

                        <div class="text-sm space-y-2">
                            <p><strong>Tanggal:</strong> {{
                                \Carbon\Carbon::parse($instansi->waktu_kunjungan)->isoFormat('dddd, D MMMM Y') }}</p>
                            <p><strong>Jam:</strong> {{ \Carbon\Carbon::parse($instansi->jam_kunjungan)->format('H:i')
                                }} WIB</p>
                        </div>

                        <div class="mt-4 pt-4 border-t">
                            <h4 class="font-semibold text-sm">Telah Dipilih oleh:</h4>
                            @if($instansi->pilihan->count() > 0)
                            <ul class="list-disc list-inside text-sm text-gray-700 mt-1">
                                @foreach($instansi->pilihan as $pilihan)
                                <li>{{ $pilihan->user->name }}</li>
                                @endforeach
                            </ul>
                            @else
                            <p class="text-sm text-gray-500 mt-1">Belum ada yang memilih.</p>
                            @endif
                        </div>
                    </div>

                    {{-- Footer Aksi --}}
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        @if(auth()->user()->role == 'admin')
                        @if(!$pilihanUser)
                        <form action="{{ route('instansi.pilih', $instansi->id) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin memilih instansi ini? Pilihan tidak dapat diubah.');">
                            @csrf
                            <x-primary-button class="w-full justify-center">
                                {{ __('Pilih Instansi Ini') }}
                            </x-primary-button>
                        </form>
                        @else
                        @if($pilihanUser->instansi_id == $instansi->id)
                        <button
                            class="w-full justify-center inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest"
                            disabled>
                            Telah Dipilih
                        </button>
                        @else
                        <x-secondary-button class="w-full justify-center" disabled>
                            {{ __('Pilih') }}
                        </x-secondary-button>
                        @endif
                        @endif
                        @endif

                        @if(auth()->user()->role == 'superadmin')
                        <div class="flex items-center justify-start space-x-2">
                            <a href="{{ route('instansi.edit', $instansi->id) }}"
                                class="inline-flex items-center px-4 py-2 bg-yellow-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-500 focus:bg-yellow-500 active:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Edit
                            </a>
                            <form action="{{ route('instansi.destroy', $instansi->id) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus instansi ini?');">
                                @csrf
                                @method('DELETE')
                                <x-danger-button type="submit">
                                    Hapus
                                </x-danger-button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-16">
                    <p class="text-gray-500">Belum ada data instansi yang ditambahkan.</p>
                    @if(auth()->user()->role == 'superadmin')
                    <div class="mt-4">
                        <a href="{{ route('instansi.create') }}">
                            <x-primary-button>
                                {{ __('+ Tambah Instansi Baru') }}
                            </x-primary-button>
                        </a>
                    </div>
                    @endif
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>