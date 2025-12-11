@extends('layouts.app')
@section('title','Daftar Barang Hilang')
@section('content')

@include('partials.navbar')

<div class="pt-24 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">

    <h3 class="text-2xl font-bold mb-4">Barang Hilang</h3>

    {{-- Grid daftar barang hilang --}}
    <div id="lost-list" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"></div>

</div>

<!-- Modal -->
<div id="report-modal"
     class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 overflow-auto">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-auto p-6 relative transform transition-transform duration-300 scale-95">
        <button id="modal-close"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
        <div id="modal-content" class="space-y-4 text-center">
            <p class="text-gray-500 text-sm">Memuat data...</p>
        </div>
    </div>
</div>

<script>
async function loadLost() {
    const token = "{{ session('api_token') }}";
    if(!token) return;

    try {
        const res = await fetch("{{ config('services.backend_api.reports') }}/reports/type/lost", {
            headers: { 'Authorization': 'Bearer ' + token }
        });
        const data = await res.json();

        const container = document.getElementById('lost-list');
        container.innerHTML = '';

        if(!data || data.length === 0){
            container.innerHTML = `<p class="text-gray-500 col-span-full text-center">Belum ada laporan barang hilang.</p>`;
            return;
        }

        data.forEach(item => {
            const div = document.createElement('div');
            div.className = "bg-white shadow-sm rounded-lg p-4 flex flex-col";

            const dateLost = item.date_lost ? new Date(item.date_lost).toLocaleDateString() : '-';

            div.innerHTML = `
                <img src="http://127.0.0.1:3000${item.photo_url || ''}" class="h-48 w-full object-cover mb-2 rounded-md">
                <h4 class="font-bold text-lg">${item.name || 'Nama Barang Tidak Diketahui'}</h4>
                <p class="text-sm text-gray-500 mt-1">${item.description}</p>
                <p class="mt-1 text-xs text-gray-400">${item.location || '-'} | ${dateLost}</p>
                <span class="inline-block mt-2 px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Kehilangan</span>
                <button data-report-id="${item.id}" class="detail-btn mt-3 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-sm font-medium">
                    Detail
                </button>
            `;
            container.appendChild(div);
        });

        // Event listener untuk tombol Detail
        const modal = document.getElementById('report-modal');
        const modalContent = document.getElementById('modal-content');
        const closeBtn = document.getElementById('modal-close');

        closeBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
            modalContent.innerHTML = '<p class="text-gray-500 text-sm">Memuat data...</p>';
        });

        document.querySelectorAll('.detail-btn').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                const id = btn.dataset.reportId;

                try {
                    const res = await fetch(`http://127.0.0.1:3000/reports/${id}`, {
                        headers: { 'Authorization': 'Bearer ' + token }
                    });
                    const report = await res.json();

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
                    modalContent.innerHTML = '<p class="text-red-500 text-center">Gagal memuat data.</p>';
                    modal.classList.remove('hidden');
                }
            });
        });

    } catch(err){
        console.error(err);
        const container = document.getElementById('lost-list');
        container.innerHTML = `<p class="text-red-500 text-center col-span-full">Gagal memuat data barang hilang.</p>`;
    }
}

document.addEventListener('DOMContentLoaded', loadLost);
</script>

@endsection
