@extends('layouts.app')

@section('title', 'Dashboard Komunitas')

@section('content')

{{-- Include Navbar --}}
@include('partials.navbar')

<div class="pt-20 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    
    {{-- Header Section --}}
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Dashboard Komunitas
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Pantau barang hilang dan temukan barang di sekitarmu.
            </p>
        </div>
        {{-- Quick Actions: Sesuai deskripsi fitur inti project --}}
        <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">

            {{-- Tombol lapor kehilangan --}}
            <a href="{{ route('report.lost') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                Lapor Hilang
            </a>

            {{-- Tombol lapor penemuan --}}
            <a href="{{ route('report.found') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Lapor Ditemukan
            </a>

        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3 mb-10">
        {{-- Card 1: Barang Hilang --}}
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Total Barang Hilang</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['total_lost'] }}</dd>
            </div>
            <div class="bg-gray-50 px-4 py-4 sm:px-6">
                <div class="text-sm">
                    <a href="{{ route('reports.lost.index') }}" class="font-medium text-indigo-600 hover:text-indigo-500">Lihat semua data <span aria-hidden="true">&rarr;</span></a>
                </div>
            </div>
        </div>

        {{-- Card 2: Barang Ditemukan --}}
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Barang Ditemukan</dt>
                <dd class="mt-1 text-3xl font-semibold text-green-600">{{ $stats['total_found'] }}</dd>
            </div>
            <div class="bg-gray-50 px-4 py-4 sm:px-6">
                <div class="text-sm">
                    <a href="{{ route('report.found-list') }}" class="font-medium text-indigo-600 hover:text-indigo-500">Cek kecocokan <span aria-hidden="true">&rarr;</span></a>
                </div>
            </div>
        </div>

        {{-- Card 3: Berhasil Kembali (Verified) --}}
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Kasus Selesai</dt>
                <dd class="mt-1 text-3xl font-semibold text-indigo-600">{{ $stats['resolved'] }}</dd>
            </div>
            <div class="bg-gray-50 px-4 py-4 sm:px-6">
                <div class="text-sm text-gray-500">Terverifikasi oleh Admin</div>
            </div>
        </div>
    </div>

    {{-- Search Bar (Fitur API Search) --}}
    <div class="mb-8 relative">
        <input type="text" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md py-3 px-4" placeholder="Cari barang hilang (contoh: Kunci motor, Dompet, Laptop)...">
        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
    </div>

    {{-- Recent Reports List --}}
    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Laporan Terbaru</h3>
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        
        @foreach($recentReports as $report)
        <div class="bg-white overflow-hidden shadow rounded-lg flex flex-col">
            <div class="relative h-48 w-full">
                <img class="h-full w-full object-cover" src="{{ $report['image'] }}" alt="{{ $report['title'] }}">
                {{-- Badge Status --}}
                <span class="absolute top-2 right-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $report['type'] == 'lost' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                    {{ ucfirst($report['type'] == 'lost' ? 'Kehilangan' : 'Ditemukan') }}
                </span>
            </div>
            <div class="px-4 py-4 flex-1">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="text-lg font-bold text-gray-900">{{ $report['title'] }}</h4>
                        <p class="text-sm text-gray-500 mt-1 flex items-center">
                            <svg class="mr-1.5 h-4 w-4 flex-shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $report['location'] }}
                        </p>
                    </div>
                </div>
                <div class="mt-4 flex justify-between items-center">
                    <span class="text-xs text-gray-500">{{ $report['date'] }}</span>
                    <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Detail &rarr;</a>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>
@endsection