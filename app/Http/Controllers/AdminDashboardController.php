<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class AdminDashboardController extends Controller
{
    private $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('services.backend_api.verification_management');
    }

    public function index()
    {
        $token = session('api_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Silakan login sebagai admin.');
        }

        // dd($token);

        $response = Http::withToken($token)->get($this->apiUrl);



        if (!$response->successful()) {
            return back()->with('error', 'Gagal mengambil data verifikasi dari API.');
        }

        $verifications = $response->json(); // array of verifications

        // Biar enak, kita pisah berdasarkan status
        $pendingVerifications   = collect($verifications)->where('status', 'pending');
        $approvedVerifications  = collect($verifications)->where('status', 'approved');
        $rejectedVerifications  = collect($verifications)->where('status', 'rejected');

        return view('admin.dashboard', [
            'verifications'        => $verifications,
            'pendingVerifications' => $pendingVerifications,
            'approvedVerifications' => $approvedVerifications,
            'rejectedVerifications' => $rejectedVerifications,
        ]);
    }
}
