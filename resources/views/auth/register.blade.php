@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="lg:hidden text-center mb-8">
    <div class="w-16 h-16 bg-primary-600 rounded-xl flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-flask text-2xl text-white"></i>
    </div>
    <h1 class="text-2xl font-bold text-gray-900">LPPM</h1>
</div>

<div class="bg-white rounded-2xl shadow-xl p-8">
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Buat Akun Baru</h2>
        <p class="text-gray-500 mt-2">Isi form di bawah untuk mendaftar</p>
    </div>
    
    <form method="POST" action="{{ route('register') }}">
        @csrf
        
        <!-- Name -->
        <div class="mb-5">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-user mr-2 text-gray-400"></i>Nama Lengkap
            </label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                   class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition @error('name') border-red-500 @enderror"
                   placeholder="Masukkan nama lengkap">
            @error('name')
            <p class="mt-2 text-sm text-red-600">
                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
            </p>
            @enderror
        </div>
        
        <!-- Email -->
        <div class="mb-5">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-envelope mr-2 text-gray-400"></i>Email Address
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                   class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition @error('email') border-red-500 @enderror"
                   placeholder="email@example.com">
            @error('email')
            <p class="mt-2 text-sm text-red-600">
                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
            </p>
            @enderror
        </div>
        
        <!-- Password -->
        <div class="mb-5">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-lock mr-2 text-gray-400"></i>Password
            </label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition @error('password') border-red-500 @enderror"
                   placeholder="Minimal 8 karakter">
            @error('password')
            <p class="mt-2 text-sm text-red-600">
                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
            </p>
            @enderror
        </div>
        
        <!-- Confirm Password -->
        <div class="mb-6">
            <label for="password-confirm" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-lock mr-2 text-gray-400"></i>Konfirmasi Password
            </label>
            <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition"
                   placeholder="Ketik ulang password">
        </div>
        
        <!-- Submit Button -->
        <button type="submit" class="w-full bg-gradient-to-r from-primary-600 to-primary-700 text-white py-3 px-6 rounded-xl font-semibold hover:from-primary-700 hover:to-primary-800 transition shadow-lg hover:shadow-xl flex items-center justify-center">
            <i class="fas fa-user-plus mr-2"></i>
            Daftar
        </button>
    </form>
    
    <div class="mt-6 text-center">
        <p class="text-gray-600">
            Sudah punya akun? 
            <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 font-semibold">
                Login di sini
            </a>
        </p>
    </div>
</div>

<div class="mt-8 text-center text-sm text-gray-500">
    <a href="{{ url('/') }}" class="hover:text-primary-600">
        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Beranda
    </a>
</div>
@endsection
