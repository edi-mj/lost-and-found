<nav class="bg-white border-b border-gray-200 fixed w-full z-30 top-0">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- Logo & Brand --}}
            <div class="flex">
                <div class="flex-shrink-0 flex items-center">
                    {{-- Ikon Search Sederhana sebagai Logo --}}
                    <div class="bg-indigo-600 p-2 rounded-lg mr-2">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <span class="font-bold text-xl text-gray-800">Lost & Found Hub</span>
                </div>

                {{-- Desktop Menu --}}
                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                    <a href="{{ route('dashboard') }}"
                        class="{{ request()->routeIs('dashboard')
    ? 'border-indigo-500 text-gray-900'
    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Dashboard
                    </a>
                    <a href="{{ route('search.index') }}"
                        class="{{ request()->routeIs('search.index')
    ? 'border-indigo-500 text-gray-900'
    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Cari Barang
                    </a>
                    <a href="#"
                        class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
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
                <div class="relative" id="notification-container">
                    <button id="notification-button" onclick="toggleNotifications()"
                        class="bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none relative">
                        <span id="notification-badge"
                            class="absolute top-0 right-0 block hidden flex items-center justify-center text-xs text-white font-bold px-1 h-5 min-w-[1.25rem] rounded-full ring-2 ring-white bg-red-500"></span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </button>

                    {{-- Dropdown Panel --}}
                    <div id="notification-dropdown"
                        class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden focus:outline-none z-50">
                        <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                            <div class="px-4 py-2 text-sm text-gray-700 font-bold border-b">
                                Notifikasi
                            </div>
                            <div id="notification-list" class="max-h-60 overflow-y-auto">
                                <div class="px-4 py-3 text-sm text-gray-400 text-center">Loading...</div>
                            </div>
                            <a href="{{ route('notifications.index') }}"
                                class="block px-4 py-2 text-sm text-center text-indigo-600 font-bold hover:bg-gray-100 border-t">
                                Lihat Semua
                            </a>
                        </div>
                    </div>
                </div>

                <script>
                    function toggleNotifications() {
                        const dropdown = document.getElementById('notification-dropdown');
                        dropdown.classList.toggle('hidden');

                        // Always re-fresh when opening
                        if (!dropdown.classList.contains('hidden')) {
                            fetchNotifications();
                        }
                    }

                    function fetchNotifications() {
                        const list = document.getElementById('notification-list');
                        // Only show loading if list is empty or appears to be loading
                        if (list.children.length === 0 || list.innerText.includes('Loading')) {
                            list.innerHTML = '<div class="px-4 py-3 text-sm text-gray-400 text-center">Loading...</div>';
                        }

                        fetch('{{ route("notifications.recent") }}')
                            .then(response => response.json())
                            .then(data => {
                                // data structure: { recent: [...], unread_count: 5 }

                                // Update Badge
                                const badge = document.getElementById('notification-badge');
                                if (data.unread_count > 0) {
                                    badge.classList.remove('hidden');
                                    badge.innerText = data.unread_count;
                                } else {
                                    badge.classList.add('hidden');
                                }

                                // Update List (Recent 5)
                                list.innerHTML = '';
                                if (data.recent.length === 0) {
                                    list.innerHTML = '<div class="px-4 py-3 text-sm text-gray-500 text-center">Tidak ada notifikasi baru</div>';
                                } else {
                                    data.recent.forEach(notif => {
                                        const item = document.createElement('a');
                                        item.href = '{{ route("notifications.index") }}'; // Go to full list for details
                                        // Highlight unread
                                        const bgClass = notif.is_read ? 'bg-white' : 'bg-blue-50';
                                        item.className = `block px-4 py-3 border-b hover:bg-gray-100 ${bgClass}`;

                                        const title = document.createElement('p');
                                        title.className = `text-sm font-medium ${notif.is_read ? 'text-gray-900' : 'text-blue-600'}`;
                                        title.innerText = notif.title || 'Notifikasi';

                                        const body = document.createElement('p');
                                        body.className = 'text-sm text-gray-500 truncate';
                                        body.innerText = notif.message || 'Pesan baru';

                                        item.appendChild(title);
                                        item.appendChild(body);
                                        list.appendChild(item);
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                list.innerHTML = '<div class="px-4 py-3 text-sm text-red-500 text-center">Gagal memuat notifikasi</div>';
                            });
                    }

                    // Fetch on load to show badge immediately
                    document.addEventListener('DOMContentLoaded', function () {
                        fetchNotifications();
                        setInterval(fetchNotifications, 1000);
                    });

                    // Close dropdown when clicking outside
                    window.addEventListener('click', function (e) {
                        const container = document.getElementById('notification-container');
                        if (container && !container.contains(e.target)) {
                            document.getElementById('notification-dropdown').classList.add('hidden');
                        }
                    });
                </script>

                {{-- User Dropdown --}}
                <div class="ml-3 relative flex items-center space-x-3">
                    <div class="flex items-center">
                        <div
                            class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold mr-2">
                            {{ strtoupper(substr(session('user.name'), 0, 1)) }}
                        </div>
                        <span class="text-sm font-medium text-gray-700">Halo, {{ session('user.name')}}</span>
                    </div>

                    {{-- Separator Vertical --}}
                    <div class="h-6 w-px bg-gray-300"></div>

                    {{-- TOMBOL LOGOUT (FORM) --}}
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="text-sm font-medium text-red-600 hover:text-red-800 transition duration-150 ease-in-out flex items-center">
                            {{-- Ikon Logout --}}
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>