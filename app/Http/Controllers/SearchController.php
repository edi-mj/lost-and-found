<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $type    = $request->input('type');
        $status  = $request->input('status');

        $results = [];
        $total   = 0;
        $error   = null;

        if ($keyword) {
            try {
                // Panggil API FastAPI di Python
                $response = Http::get(config('services.backend_api.search_management'), [
                    'keyword' => $keyword,
                    'type'    => $type,
                    'status'  => $status,
                    // bisa tambahkan location kalau mau
                ]);

                if ($response->successful()) {
                    // Bentuk response dari FastAPI: { total: int, items: [...] }
                    $data    = $response->json();
                    $results = $data['items'] ?? [];
                    $total   = $data['total'] ?? count($results);
                } else {
                    // Kalau API Python balas 4xx / 5xx
                    $error = 'Gagal mengambil data dari layanan pencarian.';
                }
            } catch (\Exception $e) {
                // Kalau service Python mati / tidak bisa dihubungi
                $error = 'Search service tidak dapat dihubungi: ' . $e->getMessage();
            }
        }

        return view('search.index', [
            'keyword' => $keyword,
            'type'    => $type,
            'status'  => $status,
            'results' => $results,
            'total'   => $total,
            'error'   => $error,
        ]);
    }
}
