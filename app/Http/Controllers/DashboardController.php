<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Ambil token JWT dari session Laravel (setelah login)
        $token = $request->session()->get('api_token');
        if (!$token) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Base URL backend
        $baseUrl = config('services.backend_api.reports'); // mengambil dari services.php

        try {
            // Ambil data barang hilang
            $lostResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token
            ])->get("{$baseUrl}/reports/type/lost");

            // Ambil data barang ditemukan
            $foundResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token
            ])->get("{$baseUrl}/reports/type/found");

            $lostReports = $lostResponse->successful() ? $lostResponse->json() : [];
            $foundReports = $foundResponse->successful() ? $foundResponse->json() : [];

            // Hitung statistik
            $stats = [
                'total_lost' => count($lostReports),
                'total_found' => count($foundReports),
                'resolved' => count(array_filter(array_merge($lostReports, $foundReports), fn($r) => in_array($r['status'], ['claimed', 'closed']))),
            ];

            // Gabungkan laporan lost & found
            $allReports = array_merge($lostReports, $foundReports);

            // Map data untuk Blade
            $recentReports = array_map(function ($r,) use ($baseUrl) {
                return [
                    'id' => $r['id'],
                    'type' => $r['type'],
                    'name' => $r['name'] ?? 'Nama Barang Tidak Diketahui', // tambahkan ini
                    'title' => substr($r['description'], 0, 30), // preview title
                    'description' => $r['description'],
                    'location' => $r['location'] ?? '-',
                    'date' => date('d M Y', strtotime($r['created_at'])),
                    'status' => $r['status'],
                    'image' => $r['photo_url'] ? $baseUrl . $r['photo_url'] : 'https://via.placeholder.com/300',
                ];
            }, $allReports);

            // Urutkan berdasarkan tanggal terbaru
            usort($recentReports, fn($a, $b) => strtotime($b['date']) <=> strtotime($a['date']));

            // Ambil 6 laporan terbaru
            $recentReports = array_slice($recentReports, 0, 6);

            return view('dashboard', compact('stats', 'recentReports', 'token'));
        } catch (\Exception $e) {
            return view('dashboard', [
                'stats' => ['total_lost' => 0, 'total_found' => 0, 'resolved' => 0],
                'recentReports' => [],
                'token' => $token
            ])->with('error', 'Gagal load data dashboard: ' . $e->getMessage());
        }
    }
}
