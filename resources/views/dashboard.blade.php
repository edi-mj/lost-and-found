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

            {{-- Quick Actions --}}
            <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
                <a href="{{ route('reports.lost.create') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                    Lapor Hilang
                </a>
                <a href="{{ route('reports.found.create') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    Lapor Ditemukan
                </a>
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-3 mb-10">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <dt class="text-sm font-medium text-gray-500 truncate">Total Barang Hilang</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['total_lost'] }}</dd>
                </div>
                <div class="bg-gray-50 px-4 py-4 sm:px-6">
                    <a href="{{ route('reports.lost.index') }}"
                        class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                        Lihat semua data &rarr;
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <dt class="text-sm font-medium text-gray-500 truncate">Barang Ditemukan</dt>
                    <dd class="mt-1 text-3xl font-semibold text-green-600">{{ $stats['total_found'] }}</dd>
                </div>
                <div class="bg-gray-50 px-4 py-4 sm:px-6">
                    <a href="{{ route('reports.found.index') }}"
                        class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                        Cek kecocokan &rarr;
                    </a>
                </div>
            </div>

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

        {{-- Recent Reports --}}
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Laporan Terbaru</h3>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($recentReports as $report)
                <div class="bg-white overflow-hidden shadow rounded-lg flex flex-col">
                    <div class="relative h-48 w-full">
                        <img class="h-full w-full object-cover" src="{{ $report['image'] }}" alt="{{ $report['title'] }}">
                        <span
                            class="absolute top-2 right-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $report['type'] === 'lost' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                            {{ $report['type'] === 'lost' ? 'Kehilangan' : 'Ditemukan' }}
                        </span>
                    </div>
                    <div class="px-4 py-4 flex-1">
                        <h4 class="text-lg font-bold text-gray-900">{{ $report['title'] }}</h4>
                        <p class="text-sm text-gray-500 mt-1">{{ $report['location'] }}</p>
                        <div class="mt-4 flex justify-between items-center">
                            <span class="text-xs text-gray-500">{{ $report['date'] }}</span>
                            <a href="{{ route('reports.show', $report['id']) }}" data-report-id="{{ $report['id'] }}"
                                class="text-sm font-medium text-indigo-600 hover:text-indigo-500 detail-btn">Detail
                                &rarr;</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    <!-- Modal -->
    <!-- Modal -->
    <div id="report-modal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 overflow-auto">
        <div
            class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-auto p-6 relative transform transition-transform duration-300 scale-95">
            <!-- Close Button -->
            <button id="modal-close"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>

            <!-- Modal Content -->
            <div id="modal-content" class="space-y-4 text-center">
                <p class="text-gray-500 text-sm">Memuat data...</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('report-modal');
            const modalContent = document.getElementById('modal-content');
            const closeBtn = document.getElementById('modal-close');

            // Close modal
            closeBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
                modalContent.innerHTML = '<p class="text-gray-500 text-sm">Memuat data...</p>';
            });

            // Open modal detail
            document.querySelectorAll('.detail-btn').forEach(btn => {
                btn.addEventListener('click', async (e) => {
                    e.preventDefault();
                    const id = btn.dataset.reportId;
                    const token = "{{ session('api_token') }}";

                    try {
                        const res = await fetch(`http://127.0.0.1:3000/reports/${id}`, {
                            headers: {
                                'Authorization': 'Bearer ' + token
                            }
                        });
                        const report = await res.json();

                        // Render modal content dengan styling lebih rapi
                        modalContent.innerHTML = `
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">${report.name || 'Tidak ada nama'}</h3>
                    <img src="http://127.0.0.1:3000${report.photo_url || ''}" class="w-full h-64 object-cover rounded-lg mb-4">
                    <p class="text-gray-700"><strong>Deskripsi:</strong> ${report.description || '-'}</p>
                    <p class="text-gray-700"><strong>Lokasi:</strong> ${report.location || '-'}</p>
                    <p class="text-gray-700"><strong>Status:</strong> 
                        <span class="${report.status === 'open' ? 'text-red-600 font-semibold' : 'text-green-600 font-semibold'}">
                            ${report.status ? report.status.charAt(0).toUpperCase() + report.status.slice(1) : '-'}
                        </span>
                    </p>
                    <p class="text-gray-500 text-sm"><strong>Tanggal:</strong> ${new Date(report.created_at).toLocaleString()}</p>
                `;

                        modal.classList.remove('hidden');

                    } catch (err) {
                        console.error(err);
                        modalContent.innerHTML =
                            '<p class="text-red-500 text-center">Gagal memuat data.</p>';
                        modal.classList.remove('hidden');
                    }
                });
            });
        });
    </script>


@endsection
