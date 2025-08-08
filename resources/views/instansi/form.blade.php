<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-indigo-100 dark:bg-indigo-900/50 rounded-lg">
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h2 class="font-bold text-2xl text-gray-900 dark:text-white leading-tight">
                {{ isset($instansi) ? __('Edit Instansi') : __('Tambah Instansi Baru') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-200 dark:border-gray-700">
                <div class="p-8">
                    
                    {{-- Enhanced Error Display --}}
                    @if ($errors->any())
                        <div class="mb-6 bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 border-l-4 border-red-500 text-red-700 dark:text-red-300 px-6 py-4 rounded-r-lg shadow-sm" role="alert">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-red-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="font-semibold text-red-800 dark:text-red-200">Terdapat Kesalahan Input</h3>
                                    <ul class="mt-2 text-sm space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li class="flex items-center">
                                                <span class="w-1 h-1 bg-red-500 rounded-full mr-2"></span>
                                                {{ $error }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ isset($instansi) ? route('instansi.update', $instansi->id) : route('instansi.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @if(isset($instansi))
                            @method('PUT')
                        @endif

                        {{-- Form Section: Basic Information --}}
                        <div class="space-y-6">
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Informasi Dasar
                                </h3>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Masukkan informasi dasar tentang instansi</p>
                            </div>

                            <!-- Nama Instansi -->
                            <div class="space-y-2">
                                <x-input-label for="nama_instansi" :value="__('Nama Instansi')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                                <x-text-input id="nama_instansi" 
                                    class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 rounded-lg shadow-sm transition-colors duration-200" 
                                    type="text" 
                                    name="nama_instansi" 
                                    :value="old('nama_instansi', $instansi->nama_instansi ?? '')" 
                                    required 
                                    autofocus 
                                    placeholder="Contoh: Kantor Dinas Pendidikan" />
                                <x-input-error :messages="$errors->get('nama_instansi')" class="mt-2" />
                            </div>

                            <!-- Alamat -->
                            <div class="space-y-2">
                                <x-input-label for="alamat" :value="__('Alamat')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                                <textarea id="alamat" 
                                    name="alamat" 
                                    rows="4" 
                                    class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 rounded-lg shadow-sm transition-colors duration-200 resize-none" 
                                    required
                                    placeholder="Masukkan alamat lengkap instansi...">{{ old('alamat', $instansi->alamat ?? '') }}</textarea>
                                <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Form Section: Visit Schedule --}}
                        <div class="space-y-6">
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Jadwal Kunjungan
                                </h3>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Tentukan waktu kunjungan ke instansi</p>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <x-input-label for="waktu_kunjungan" :value="__('Tanggal Kunjungan')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                                    <x-text-input id="waktu_kunjungan" 
                                        class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 rounded-lg shadow-sm transition-colors duration-200" 
                                        type="date" 
                                        name="waktu_kunjungan" 
                                        :value="old('waktu_kunjungan', $instansi->waktu_kunjungan ?? '')" 
                                        required />
                                    <x-input-error :messages="$errors->get('waktu_kunjungan')" class="mt-2" />
                                </div>
                                <div class="space-y-2">
                                    <x-input-label for="jam_kunjungan" :value="__('Jam Kunjungan')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                                    <x-text-input id="jam_kunjungan" 
                                        class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 rounded-lg shadow-sm transition-colors duration-200" 
                                        type="time" 
                                        name="jam_kunjungan" 
                                        :value="old('jam_kunjungan', $instansi->jam_kunjungan ?? '')" 
                                        required />
                                    <x-input-error :messages="$errors->get('jam_kunjungan')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        {{-- Form Section: Location Coordinates --}}
                        <div class="space-y-6">
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Koordinat Lokasi
                                </h3>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Koordinat GPS untuk penentuan lokasi pada peta</p>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <x-input-label for="latitude" :value="__('Latitude')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                                    <x-text-input id="latitude" 
                                        class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 rounded-lg shadow-sm transition-colors duration-200" 
                                        type="text" 
                                        name="latitude" 
                                        :value="old('latitude', $instansi->latitude ?? '')" 
                                        placeholder="-6.200000" />
                                    <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                                </div>
                                <div class="space-y-2">
                                    <x-input-label for="longitude" :value="__('Longitude')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                                    <x-text-input id="longitude" 
                                        class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 rounded-lg shadow-sm transition-colors duration-200" 
                                        type="text" 
                                        name="longitude" 
                                        :value="old('longitude', $instansi->longitude ?? '')" 
                                        placeholder="106.816666" />
                                    <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        {{-- Form Section: Image Upload --}}
                        <div class="space-y-6">
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Gambar Lokasi
                                </h3>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Upload gambar untuk menampilkan lokasi instansi</p>
                            </div>

                            <div class="space-y-2">
                                <x-input-label for="gambar" :value="__('Tambah Gambar Lokasi Baru')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                                <div class="relative">
                                    <input id="gambar" 
                                        name="gambar[]" 
                                        type="file" 
                                        multiple 
                                        accept="image/*"
                                        class="block w-full text-sm text-gray-500 dark:text-gray-400 
                                               file:mr-4 file:py-3 file:px-4 file:rounded-lg file:border-0 
                                               file:text-sm file:font-semibold 
                                               file:bg-indigo-50 file:text-indigo-700 
                                               dark:file:bg-indigo-900/50 dark:file:text-indigo-300
                                               hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900/70
                                               file:transition-colors file:duration-200
                                               border border-gray-300 dark:border-gray-600 
                                               rounded-lg bg-white dark:bg-gray-700
                                               focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 
                                               focus:border-transparent"/>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Format yang didukung: JPG, PNG, GIF. Maksimal 2MB per file.</p>
                                <x-input-error :messages="$errors->get('gambar.*')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Enhanced Existing Images Section --}}
                        @if(isset($instansi) && $instansi->images->isNotEmpty())
                        <div class="space-y-6">
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3l14 9-9 3-3-9z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3 3l7-7"></path>
                                    </svg>
                                    Pilih Gambar Thumbnail
                                </h3>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Klik pada gambar untuk menjadikannya sebagai gambar utama yang akan tampil di daftar instansi</p>
                            </div>
                            
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                                @foreach($instansi->images as $image)
                                <div class="relative group">
                                    <label for="default_{{ $image->id }}" class="cursor-pointer block">
                                        <div class="relative overflow-hidden rounded-xl transition-all duration-300 {{ $image->is_default ? 'ring-4 ring-indigo-500 ring-offset-2 dark:ring-offset-gray-800' : 'hover:ring-2 hover:ring-gray-300 dark:hover:ring-gray-600' }}">
                                            <img src="{{ asset($image->path_gambar) }}" 
                                                 alt="Gambar Instansi" 
                                                 class="w-full h-32 object-cover transition-transform duration-300 group-hover:scale-105">
                                            
                                            {{-- Overlay for default image --}}
                                            @if($image->is_default)
                                                <div class="absolute inset-0 bg-indigo-500 bg-opacity-20 flex items-center justify-center">
                                                    <div class="bg-indigo-500 text-white text-xs px-2 py-1 rounded-full font-semibold flex items-center">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Utama
                                                    </div>
                                                </div>
                                            @else
                                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                                                    <span class="text-white text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-gray-900 px-2 py-1 rounded">
                                                        Klik untuk pilih
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        <input type="radio" 
                                               name="default_image_id" 
                                               id="default_{{ $image->id }}" 
                                               value="{{ $image->id }}" 
                                               class="sr-only" 
                                               {{ $image->is_default ? 'checked' : '' }}>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        {{-- Enhanced Action Buttons --}}
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('instansi.index') }}" 
                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                {{ __('Batal') }}
                            </a>

                            <x-primary-button class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 focus:ring-indigo-500 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ isset($instansi) ? __('Update Instansi') : __('Simpan Instansi') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript for enhanced interactions --}}
    <script>
        // Enhanced radio button interactions for image selection
        document.querySelectorAll('input[name="default_image_id"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                // Remove active styling from all images
                document.querySelectorAll('input[name="default_image_id"]').forEach(function(otherRadio) {
                    const label = otherRadio.closest('label');
                    const img = label.querySelector('div');
                    img.className = img.className.replace(/ring-4 ring-indigo-500 ring-offset-2 dark:ring-offset-gray-800/, 'hover:ring-2 hover:ring-gray-300 dark:hover:ring-gray-600');
                });
                
                // Add active styling to selected image
                const label = this.closest('label');
                const img = label.querySelector('div');
                img.className = img.className.replace(/hover:ring-2 hover:ring-gray-300 dark:hover:ring-gray-600/, 'ring-4 ring-indigo-500 ring-offset-2 dark:ring-offset-gray-800');
            });
        });

        // File input enhancement
        document.getElementById('gambar').addEventListener('change', function() {
            const fileCount = this.files.length;
            if (fileCount > 0) {
                const fileNames = Array.from(this.files).map(file => file.name).join(', ');
                console.log(`Selected ${fileCount} file(s): ${fileNames}`);
                
                // You can add a preview functionality here if needed
            }
        });
    </script>
</x-app-layout>