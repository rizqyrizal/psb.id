@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto mt-10 bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-bold mb-4">Undangan Diterima</h2>
    <p>Hai <strong>{{ $name }}</strong>, akun guru Anda sudah aktif.</p>

    <div class="mt-4 bg-gray-100 p-4 rounded">
        <p><strong>Email:</strong> {{ $email }}</p>
        <p><strong>Password:</strong> {{ $password }}</p>
    </div>

    <p class="mt-4 text-sm text-gray-600">
        Demi keamanan, segera ubah password setelah login.
    </p>
</div>
@endsection
