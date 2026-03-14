@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="lg:hidden text-center mb-8">
    <div class="w-16 h-16 bg-primary-600 rounded-xl flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-flask text-2xl text-white"></i>
    </div>
    <h1 class="text-2xl font-bold text-gray-900">LPPM</h1>
</div>

<div class="bg-white rounded-2xl shadow-xl p-8">
    <div class="text-center mb-8">
        <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-key text-2xl text-primary-600"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900">Lupa Password?</h2>
        <p class="text-gray-500 mt-2">Masukkan email Anda untuk menerima link reset password</p>
    </div>
    
    @if (session('status'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
        <div class="flex items-center text-green-700">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('status') }}
        </div>
    </div>
    @endif
    
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        
        <!-- Email -->
        <div class="mb-6">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-envelope mr-2 text-gray-400"></i>Email Address
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                   class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition @error('email') border-red-500 @enderror"
                   placeholder="email@example.com">
            @error('email')
            <p class="mt-2 text-sm text-red-600">
                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
            </p>
            @enderror
        </div>
        
        <!-- Submit Button -->
        <button type="submit" class="w-full bg-gradient-to-r from-primary-600 to-primary-700 text-white py-3 px-6 rounded-xl font-semibold hover:from-primary-700 hover:to-primary-800 transition shadow-lg hover:shadow-xl flex items-center justify-center">
            <i class="fas fa-paper-plane mr-2"></i>
            Kirim Link Reset Password
        </button>
    </form>
    
    <div class="mt-6 text-center">
        <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 font-semibold">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Login
        </a>
    </div>
</div>

<div class="mt-8 text-center text-sm text-gray-500">
    <a href="{{ url('/') }}" class="hover:text-primary-600">
        <i class="fas fa-home mr-1"></i> Kembali ke Beranda
    </a>
</div>
@endsection
