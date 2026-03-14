@extends('admin.layouts.app')

@section('title', 'Kelola Berita')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Berita</h1>
        <p class="text-gray-600">Kelola berita dan artikel</p>
    </div>
    <a href="{{ route('admin.news.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
        <i class="fas fa-plus mr-2"></i>
        Tambah Berita
    </a>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('admin.news.index') }}" method="GET" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berita..." class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
        </div>
        <div class="w-48">
            <select name="category" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                    {{ $category->getTranslation()?->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="w-40">
            <select name="status" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                <option value="">Semua Status</option>
                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
            <i class="fas fa-search mr-2"></i> Filter
        </button>
    </form>
</div>

<!-- Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Berita</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($news as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            @if($item->featured_image)
                            <img src="{{ asset('storage/' . $item->featured_image) }}" class="w-12 h-12 rounded-lg object-cover mr-4" alt="">
                            @else
                            <div class="w-12 h-12 rounded-lg bg-gray-200 flex items-center justify-center mr-4">
                                <i class="fas fa-image text-gray-400"></i>
                            </div>
                            @endif
                            <div>
                                <p class="font-medium text-gray-900">{{ $item->getTranslation()?->title }}</p>
                                <p class="text-sm text-gray-500">{{ Str::limit($item->getTranslation()?->excerpt, 50) }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($item->category)
                        <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded-full">
                            {{ $item->category->getTranslation()?->name }}
                        </span>
                        @else
                        <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($item->is_published)
                        <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">Published</span>
                        @else
                        <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full">Draft</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $item->published_at?->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ number_format($item->views) }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('news.detail', $item->slug) }}" target="_blank" class="p-2 text-gray-400 hover:text-gray-600" title="Lihat">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.news.edit', $item) }}" class="p-2 text-blue-600 hover:text-blue-800" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.news.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:text-red-800" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-newspaper text-4xl mb-4"></i>
                        <p>Belum ada berita</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($news->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $news->links() }}
    </div>
    @endif
</div>
@endsection
