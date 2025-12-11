<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        // Kalau belum login via API, redirect ke login
        if (!session('api_token')) {
            return redirect()->route('login');
        }

        // =============================================
        // GANTI BAGIAN INI: Kirim stats & recentReports ke view
        // =============================================
        $stats = [
            'total_lost'  => 12,   // nanti bisa diganti fetch dari API
            'total_found' => 8,
            'resolved'    => 5,
        ];

        $recentReports = [
            [
                'title'    => 'Dompet Kulit Coklat',
                'location' => 'Kantin Fakultas Teknik',
                'date'     => '2 Jam yang lalu',
                'image'    => 'https://images.unsplash.com/photo-1627123424574-181ce5171c98?auto=format&fit=crop&q=80&w=300&h=200',
                'type'     => 'lost',
            ],
            [
                'title'    => 'Kunci Motor Honda',
                'location' => 'Parkiran Belakang',
                'date'     => '5 Jam yang lalu',
                'image'    => 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?auto=format&fit=crop&q=80&w=300&h=200',
                'type'     => 'found',
            ],
            [
                'title'    => 'iPhone 13 Pro',
                'location' => 'Perpustakaan Lt. 2',
                'date'     => '1 Hari yang lalu',
                'image'    => 'https://images.unsplash.com/photo-1511385348-a52b4a160dc2?auto=format&fit=crop&q=80&w=300&h=200',
                'type'     => 'lost',
            ],
            // tambah lagi kalau mau sampai 6 card
        ];

        return view('dashboard', compact('stats', 'recentReports'));
        //               JANGAN LUPA TAMBAH INI! (sebelumnya cuma 'user')
    }}