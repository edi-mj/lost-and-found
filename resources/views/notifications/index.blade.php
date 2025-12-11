@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="mr-4 text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-gray-900">Notifikasi Anda</h1>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            @if(count($notifications) > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach($notifications as $notification)
                        <li class="p-4 hover:bg-gray-50 {{ !$notification['is_read'] ? 'bg-blue-50' : '' }}">
                            <div class="flex justify-between items-start">
                                <div class="w-full">
                                    <div class="flex justify-between">
                                        <span class="font-semibold text-gray-800">{{ $notification['title'] }}</span>
                                        <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($notification['created_at'])->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">{{ $notification['message'] }}</p>
                                </div>
                                <div class="ml-4 flex-shrink-0">
                                    @if(!$notification['is_read'])
                                        <form action="{{ route('notifications.read', $notification['id']) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-xs text-indigo-600 hover:text-indigo-900">Mark as Read</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="p-8 text-center text-gray-500">
                    You have no notifications.
                </div>
            @endif
        </div>
    </div>
@endsection
