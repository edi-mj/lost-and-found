<nav class="bg-white border-b border-gray-200 fixed w-full z-30 top-0">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            {{-- Logo & Brand --}}
            <div class="flex">
                <div class="flex-shrink-0 flex items-center">
                    {{-- Ikon Search Sederhana sebagai Logo --}}
                    <div class="bg-indigo-600 p-2 rounded-lg mr-2">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <span class="font-bold text-xl text-gray-800">Lost & Found Hub</span>
                </div>
                
                {{-- Desktop Menu --}}
                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                    <a href="{{ route('dashboard') }}" class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Dashboard
                    </a>
                    <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Cari Barang
                    </a>
                    <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Riwayat Laporan
                    </a>
                    @if(session()->has('user') && session('user.role') === 'admin')
                        <a href="{{ route('dashboard-admin') }}"
                        class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Dashboard Admin
                        </a>
                    @endif
                </div>
            </div>

            {{-- Kanan: Notifikasi & Profile --}}
            <div class="hidden sm:ml-6 sm:flex sm:items-center space-x-4">
                
                {{-- Notifikasi Bell (Fitur API Notification) --}}
                <button class="bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none relative">
                    <span class="absolute top-0 right-0 block h-2 w-2 rounded-full ring-2 ring-white bg-red-500"></span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </button>

                {{-- User Dropdown --}}
                <div class="ml-3 relative flex items-center space-x-3">
                    <div class="flex items-center">
                        <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold mr-2">
                            {{ strtoupper(substr(session('user.name'), 0, 1)) }}
                          </div>
                          <span class="text-sm font-medium text-gray-700">Halo, {{ session('user.name')}}</span>
                    </div>

                    {{-- Separator Vertical --}}
                    <div class="h-6 w-px bg-gray-300"></div>

                    {{-- TOMBOL LOGOUT (FORM) --}}
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800 transition duration-150 ease-in-out flex items-center">
                            {{-- Ikon Logout --}}
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>