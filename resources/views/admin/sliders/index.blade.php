@extends('admin.layouts.app')

@section('title', 'Slider')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Slider</h1>
    <a href="{{ route('admin.sliders.create') }}" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition">
        <i class="fas fa-plus mr-2"></i>Tambah Slider
    </a>
</div>

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($sliders as $slider)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="aspect-video bg-gray-100 relative">
            @if($slider->image)
            <img src="{{ asset('storage/' . $slider->image) }}" class="w-full h-full object-cover">
            @else
            <div class="flex items-center justify-center h-full text-gray-400">
                <i class="fas fa-image text-4xl"></i>
            </div>
            @endif
            <div class="absolute top-2 right-2">
                @if($slider->is_active)
                <span class="px-2 py-1 text-xs font-medium bg-green-500 text-white rounded-full">Aktif</span>
                @else
                <span class="px-2 py-1 text-xs font-medium bg-gray-500 text-white rounded-full">Nonaktif</span>
                @endif
            </div>
        </div>
        <div class="p-4">
            <h3 class="font-semibold text-gray-900 mb-1">{{ $slider->getTranslation()?->title ?? 'No Title' }}</h3>
            <p class="text-sm text-gray-500 line-clamp-2 mb-3">{{ $slider->getTranslation()?->subtitle ?? '' }}</p>
            <div class="flex justify-between items-center">
                <span class="text-xs text-gray-400">Urutan: {{ $slider->order }}</span>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.sliders.edit', $slider) }}" class="text-primary-600 hover:text-primary-800"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('admin.sliders.destroy', $slider) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus slider ini?')">
                        @csrf @method('DELETE')
                        <button class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-12 text-gray-500">
        <i class="fas fa-images text-4xl mb-3 text-gray-300"></i>
        <p>Belum ada slider</p>
    </div>
    @endforelse
</div>

@if($sliders->hasPages())
<div class="mt-6">{{ $sliders->links() }}</div>
@endif
@endsection
