<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('services.backend_api.user_management');
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
            'email' => 'required|email',
            'password' => 'required'
        ]);

        try {
            $response = Http::post($this->apiBaseUrl . '/auth/login', [
                'email' => $request->email,
                'password' => $request->password,
            ]);

            if ($response->successful()) {
                $data = $response->json()['data'];

                session([
                    'api_token' => $data['token'],
                    'user' => $data['user'],
                ]);

                return redirect()->route('dashboard');
            }
            return back()->with('error', 'Username atau Password salah!')->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan pada server API atau tidak terjangkau.')->withInput();
        }
    }

    public function dashboard()
    {
        if (!session()->has('api_token')) {
            return redirect()->route('login')->with('error', 'Anda belum login.');
        }

        return view('dashboard');
    }

    /**
     * Tampilkan form register
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Handle submit register
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'contact_info' => 'required',
            'password' => 'required',
        ]);

        try {
            $response = Http::post($this->apiBaseUrl . '/auth/register', [
                'name' => $request->name,
                'email' => $request->email,
                'contact_info' => $request->contact_info,
                'password' => $request->password,
            ]);

            if ($response->successful()) {
                return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
            }

            if ($response->status() == 422) {
                $errors = $response->json()['errors'];
                $errorMessages = [];
                foreach ($errors as $err) {
                    $field = $err['path'] ?? 'error';
                    $message = $err['msg'] ?? 'Invalid data';

                    $errorMessages[$field][] = $message;
                }
                return back()->withErrors($errorMessages)->withInput();
            } elseif ($response->status() == 400) {
                $message = $response->json()['message'] ?? 'Invalid data';
                return back()->withErrors(['email' => $message])->withInput();
            }
            return back()->with('error', 'Gagal registrasi. Silakan coba lagi.')->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan pada server API atau tidak terjangkau.')->withInput();
        }
    }

    public function logout()
    {
        session()->forget(['api_token', 'user']);
        return redirect()->route('login');
    }
}
