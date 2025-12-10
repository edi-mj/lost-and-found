@extends('layouts.app')

@section('title', 'Daftar Akun Baru')

@section('content')

{{-- Header --}}
<div class="sm:mx-auto sm:w-full sm:max-w-md">
    <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
        Buat Akun Baru
    </h2>
</div>

{{-- Card Form --}}
<div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
    <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
        
        <form action="{{ route('register.post') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Nama Lengkap --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                <div class="mt-1">
                    <input id="name" name="name" type="text" required value="{{ old('name') }}"
                        class="appearance-none block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm
                        @error('name') border-red-500 text-red-900 focus:ring-red-500 focus:border-red-500 @else border-gray-300 @enderror">
                </div>
                @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <div class="mt-1">
                    <input id="email" name="email" type="email" required value="{{ old('email') }}"
                        class="appearance-none block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm
                        @error('email') border-red-500 text-red-900 focus:ring-red-500 focus:border-red-500 @else border-gray-300 @enderror">
                </div>
                @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Contact Info --}}
            <div>
                <label for="contact_info" class="block text-sm font-medium text-gray-700">Nomor (HP/WhatsApp)</label>
                <div class="mt-1">
                    <input id="contact_info" name="contact_info" type="text" required value="{{ old('contact_info') }}"
                        class="appearance-none block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm
                        @error('contact_info') border-red-500 text-red-900 focus:ring-red-500 focus:border-red-500 @else border-gray-300 @enderror">
                </div>
                @error('contact_info') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <div class="mt-1">
                    <input id="password" name="password" type="password" required
                        class="appearance-none block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm
                        @error('password') border-red-500 text-red-900 focus:ring-red-500 focus:border-red-500 @else border-gray-300 @enderror">
                </div>
                @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Konfirmasi Password (Wajib ada karena validasi 'confirmed') --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                <div class="mt-1">
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
            </div>

            {{-- Tombol Submit --}}
            <div>
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                    Daftar Sekarang
                </button>
            </div>
        </form>

        <p class="mt-2 text-center text-sm text-gray-600">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                Masuk di sini
            </a>
        </p>

    </div>
</div>
@endsection