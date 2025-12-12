<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class ReportController extends Controller
{
    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.backend_api.reports');
    }

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
     * Submit Laporan Kehilangan / Penemuan
     */
    public function store(Request $request, $type)
    {
        $request->validate([
            'name'        => 'required|string|min:3',
            'description' => 'required|string|min:10',
            'location'    => 'nullable|string',
            'photo'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $token = $request->session()->get('api_token');
        if (!$token) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $payload = [
            'user_id'     => $request->session()->get('user_id'),
            'type'        => $type,
            'name'        => $request->input('name'),
            'description' => $request->input('description'),
            'location'    => $request->input('location'),
        ];

        $photo = $request->file('photo');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->attach(
            'photo',
            $photo ? file_get_contents($photo->getRealPath()) : null,
            $photo ? $photo->getClientOriginalName() : null
        )->post($this->baseUrl . '/reports', $payload);

        if ($response->successful()) {
            return redirect()->route($type === 'lost' ? 'reports.lost.index' : 'reports.found.index')
                ->with('success', 'Laporan berhasil dikirim!');
        } else {
            return back()->with('error', $response->json()['error'] ?? 'Gagal mengirim laporan');
        }
    }

    /**
     * Tampilkan Semua Data Barang Hilang
     */
    public function indexLost(Request $request)
    {
        return $this->indexByType($request, 'lost');
    }

    /**
     * Tampilkan Semua Data Barang Ditemukan
     */
    public function indexFound(Request $request)
    {
        return $this->indexByType($request, 'found');
    }

    /**
     * Helper: Ambil data dari backend berdasarkan type & pagination
     */
    private function indexByType(Request $request, $type)
    {
        $token = $request->session()->get('api_token');
        if (!$token) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $page = $request->input('page', 1);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token
            ])->get($this->baseUrl . "/type/{$type}");

            $items = $response->successful() ? $response->json() : [];

            // Pagination manual
            $perPage = 8;
            $collection = collect($items);
            $currentPageItems = $collection->forPage($page, $perPage)->values();

            $paginator = new LengthAwarePaginator(
                $currentPageItems,
                $collection->count(),
                $perPage,
                $page,
                ['path' => url()->current()]
            );

            return view(
                $type === 'lost' ? 'reports.index_lost' : 'reports.index_found',
                [$type === 'lost' ? 'lostItems' : 'foundItems' => $paginator]
            );
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal load data dari backend: ' . $e->getMessage());
        }
    }

    /**
     * Detail Laporan
     */
    public function show(Request $request, $id)
    {
        $token = $request->session()->get('api_token');
        if (!$token) return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->get(config('services.backend_api.reports') . "/{$id}");

        if (!$response->successful()) {
            if ($request->ajax()) return response('<p class="text-red-500">Data tidak ditemukan</p>', 404);
            return back()->with('error', 'Data tidak ditemukan');
        }

        $report = $response->json();

        if ($request->ajax()) {
            return view('reports.partials.show_modal', compact('report'))->render();
        }

        return view('reports.show', compact('report'));
    }
}
