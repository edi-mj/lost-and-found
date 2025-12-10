@extends('layouts.app')

@section('title', 'Daftar Barang Hilang')

@section('content')

@include('partials.navbar')

<div class="pt-24 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">

    {{-- Header Section --}}
    <div class="border-b border-gray-200 pb-5 sm:flex sm:items-center sm:justify-between">
        
        {{-- KIRI: Judul & Tombol Balik --}}
        <div class="flex flex-col">
            {{-- Tombol Kembali (Kecil di atas judul) --}}
            {{-- Pastikan route 'dashboard' sudah ada di web.php kamu --}}
            <a href="{{ route('dashboard') }}" class="mb-2 inline-flex items-center text-sm text-gray-500 hover:text-gray-700 transition-colors">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Dashboard
            </a>

            <h3 class="text-2xl leading-6 font-bold text-gray-900">
                Daftar Kehilangan
            </h3>
        </div>

        {{-- KANAN: Tombol Tambah --}}
        <div class="mt-3 sm:mt-0 sm:ml-4">
            <a href="{{ route('report.lost') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                Tambah Laporan Baru
            </a>
        </div>
    </div>

    {{-- Filter & Search Bar (Masih Mockup UI) --}}
    <div class="mt-6 flex flex-col sm:flex-row justify-between items-center gap-4 bg-white p-4 rounded-lg shadow-sm border border-gray-100">
        {{-- Search Input --}}
        <div class="relative w-full sm:w-96">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-2" placeholder="Cari nama barang atau lokasi...">
        </div>

        {{-- Filter Dropdown --}}
        <div class="w-full sm:w-auto flex items-center gap-2">
            <span class="text-sm text-gray-500">Urutkan:</span>
            <select class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                <option>Terbaru</option>
                <option>Terlama</option>
                <option>Lokasi (A-Z)</option>
            </select>
        </div>
    </div>

    {{-- Items Grid --}}
    <div class="mt-8 grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        
        @foreach($lostItems as $item)
        <div class="group bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden border border-gray-100 flex flex-col h-full">
            
            {{-- Image --}}
            <div class="relative h-48 w-full bg-gray-200 overflow-hidden">
                <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300">
                {{-- Badge Date --}}
                <div class="absolute top-2 left-2 bg-black bg-opacity-60 text-white text-xs px-2 py-1 rounded">
                    {{ \Carbon\Carbon::parse($item['date'])->format('d M Y') }}
                </div>
            </div>

            {{-- Content --}}
            <div class="p-4 flex-1 flex flex-col">
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">
                        {{ $item['title'] }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 line-clamp-2">
                        {{ $item['description'] }}
                    </p>
                    
                    {{-- Location --}}
                    <div class="mt-3 flex items-center text-sm text-gray-500">
                        <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $item['location'] }}
                    </div>
                </div>

                {{-- Action Button --}}
                <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        Hilang
                    </span>
                    <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                        Lihat Detail &rarr;
                    </a>
                </div>
            </div>
        </div>
        @endforeach

    </div>

    {{-- Pagination --}}
    <div class="mt-10 px-4 py-3 border-t border-gray-200">
        {{ $lostItems->withQueryString()->links() }}
    </div>

</div>
@endsection