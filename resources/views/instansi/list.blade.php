<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Kunjungan Instansi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Notifikasi Sukses atau Error --}}
            @if(session('success'))
            <div class="mb-4 bg-green-100 dark:bg-green-800/30 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded relative"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif
            @if(session('error'))
            <div class="mb-4 bg-red-100 dark:bg-red-800/30 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-300 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
            @endif

            {{-- Pesan jika user sudah memilih --}}
            @if($pilihanUser)
            <div class="mb-6 p-4 bg-blue-100 dark:bg-blue-800/30 border-l-4 border-blue-500 dark:border-blue-400 text-blue-700 dark:text-blue-300 rounded-r-lg">
                <p class="font-bold">Pilihan Final</p>
                <p>Anda telah memilih: <strong>{{ $pilihanUser->instansi->nama_instansi }}</strong>. Pilihan ini tidak
                    dapat diubah.</p>
            </div>
            @endif

            {{-- Grid untuk daftar instansi --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($instansis as $instansi)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex flex-col border border-gray-200 dark:border-gray-700">
                    {{-- Gambar Instansi --}}
                    @if($instansi->images->isNotEmpty())
                    <img src="{{ asset($instansi->images->where('is_default', true)->first()->path_gambar) }}" alt="{{ $instansi->nama_instansi }}"
                        class="w-full h-48 object-cover">
                    @else
                    <div class="w-full h-48 bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                        <div class="text-center text-gray-500 dark:text-gray-400">
                            <svg class="mx-auto h-12 w-12 mb-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1zM4 7v10h16V7H4zm8 2l4 4H8l4-4z"/>
                            </svg>
                            <p class="text-sm">Gambar Tidak Tersedia</p>
                        </div>
                    </div>
                    @endif

                    <div class="p-6 text-gray-900 dark:text-gray-100 flex-grow">
                        <a href="{{ route('instansi.show', $instansi->id) }}">
                            <h3
                                class="font-semibold text-lg mb-2 text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors duration-300">
                                {{ $instansi->nama_instansi }}</h3>
                        </a>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ $instansi->alamat }}</p>

                        <div class="text-sm space-y-2">
                            <p class="text-gray-700 dark:text-gray-300"><strong>Tanggal:</strong> {{
                                \Carbon\Carbon::parse($instansi->waktu_kunjungan)->isoFormat('dddd, D MMMM Y') }}</p>
                            <p class="text-gray-700 dark:text-gray-300"><strong>Jam:</strong> {{ \Carbon\Carbon::parse($instansi->jam_kunjungan)->format('H:i')
                                }} WIB</p>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                            <h4 class="font-semibold text-sm text-gray-900 dark:text-gray-100">Telah Dipilih oleh:</h4>
                            @if($instansi->pilihan->count() > 0)
                            <ul class="list-disc list-inside text-sm text-gray-700 dark:text-gray-300 mt-1">
                                @foreach($instansi->pilihan as $pilihan)
                                <li>{{ $pilihan->user->name }}</li>
                                @endforeach
                            </ul>
                            @else
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Belum ada yang memilih.</p>
                            @endif
                        </div>
                    </div>

                    {{-- Footer Aksi --}}
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-600">
                        @if(auth()->user()->role == 'maganger')
                        @if(!$pilihanUser)
                        <form action="{{ route('instansi.pilih', $instansi->id) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin memilih instansi ini? Pilihan tidak dapat diubah.');">
                            @csrf
                            <x-primary-button class="w-full justify-center bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                                {{ __('Pilih Instansi Ini') }}
                            </x-primary-button>
                        </form>
                        @else
                        @if($pilihanUser->instansi_id == $instansi->id)
                        <button
                            class="w-full justify-center inline-flex items-center px-4 py-2 bg-green-600 dark:bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest"
                            disabled>
                            Telah Dipilih
                        </button>
                        @else
                        <x-secondary-button class="w-full justify-center bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300" disabled>
                            {{ __('Pilih') }}
                        </x-secondary-button>
                        @endif
                        @endif
                        @endif

                        @if(auth()->user()->role == 'superadmin')
                        <div class="flex items-center justify-start space-x-2">
                            <a href="{{ route('instansi.edit', $instansi->id) }}"
                                class="inline-flex items-center px-4 py-2 bg-yellow-400 dark:bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-500 dark:hover:bg-yellow-600 focus:bg-yellow-500 dark:focus:bg-yellow-600 active:bg-yellow-600 dark:active:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 dark:focus:ring-yellow-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Edit
                            </a>
                            <form action="{{ route('instansi.destroy', $instansi->id) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus instansi ini?');">
                                @csrf
                                @method('DELETE')
                                <x-danger-button type="submit" class="bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600">
                                    Hapus
                                </x-danger-button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-16">
                    <div class="text-gray-500 dark:text-gray-400">
                        <svg class="mx-auto h-16 w-16 mb-4 text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1zM4 7v10h16V7H4zm8 2l4 4H8l4-4z"/>
                        </svg>
                        <p class="text-lg font-medium mb-2">Belum ada data instansi</p>
                        <p class="text-sm">Belum ada data instansi yang ditambahkan.</p>
                    </div>
                    @if(auth()->user()->role == 'superadmin')
                    <div class="mt-6">
                        <a href="{{ route('instansi.create') }}">
                            <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600">
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