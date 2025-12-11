@extends('layouts.app')

@section('title', 'Cari Barang / Laporan')

@section('content')

{{-- Include Navbar --}}
@include('partials.navbar')

<div class="pt-20 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">

    {{-- Header Section --}}
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Pencarian Barang
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Cari laporan barang hilang atau ditemukan yang sesuai dengan kata kunci dan filter yang kamu butuhkan.
            </p>
        </div>
    </div>

    {{-- Search Bar + Filter (Fitur API Search) --}}
    <form action="{{ route('search.index') }}" method="GET" class="mb-8 bg-white shadow rounded-lg p-4 sm:p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
            {{-- Keyword --}}
            <div class="md:col-span-2">
                <label for="keyword" class="block text-sm font-medium text-gray-700 mb-1">
                    Kata Kunci
                </label>
                <div class="relative">
                    <input
                        type="text"
                        id="keyword"
                        name="keyword"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md py-2.5 pl-3 pr-10"
                        placeholder="Contoh: Kunci motor, Dompet, Laptop..."
                        value="{{ old('keyword', $keyword) }}"
                        required
                    >
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Filter Tipe --}}
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">
                    Tipe Laporan
                </label>
                <select
                    id="type"
                    name="type"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-sm border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-md"
                >
                    <option value="">Semua Tipe</option>
                    <option value="lost" {{ ($type ?? '') === 'lost' ? 'selected' : '' }}>Kehilangan</option>
                    <option value="found" {{ ($type ?? '') === 'found' ? 'selected' : '' }}>Penemuan</option>
                </select>
            </div>

            {{-- Filter Status --}}
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                    Status
                </label>
                <select
                    id="status"
                    name="status"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-sm border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-md"
                >
                    <option value="">Semua Status</option>
                    <option value="open" {{ ($status ?? '') === 'open' ? 'selected' : '' }}>Open</option>
                    <option value="claimed" {{ ($status ?? '') === 'claimed' ? 'selected' : '' }}>Claimed</option>
                    <option value="closed" {{ ($status ?? '') === 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
        </div>

        <div class="mt-4 flex justify-between items-center flex-col sm:flex-row gap-3">
            @if($keyword)
                <p class="text-sm text-gray-500">
                    Menampilkan <span class="font-semibold text-gray-900">{{ $total }}</span> hasil untuk
                    <span class="font-semibold text-indigo-600">"{{ $keyword }}"</span>
                    {{ $type ? ' • ' . strtoupper($type) : '' }}
                    {{ $status ? ' • ' . strtoupper($status) : '' }}
                </p>
            @else
                <p class="text-sm text-gray-500">
                    Masukkan kata kunci lalu tekan tombol cari untuk menampilkan laporan.
                </p>
            @endif

            <div class="flex gap-3">
                @if($keyword)
                    <a href="{{ route('search.index') }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Reset
                    </a>
                @endif
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cari
                </button>
            </div>
        </div>
    </form>

    {{-- Error jika API Python bermasalah --}}
    @if($error)
        <div class="mb-6">
            <div class="rounded-md bg-red-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M12 4v1m0 14v1m8-9h-1M5 12H4m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            Terjadi kesalahan saat menghubungi layanan pencarian
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p>{{ $error }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Hasil Pencarian --}}
    @if($keyword && !$error)
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
            Hasil Pencarian
        </h3>

        @if($total > 0)
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($results as $report)
                    <div class="bg-white overflow-hidden shadow rounded-lg flex flex-col">
                        {{-- Jika suatu saat ada photo_url dari API Python --}}
                        @if(!empty($report['photo_url']))
                            <div class="relative h-40 w-full">
                                <img class="h-full w-full object-cover"
                                     src="{{ $report['photo_url'] }}"
                                     alt="Foto laporan #{{ $report['id'] }}">
                                {{-- Badge tipe --}}
                                <span class="absolute top-2 left-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $report['type'] === 'lost' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $report['type'] === 'lost' ? 'Kehilangan' : 'Penemuan' }}
                                </span>
                                {{-- Badge status --}}
                                <span class="absolute top-2 right-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-800 text-white">
                                    {{ strtoupper($report['status']) }}
                                </span>
                            </div>
                        @else
                            <div class="px-4 pt-4 flex justify-between items-start">
                                <span class="inline-flex text-xs leading-5 font-semibold rounded-full {{ $report['type'] === 'lost' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $report['type'] === 'lost' ? 'Kehilangan' : 'Penemuan' }}
                                </span>
                                <span class="inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-700">
                                    {{ strtoupper($report['status']) }}
                                </span>
                            </div>
                        @endif

                        <div class="px-4 pb-4 pt-3 flex-1 flex flex-col">
                            {{-- Deskripsi / judul singkat --}}
                            <p class="text-sm font-semibold text-gray-900 line-clamp-2">
                                {{ $report['description'] }}
                            </p>

                            {{-- Lokasi --}}
                            @if(!empty($report['location']))
                                <p class="text-sm text-gray-500 mt-2 flex items-center">
                                    <svg class="mr-1.5 h-4 w-4 flex-shrink-0 text-gray-400" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $report['location'] }}
                                </p>
                            @endif

                            {{-- Date & Detail --}}
                            <div class="mt-4 flex justify-between items-center">
                                <span class="text-xs text-gray-500">
                                    {{ $report['created_at'] ?? '-' }}
                                </span>
                                <a href="#"
                                   class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                    Detail &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white shadow rounded-lg p-6">
                <p class="text-sm text-gray-500">
                    Tidak ada laporan yang cocok dengan kata kunci
                    <span class="font-semibold text-gray-900">"{{ $keyword }}"</span>.
                    Coba gunakan kata kunci lain atau kurangi filter.
                </p>
            </div>
        @endif
    @endif
</div>
@endsection
