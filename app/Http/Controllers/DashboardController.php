<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Simulasi data statistik (Nanti ambil dari API Report & Verification)
        $stats = [
            'total_lost' => 12,    // Barang Hilang
            'total_found' => 8,    // Barang Ditemukan
            'resolved' => 5,       // Kasus Selesai (Verified)
        ];

        // Simulasi data laporan terbaru (Nanti ini hasil JSON dari API Search/Report)
        $recentReports = [
            [
                'id' => 1,
                'type' => 'lost', // Tipe laporan
                'title' => 'Dompet Kulit Coklat',
                'location' => 'Kantin Fakultas Teknik',
                'date' => '2 Jam yang lalu',
                'status' => 'Mencari',
                'image' => 'https://images.unsplash.com/photo-1627123424574-181ce5171c98?auto=format&fit=crop&q=80&w=300&h=200'
            ],
            [
                'id' => 2,
                'type' => 'found',
                'title' => 'Kunci Motor Honda',
                'location' => 'Parkiran Belakang',
                'date' => '5 Jam yang lalu',
                'status' => 'Menunggu Verifikasi',
                'image' => 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?auto=format&fit=crop&q=80&w=300&h=200'
            ],
            [
                'id' => 3,
                'type' => 'lost',
                'title' => 'iPhone 13 Pro',
                'location' => 'Perpustakaan Lt. 2',
                'date' => '1 Hari yang lalu',
                'status' => 'Mencari',
                'image' => 'https://images.unsplash.com/photo-1511385348-a52b4a160dc2?auto=format&fit=crop&q=80&w=300&h=200'
            ],
        ];

        return view('dashboard', compact('stats', 'recentReports'));
    }
}
