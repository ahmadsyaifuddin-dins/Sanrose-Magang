<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($instansi) ? __('Edit Instansi') : __('Tambah Instansi Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Menampilkan error validasi --}}
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Whoops!</strong>
                            <span class="block sm:inline">Ada beberapa masalah dengan input Anda.</span>
                            <ul class="mt-3 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ isset($instansi) ? route('instansi.update', $instansi->id) : route('instansi.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(isset($instansi))
                            @method('PUT')
                        @endif

                        <!-- Nama Instansi -->
                        <div class="mb-4">
                            <x-input-label for="nama_instansi" :value="__('Nama Instansi')" />
                            <x-text-input id="nama_instansi" class="block mt-1 w-full" type="text" name="nama_instansi" :value="old('nama_instansi', $instansi->nama_instansi ?? '')" required autofocus />
                            <x-input-error :messages="$errors->get('nama_instansi')" class="mt-2" />
                        </div>

                        <!-- Alamat -->
                        <div class="mb-4">
                            <x-input-label for="alamat" :value="__('Alamat')" />
                            <textarea id="alamat" name="alamat" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('alamat', $instansi->alamat ?? '') }}</textarea>
                            <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                        </div>

                        <!-- Waktu & Jam Kunjungan -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="waktu_kunjungan" :value="__('Tanggal Kunjungan')" />
                                <x-text-input id="waktu_kunjungan" class="block mt-1 w-full" type="date" name="waktu_kunjungan" :value="old('waktu_kunjungan', $instansi->waktu_kunjungan ?? '')" required />
                                <x-input-error :messages="$errors->get('waktu_kunjungan')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="jam_kunjungan" :value="__('Jam Kunjungan')" />
                                <x-text-input id="jam_kunjungan" class="block mt-1 w-full" type="time" name="jam_kunjungan" :value="old('jam_kunjungan', $instansi->jam_kunjungan ?? '')" required />
                                <x-input-error :messages="$errors->get('jam_kunjungan')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Latitude & Longitude -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="latitude" :value="__('Latitude')" />
                                <x-text-input id="latitude" class="block mt-1 w-full" type="text" name="latitude" :value="old('latitude', $instansi->latitude ?? '')" placeholder="-6.200000" />
                                <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="longitude" :value="__('Longitude')" />
                                <x-text-input id="longitude" class="block mt-1 w-full" type="text" name="longitude" :value="old('longitude', $instansi->longitude ?? '')" placeholder="106.816666" />
                                <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Upload Gambar -->
                        <div class="mb-4">
                             <x-input-label for="gambar" :value="__('Gambar Lokasi (Bisa lebih dari satu)')" />
                             <input id="gambar" name="gambar[]" type="file" multiple class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 mt-1"/>
                             <x-input-error :messages="$errors->get('gambar.*')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('instansi.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                {{ __('Batal') }}
                            </a>

                            <x-primary-button>
                                {{ __('Simpan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
