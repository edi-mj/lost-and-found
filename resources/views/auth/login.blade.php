@extends('layouts.app')

@section('title', 'Login Client')

@section('content')
{{-- Judul di atas form --}}
<div class="sm:mx-auto sm:w-full sm:max-w-md">
    <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
        Masuk ke Akun Anda
    </h2>
    
</div>

{{-- Card Form --}}
<div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
    <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
        
        <form action="{{ route('login') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Alert Error Global --}}
            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                {{ session('error') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Input Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">
                    Email Address
                </label>
                <div class="mt-1">
                    <input id="email" name="email" type="email" autocomplete="email" required
                        value="{{ old('email') }}"
                        class="appearance-none block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm
                        @error('email') border-red-500 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @else border-gray-300 @enderror">
                </div>
                {{-- Pesan Error Email --}}
                @error('email')
                    <p class="mt-2 text-sm text-red-600" id="email-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Input Password --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">
                    Password
                </label>
                <div class="mt-1">
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                        class="appearance-none block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm
                        @error('password') border-red-500 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @else border-gray-300 @enderror">
                </div>
                @error('password')
                    <p class="mt-2 text-sm text-red-600" id="password-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Checkbox Remember Me & Forgot Password
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                        Ingat saya
                    </label>
                </div>

                <div class="text-sm">
                    <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">
                        Lupa password?
                    </a>
                </div>
            </div> --}}

            {{-- Tombol Submit --}}
            <div>
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                    Masuk
                </button>
            </div>
        </form>
        <p class="mt-2 text-center text-sm text-gray-600">
        Belum punya akun?
        {{-- Link ke route register --}}
        <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
            Daftar akun baru
        </a>
    </p>
    </div>
</div>
@endsection