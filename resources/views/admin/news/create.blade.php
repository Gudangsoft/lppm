@extends('admin.layouts.app')

@section('title', isset($news) ? 'Edit Berita' : 'Tambah Berita')

@section('content')
<div class="mb-6">
    <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
        <a href="{{ route('admin.news.index') }}" class="hover:text-primary-600">Berita</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span>{{ isset($news) ? 'Edit' : 'Tambah' }}</span>
    </div>
    <h1 class="text-2xl font-bold text-gray-900">{{ isset($news) ? 'Edit Berita' : 'Tambah Berita Baru' }}</h1>
</div>

<form action="{{ isset($news) ? route('admin.news.update', $news) : route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($news))
    @method('PUT')
    @endif
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Translations Tabs -->
            <div x-data="{ activeTab: '{{ $languages->first()->code }}' }" class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="border-b border-gray-100">
                    <nav class="flex -mb-px">
                        @foreach($languages as $lang)
                        <button type="button" @click="activeTab = '{{ $lang->code }}'" :class="activeTab === '{{ $lang->code }}' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="px-6 py-3 border-b-2 font-medium text-sm transition">
                            {{ $lang->flag }} {{ $lang->native_name }}
                        </button>
                        @endforeach
                    </nav>
                </div>
                
                <div class="p-6">
                    @foreach($languages as $lang)
                    @php
                        $translation = isset($news) ? $news->translations->where('locale', $lang->code)->first() : null;
                    @endphp
                    <div x-show="activeTab === '{{ $lang->code }}'" x-cloak class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul <span class="text-red-500">*</span></label>
                            <input type="text" name="translations[{{ $lang->code }}][title]" value="{{ old("translations.{$lang->code}.title", $translation?->title) }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ringkasan</label>
                            <textarea name="translations[{{ $lang->code }}][excerpt]" rows="2" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">{{ old("translations.{$lang->code}.excerpt", $translation?->excerpt) }}</textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Konten</label>
                            <textarea name="translations[{{ $lang->code }}][content]" id="content_{{ $lang->code }}" rows="10" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 editor">{{ old("translations.{$lang->code}.content", $translation?->content) }}</textarea>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                                <input type="text" name="translations[{{ $lang->code }}][meta_title]" value="{{ old("translations.{$lang->code}.meta_title", $translation?->meta_title) }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                                <input type="text" name="translations[{{ $lang->code }}][meta_description]" value="{{ old("translations.{$lang->code}.meta_description", $translation?->meta_description) }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Publish -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Publikasi</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Slug <span class="text-red-500">*</span></label>
                        <input type="text" name="slug" value="{{ old('slug', $news->slug ?? '') }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Publikasi</label>
                        <input type="datetime-local" name="published_at" value="{{ old('published_at', isset($news) && $news->published_at ? $news->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', $news->is_published ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500">
                        <label for="is_published" class="ml-2 text-sm text-gray-700">Publikasikan</label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $news->is_featured ?? false) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500">
                        <label for="is_featured" class="ml-2 text-sm text-gray-700">Berita Unggulan</label>
                    </div>
                </div>
                
                <div class="mt-6 flex space-x-3">
                    <button type="submit" class="flex-1 bg-primary-600 text-white py-2 px-4 rounded-lg hover:bg-primary-700 transition">
                        <i class="fas fa-save mr-2"></i>
                        Simpan
                    </button>
                    <a href="{{ route('admin.news.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                        Batal
                    </a>
                </div>
            </div>
            
            <!-- Category -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Kategori</h3>
                <select name="category_id" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $news->category_id ?? '') == $category->id ? 'selected' : '' }}>
                        {{ $category->getTranslation()?->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Featured Image -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Gambar Utama</h3>
                
                <div x-data="{ preview: '{{ isset($news) && $news->featured_image ? asset('storage/' . $news->featured_image) : '' }}' }">
                    <template x-if="preview">
                        <div class="mb-3">
                            <img :src="preview" class="w-full h-40 object-cover rounded-lg">
                        </div>
                    </template>
                    
                    <label class="block">
                        <span class="sr-only">Pilih gambar</span>
                        <input type="file" name="featured_image" accept="image/*" @change="preview = URL.createObjectURL($event.target.files[0])" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                    </label>
                    <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF maks. 2MB</p>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
document.querySelectorAll('.editor').forEach(el => {
    ClassicEditor.create(el, {
        toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'insertTable', 'undo', 'redo']
    }).catch(error => console.error(error));
});
</script>
@endpush
