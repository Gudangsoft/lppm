@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
    <p class="text-gray-600">Selamat datang di panel admin LPPM</p>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-users text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Total Pengguna</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <i class="fas fa-newspaper text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Total Berita</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_news'] }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                <i class="fas fa-flask text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Penelitian</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_research'] }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                <i class="fas fa-hands-helping text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Pengabdian</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_pengabdian'] }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-100 text-red-600">
                <i class="fas fa-envelope text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Pesan Baru</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['unread_messages'] }}</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent News -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Berita Terbaru</h2>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentNews as $news)
            <div class="p-4 flex items-center">
                @if($news->featured_image)
                <img src="{{ asset('storage/' . $news->featured_image) }}" class="w-12 h-12 rounded-lg object-cover mr-4" alt="">
                @else
                <div class="w-12 h-12 rounded-lg bg-gray-200 flex items-center justify-center mr-4">
                    <i class="fas fa-image text-gray-400"></i>
                </div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $news->getTranslation()?->title }}</p>
                    <p class="text-xs text-gray-500">{{ $news->published_at?->format('d M Y') }}</p>
                </div>
                <a href="{{ route('admin.news.edit', $news) }}" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-edit"></i>
                </a>
            </div>
            @empty
            <div class="p-6 text-center text-gray-500">
                Belum ada berita
            </div>
            @endforelse
        </div>
    </div>
    
    <!-- Recent Research -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Penelitian Terbaru</h2>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentResearch as $research)
            <div class="p-4 flex items-center">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $research->getTranslation()?->title }}</p>
                    <p class="text-xs text-gray-500">{{ $research->year }} - {{ ucfirst($research->status) }}</p>
                </div>
                <a href="{{ route('admin.researches.edit', $research) }}" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-edit"></i>
                </a>
            </div>
            @empty
            <div class="p-6 text-center text-gray-500">
                Belum ada penelitian
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Recent Messages -->
<div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
        <h2 class="text-lg font-semibold text-gray-900">Pesan Terbaru</h2>
    </div>
    <div class="divide-y divide-gray-100">
        @forelse($recentMessages as $message)
        <div class="p-4 flex items-center {{ $message->status === 'unread' ? 'bg-blue-50' : '' }}">
            <div class="flex-1">
                <div class="flex items-center">
                    <p class="text-sm font-medium text-gray-900">{{ $message->name }}</p>
                    @if($message->status === 'unread')
                    <span class="ml-2 px-2 py-0.5 text-xs bg-blue-100 text-blue-600 rounded-full">Baru</span>
                    @endif
                </div>
                <p class="text-sm text-gray-600">{{ $message->subject }}</p>
                <p class="text-xs text-gray-500">{{ $message->created_at->diffForHumans() }}</p>
            </div>
        </div>
        @empty
        <div class="p-6 text-center text-gray-500">
            Belum ada pesan
        </div>
        @endforelse
    </div>
</div>
@endsection
