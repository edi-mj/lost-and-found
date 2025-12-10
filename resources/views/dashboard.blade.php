@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <h1>Dashboard Page</h1>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>

@endsection