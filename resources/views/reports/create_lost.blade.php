@extends('layouts.app')

@section('title', 'Lapor Barang Hilang')

@section('content')

@include('partials.navbar')

<div class="pt-24 pb-12 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
    
    <div class="md:grid md:grid-cols-3 md:gap-6">
        
        {{-- Kolom Kiri: Penjelasan --}}
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Detail Kehilangan</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Ceritakan sedetail mungkin barang apa yang hilang, kapan, dan di mana.
                </p>
                <div class="mt-4 bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <div class="flex">
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <strong>Tips:</strong> Upload foto asli atau foto barang sejenis biar orang lebih gampang ngenalinnya.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Form --}}
        <div class="mt-5 md:mt-0 md:col-span-2">
            
            {{-- Form start --}}
            {{-- ENCTYPE multipart/form-data WAJIB kalau ada upload file --}}
            <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                {{-- Hidden Input Type --}}
                <input type="hidden" name="type" value="lost">

                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">

                        {{-- Judul Barang --}}
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Nama Barang</label>
                            <div class="mt-1">
                                <input type="text" name="title" id="title" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full px-4 py-2 sm:text-sm border-gray-300 rounded-md" placeholder="Contoh: Dompet Kulit Coklat Merk X" value="{{ old('title') }}">
                            </div>
                            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Deskripsi --}}
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi Ciri-Ciri</label>
                            <div class="mt-1">
                                <textarea id="description" name="description" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full px-4 py-2 sm:text-sm border border-gray-300 rounded-md" placeholder="Ada stiker di bagian belakang, lecet sedikit di ujung kanan...">{{ old('description') }}</textarea>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Jelaskan warna, ukuran, atau tanda khusus.</p>
                            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Grid Lokasi & Tanggal --}}
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-3">
                                <label for="location" class="block text-sm font-medium text-gray-700">Lokasi Hilang</label>
                                <input type="text" name="location" id="location" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full px-4 py-2 shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Gedung A, Kantin, dll" value="{{ old('location') }}">
                                @error('location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="date_lost" class="block text-sm font-medium text-gray-700">Tanggal Kejadian</label>
                                <input type="date" name="date_lost" id="date_lost" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full px-4 py-2 shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('date_lost') }}">
                                @error('date_lost') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Upload Foto --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Foto Barang</label>
                            <div id='drop-zone' class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md relative hover:bg-gray-50 transition">
                                <div class="space-y-1 text-center">
                                    
                                    {{-- Preview Image Container --}}
                                    <div id="image-preview" class="hidden mb-4 relative w-fit mx-auto">
    
                                    {{-- Gambar Preview --}}
                                    <img src="" alt="Preview" class="h-48 object-contain rounded-md shadow-sm border border-gray-200">
                                    
                                    {{-- Tombol Hapus (X) --}}
                                    <button type="button" onclick="removeImage()" 
                                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-md hover:bg-red-600 focus:outline-none transition-colors duration-200"
                                            title="Hapus foto">
                                        {{-- Ikon X SVG --}}
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>

                                </div>
                                    
                                    {{-- Default Icon --}}
                                    <div id="upload-icon">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>

                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <label for="photo" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                                            <span>Upload file</span>
                                            <input id="photo" name="photo" type="file" class="sr-only" onchange="if(this.files.length > 0) previewImage(this.files[0])">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                </div>
                            </div>
                            @error('photo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                    </div>
                    
                    {{-- Footer Button --}}
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 flex justify-end items-center">
                      
                      {{-- Tombol Batal / Kembali --}}
                      {{-- Kita arahkan balik ke Dashboard --}}
                      <a href="{{ route('dashboard') }}" class="mr-3 bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                          Batal
                      </a>

                      {{-- Tombol Submit (Yang Tadi) --}}
                      <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                          Kirim Laporan
                      </button>

                  </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Ambil elemen-elemen yang dibutuhkan
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('photo');
    const previewContainer = document.getElementById('image-preview');
    const previewImg = previewContainer.querySelector('img');
    const uploadIcon = document.getElementById('upload-icon');

    // Mencegah default behavior browser (membuka file di tab baru)
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    // Efek visual saat file di-drag masuk ke area
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    // Hapus efek visual saat file keluar area
    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropZone.classList.add('border-indigo-500', 'bg-indigo-50'); // Ganti warna jadi ungu
    }

    function unhighlight(e) {
        dropZone.classList.remove('border-indigo-500', 'bg-indigo-50'); // Balikin jadi abu-abu
    }

    // Handle saat file dilepas (DROP)
    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        // Panggil fungsi preview
        if (files.length > 0) {
            fileInput.files = files; // Assign file ke input
            previewImage(files[0]); // Tampilkan preview
        }
    }

    // Fungsi untuk menampilkan preview gambar
    function previewImage(file) {

      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewContainer.classList.remove('hidden');
            uploadIcon.classList.add('hidden');
        }
        reader.readAsDataURL(file);
      }
    }

    // Fitur hapus gambar
    function removeImage() {
        // 1. Reset Input File (PENTING: Biar gak ke-upload)
        fileInput.value = '';
        
        // 2. Sembunyikan Preview
        previewContainer.classList.add('hidden');
        previewImg.src = ''; // Bersihin source gambar biar hemat memori

        // 3. Munculkan kembali Icon Upload
        uploadIcon.classList.remove('hidden');
        
        // 4. Pastikan styling border balik ke normal
        dropZone.classList.remove('border-indigo-500', 'bg-indigo-50');
    }
</script>

@endsection