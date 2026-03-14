@extends('layouts.app')

@section('title', 'Verifikasi Email')

@section('content')
<div class="lg:hidden text-center mb-8">
    <div class="w-16 h-16 bg-primary-600 rounded-xl flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-flask text-2xl text-white"></i>
    </div>
    <h1 class="text-2xl font-bold text-gray-900">LPPM</h1>
</div>

<div class="bg-white rounded-2xl shadow-xl p-8">
    <div class="text-center mb-8">
        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-envelope-open text-2xl text-yellow-600"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900">Verifikasi Email Anda</h2>
        <p class="text-gray-500 mt-2">Silakan cek email Anda untuk link verifikasi</p>
    </div>
    
    @if (session('resent'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
        <div class="flex items-center text-green-700">
            <i class="fas fa-check-circle mr-2"></i>
            Link verifikasi baru telah dikirim ke email Anda.
        </div>
    </div>
    @endif
    
    <div class="text-center text-gray-600 mb-6">
        <p>Sebelum melanjutkan, silakan cek email Anda untuk link verifikasi.</p>
        <p class="mt-2">Jika Anda tidak menerima email tersebut,</p>
    </div>
    
    <form method="POST" action="{{ route('verification.resend') }}">
        @csrf
        <button type="submit" class="w-full bg-gradient-to-r from-primary-600 to-primary-700 text-white py-3 px-6 rounded-xl font-semibold hover:from-primary-700 hover:to-primary-800 transition shadow-lg hover:shadow-xl flex items-center justify-center">
            <i class="fas fa-paper-plane mr-2"></i>
            Kirim Ulang Email Verifikasi
        </button>
    </form>
</div>

<div class="mt-8 text-center text-sm text-gray-500">
    <form action="{{ route('logout') }}" method="POST" class="inline">
        @csrf
        <button type="submit" class="hover:text-primary-600">
            <i class="fas fa-sign-out-alt mr-1"></i> Logout
        </button>
    </form>
</div>
@endsection
