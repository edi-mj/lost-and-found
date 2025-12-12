<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VerificationController extends Controller
{
    private $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('services.backend_api.verification_management');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $token = session('api_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        try {
            $response = Http::withToken($token)->put("{$this->apiUrl}/{$id}", [
                'status' => $request->status,
            ]);

            if ($response->failed()) {
                // Debug error dari Verification API
                // dd($response->status(), $response->body());
                return back()->with('error', 'Gagal mengupdate status verifikasi.')->withInput();
            }

            return back()->with('success', 'Status verifikasi berhasil diupdate.');
        } catch (\Exception $e) {
            // Sementara tampilkan pesan supaya kelihatan
            // dd($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghubungi Verification API.');
        }
    }
}
