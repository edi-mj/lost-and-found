@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

{{-- Include Navbar --}}
@include('partials.navbar')
<div class="min-h-screen bg-gray-100 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Dashboard Admin</h1>
            <p class="text-gray-500 mt-1">
                Kelola verifikasi laporan barang ditemukan
            </p>
        </div>

        {{-- Statistik --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-sm font-medium text-gray-500">Total Laporan</h2>
                <p class="mt-3 text-4xl font-bold text-gray-800">{{ count($verifications) }}</p>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-sm font-medium text-gray-500">Menunggu Verifikasi</h2>
                <p class="mt-3 text-4xl font-bold text-yellow-500">
                    {{ collect($verifications)->where('status','pending')->count() }}
                </p>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-sm font-medium text-gray-500">Sudah Diverifikasi</h2>
                <p class="mt-3 text-4xl font-bold text-emerald-600">
                    {{ collect($verifications)->whereIn('status',['approved','rejected'])->count() }}
                </p>
            </div>
        </div>

        {{-- Tabel Laporan --}}
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-800">
                    Daftar Laporan Menunggu Verifikasi
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">#</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Report ID</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Claimant ID</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Proof</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($verifications as $index => $verification)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">{{ $index+1 }}</td>
                                <td class="px-6 py-4">{{ $verification['report_id'] }}</td>
                                <td class="px-6 py-4">{{ $verification['claimant_id'] }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ $verification['proof'] }}" target="_blank" class="text-blue-600 hover:underline">
                                        Lihat Bukti
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    @if($verification['status'] === 'pending')
                                        <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full">Pending</span>
                                    @elseif($verification['status'] === 'approved')
                                        <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">Approved</span>
                                    @else
                                        <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded-full">Rejected</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 space-x-2">
                                    @if($verification['status'] === 'pending')
                                        <form action="{{ route('verifications.update', $verification['id']) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="approved">
                                            <button class="px-3 py-1.5 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                                                Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('verifications.update', $verification['id']) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="rejected">
                                            <button class="px-3 py-1.5 bg-red-600 text-white text-xs rounded hover:bg-red-700">
                                                Reject
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    Tidak ada laporan verifikasi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
