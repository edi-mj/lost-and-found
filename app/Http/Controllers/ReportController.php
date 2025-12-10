<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ReportController extends Controller
{
    /**
     * Tampilkan Form Lapor Kehilangan
     */
    public function createLost()
    {
        return view('reports.create_lost');
    }

    /**
     * Tampilkan Form Lapor Ditemukan
     */
    public function createFound()
    {
        return view('reports.create_found');
    }

    /**
     * Handle Submit Laporan
     */
    public function store(Request $request)
    {
        // Validasi Input
        $request->validate([
            'title'       => 'required|string|max:100', // Judul singkat
            'description' => 'required|string|min:10',  // Deskripsi detail
            'location'    => 'required|string',         // Lokasi hilang
            'date_lost'   => 'required|date',           // Tanggal kejadian
            'photo'       => 'required|image|mimes:jpeg,png,jpg|max:2048', // Validasi file gambar
        ]);

        // Logic Upload File ke Storage Laravel (Sementara)
        // Nanti URL-nya yang kita kirim ke API Express sebagai 'photoUrl'
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('public/lost_items');
            // $photoUrl = asset(str_replace('public', 'storage', $path));
        }

        // DD data untuk memastikan inputan benar sebelum dikirim ke API Express
        dd([
            'type' => 'lost', // Hardcode karena ini form kehilangan
            'data_input' => $request->all(),
            'file_path' => $path ?? null
        ]);
    }

    /**
     * Tampilkan Semua Data Barang Hilang
     */
    public function indexLost(Request $request)
    {
        // Simulasi data dari API (Array of objects)
        // Nanti diganti: Http::get('.../api/reports?type=lost')->json();
        $dummyItems = [
            [
                'id' => 1,
                'title' => 'Dompet Kulit Coklat',
                'description' => 'Dompet merk Fossil, ada SIM C a.n. Budi. Jatuh sekitar kantin.',
                'location' => 'Kantin Fakultas Teknik',
                'date' => '2023-10-25',
                'image' => 'https://images.unsplash.com/photo-1627123424574-181ce5171c98?auto=format&fit=crop&q=80&w=300&h=200',
            ],
            [
                'id' => 2,
                'title' => 'iPhone 13 Pro Graphite',
                'description' => 'Casing bening, retak dikit di tempered glass pojok kiri.',
                'location' => 'Perpustakaan Lt. 2',
                'date' => '2023-10-24',
                'image' => 'https://images.unsplash.com/photo-1511385348-a52b4a160dc2?auto=format&fit=crop&q=80&w=300&h=200',
            ],
            [
                'id' => 3,
                'title' => 'Kunci Motor Honda Vario',
                'description' => 'Gantungan kunci boneka spongebob. Hilang pas parkir.',
                'location' => 'Parkiran Belakang',
                'date' => '2023-10-26',
                'image' => 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?auto=format&fit=crop&q=80&w=300&h=200', // Gambar ilustrasi kunci
            ],
            [
                'id' => 4,
                'title' => 'Tumbler Corkcicle Hitam',
                'description' => 'Ketinggalan di meja dosen pas bimbingan.',
                'location' => 'Ruang Dosen 101',
                'date' => '2023-10-26',
                'image' => 'https://images.unsplash.com/photo-1602143407151-011141920038?auto=format&fit=crop&q=80&w=300&h=200',
            ],
            [
                'id' => 4,
                'title' => 'Tumbler Corkcicle Hitam',
                'description' => 'Ketinggalan di meja dosen pas bimbingan.',
                'location' => 'Ruang Dosen 101',
                'date' => '2023-10-26',
                'image' => 'https://images.unsplash.com/photo-1602143407151-011141920038?auto=format&fit=crop&q=80&w=300&h=200',
            ],
            [
                'id' => 4,
                'title' => 'Tumbler Corkcicle Hitam',
                'description' => 'Ketinggalan di meja dosen pas bimbingan.',
                'location' => 'Ruang Dosen 101',
                'date' => '2023-10-26',
                'image' => 'https://images.unsplash.com/photo-1602143407151-011141920038?auto=format&fit=crop&q=80&w=300&h=200',
            ],
            [
                'id' => 4,
                'title' => 'Tumbler Corkcicle Hitam',
                'description' => 'Ketinggalan di meja dosen pas bimbingan.',
                'location' => 'Ruang Dosen 101',
                'date' => '2023-10-26',
                'image' => 'https://images.unsplash.com/photo-1602143407151-011141920038?auto=format&fit=crop&q=80&w=300&h=200',
            ],
            // ... bayangin ada banyak data lainnya ...
        ];


        // A. Bungkus Array jadi Collection
        $collection = collect($dummyItems);

        // B. Konfigurasi
        $perPage = 4; // Coba ganti jadi 4 biar cepet kelihatan halamannya
        $currentPage = $request->input('page', 1);

        // C. Potong Data (Slice)
        // Ambil data sesuai halaman yang diminta
        $currentPageItems = $collection->forPage($currentPage, $perPage)->values();

        // D. Bikin Object Paginator
        // Variabelnya kita namain $lostItems biar di View gak perlu ubah variabel
        $lostItems = new LengthAwarePaginator(
            $currentPageItems,
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => url()->current()]
        );

        return view('reports.index_lost', compact('lostItems'));
    }

    /**
     * Tampilkan Semua Data Barang DITEMUKAN
     */
    public function indexFound(Request $request)
    {
        // 1. Data Dummy (Ceritanya ini barang yang DITEMUKAN orang)
        $dummyItems = [
            [
                'id' => 101,
                'title' => 'KTP a.n. Siti Aminah',
                'description' => 'Ditemukan di dekat mesin fotokopi, domisili Jakarta Selatan.',
                'location' => 'Lobby Utama',
                'date' => '2023-10-27',
                'image' => 'https://images.unsplash.com/photo-1548126032-079a0fb0099f?auto=format&fit=crop&q=80&w=300&h=200', // Gambar ilustrasi ID Card
            ],
            [
                'id' => 102,
                'title' => 'Botol Minum Tupperware Ungu',
                'description' => 'Ada stiker nama "Rina" di tutupnya.',
                'location' => 'Kantin Sehat',
                'date' => '2023-10-26',
                'image' => 'https://images.unsplash.com/photo-1602143407151-011141920038?auto=format&fit=crop&q=80&w=300&h=200',
            ],
            [
                'id' => 103,
                'title' => 'Jaket Hoodie Hitam',
                'description' => 'Merk Uniqlo, size L, ketinggalan di kursi panjang.',
                'location' => 'Taman Kampus',
                'date' => '2023-10-25',
                'image' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&q=80&w=300&h=200',
            ],
            // ... Tambahin data dummy lain biar banyak ...
        ];

        // 2. Logika Pagination Manual (Sama persis kayak indexLost)
        $collection = collect($dummyItems);
        $perPage = 8; // Kita coba tampilin 8 per halaman
        $currentPage = $request->input('page', 1);
        $currentPageItems = $collection->forPage($currentPage, $perPage)->values();

        $foundItems = new LengthAwarePaginator(
            $currentPageItems,
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => url()->current()]
        );

        return view('reports.index_found', compact('foundItems'));
    }
}
