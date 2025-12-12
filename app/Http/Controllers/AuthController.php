<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('services.backend_api.user_management');
    }

    public function showLoginForm()
    {
        if (session()->has('api_token')) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $response = Http::post("{$this->apiUrl}/auth/login", [
            'email'    => $request->email,
            'password' => $request->password,
        ]);

        if ($response->failed()) {
            return back()->with('error', 'Email atau password salah!')->withInput();
        }

        $data  = $response->json()['data'];
        $token = $data['token'];
        $user  = $data['user'];

        // Simpan session
        session([
            'api_token' => $token,
            'user'      => $user
        ]);


        // Cek role admin
        if ($user['role'] === 'admin') {
            return redirect()->route('dashboard-admin')
                ->with('success', 'Selamat datang Admin!');
        }

        // Jika bukan admin → ke dashboard biasa
        return redirect('/dashboard')->with('success', 'Login berhasil!');
    }


    public function dashboard()
    {
        if (!session()->has('api_token')) {
            return redirect()->route('login')->with('error', 'Anda belum login.');
        }

        return view('dashboard');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Sesuaikan dengan validasi Express.js
        $request->validate([
            'name'         => 'required',
            'email'        => 'required|email',
            'password'     => 'required|min:6',
            'contact_info' => 'nullable|numeric',
        ]);

        try {
            $response = Http::post("{$this->apiUrl}/auth/register", [
                'name'         => $request->name,
                'email'        => $request->email,
                'password'     => $request->password,
                'contact_info' => $request->contact_info,
            ]);

            // ✅ Berhasil
            if ($response->successful()) {
                return redirect()->route('login')
                    ->with('success', 'Registrasi berhasil! Silakan login.');
            }

            // ✅ Error validasi dari Express (422)
            if ($response->status() == 422) {
                $errors = $response->json()['errors'];
                $errorMessages = [];

                foreach ($errors as $err) {
                    $field = $err['path'] ?? 'error';
                    $message = $err['msg'] ?? 'Invalid data';
                    $errorMessages[$field][] = $message;
                }

                return back()->withErrors($errorMessages)->withInput();
            }

            // ✅ Email sudah terdaftar (400)
            if ($response->status() == 400) {
                return back()->withErrors([
                    'email' => $response->json()['message'] ?? 'Email sudah digunakan'
                ])->withInput();
            }

            // ✅ Error lain
            return back()->with('error', 'Gagal registrasi. Silakan coba lagi.')
                ->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'API tidak dapat dijangkau.')
                ->withInput();
        }
    }

    public function logout()
    {
        session()->forget(['api_token', 'user']);
        return redirect()->route('login');
    }
}
