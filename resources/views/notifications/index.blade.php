@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl">
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}"
                        class="p-2 rounded-full bg-white shadow-sm text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Notifikasi</h1>
                        <p class="text-sm text-gray-500 mt-1">Pantau aktivitas terbaru laporan Anda.</p>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="space-y-4">
                @if(count($notifications) > 0)
                    @foreach($notifications as $notification)
                        <div
                            class="group relative bg-white rounded-xl shadow-sm border border-gray-100 p-5 transition-all duration-200 hover:shadow-md hover:border-indigo-100 {{ !$notification['is_read'] ? 'bg-indigo-50/50 border-indigo-100' : '' }}">
                            <div class="flex items-start space-x-4">
                                <!-- Icon based on type (assuming 'type' might be passed or defaulting to info) -->
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-10 h-10 rounded-full flex items-center justify-center {{ !$notification['is_read'] ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-500' }}">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                            </path>
                                        </svg>
                                    </div>
                                </div>

                                <!-- Body -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-1">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            @if(!$notification['is_read'])
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800 mr-2">
                                                    Baru
                                                </span>
                                            @endif
                                            Pesan Baru
                                        </p>
                                        <span class="text-xs text-gray-400 flex-shrink-0 whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($notification['created_at'])->diffForHumans() }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 leading-relaxed">{{ $notification['message'] }}</p>
                                </div>

                                <!-- Actions -->
                                @if(!$notification['is_read'])
                                    <div class="flex-shrink-0 self-center ml-2">
                                        <form action="{{ route('notifications.read', $notification['id']) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="p-2 rounded-full text-indigo-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors"
                                                title="Tandai sudah dibaca">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- Empty State -->
                    <div class="text-center py-20 bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="w-24 h-24 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-12 h-12 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Belum ada notifikasi</h3>
                        <p class="text-gray-500 mt-1 max-w-sm mx-auto">Saat ada update status laporan Anda, notifikasinya akan
                            muncul di sini.</p>
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center justify-center mt-6 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-sm">
                            Kembali ke Dashboard
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection