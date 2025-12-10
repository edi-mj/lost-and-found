<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
