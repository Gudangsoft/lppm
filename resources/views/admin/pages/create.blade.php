@extends('admin.layouts.app')

@section('title', isset($page) ? 'Edit Halaman' : 'Tambah Halaman')

@section('content')
<div class="mb-6">
    <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
        <a href="{{ route('admin.pages.index') }}" class="hover:text-primary-600">Halaman</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span>{{ isset($page) ? 'Edit' : 'Tambah' }}</span>
    </div>
    <h1 class="text-2xl font-bold text-gray-900">{{ isset($page) ? 'Edit Halaman' : 'Tambah Halaman Baru' }}</h1>
</div>

<form action="{{ isset($page) ? route('admin.pages.update', $page) : route('admin.pages.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($page)) @method('PUT') @endif
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div x-data="{ activeTab: '{{ $languages->first()->code }}' }" class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="border-b border-gray-100">
                    <nav class="flex -mb-px">
                        @foreach($languages as $lang)
                        <button type="button" @click="activeTab = '{{ $lang->code }}'" :class="activeTab === '{{ $lang->code }}' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-6 py-3 border-b-2 font-medium text-sm transition">
                            {{ $lang->flag }} {{ $lang->native_name }}
                        </button>
                        @endforeach
                    </nav>
                </div>
                
                <div class="p-6">
                    @foreach($languages as $lang)
                    @php $translation = isset($page) ? $page->translations->where('locale', $lang->code)->first() : null; @endphp
                    <div x-show="activeTab === '{{ $lang->code }}'" x-cloak class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul <span class="text-red-500">*</span></label>
                            <input type="text" name="translations[{{ $lang->code }}][title]" value="{{ old("translations.{$lang->code}.title", $translation?->title) }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Konten</label>
                            <textarea name="translations[{{ $lang->code }}][content]" rows="12" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 editor">{{ old("translations.{$lang->code}.content", $translation?->content) }}</textarea>
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
        
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Pengaturan</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Slug <span class="text-red-500">*</span></label>
                        <input type="text" name="slug" value="{{ old('slug', $page->slug ?? '') }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Template</label>
                        <select name="template" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                            <option value="default" {{ old('template', $page->template ?? '') == 'default' ? 'selected' : '' }}>Default</option>
                            <option value="full-width" {{ old('template', $page->template ?? '') == 'full-width' ? 'selected' : '' }}>Full Width</option>
                            <option value="sidebar" {{ old('template', $page->template ?? '') == 'sidebar' ? 'selected' : '' }}>With Sidebar</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $page->sort_order ?? 0) }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', $page->is_published ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600">
                        <label for="is_published" class="ml-2 text-sm text-gray-700">Publikasikan</label>
                    </div>
                </div>
                
                <div class="mt-6 flex space-x-3">
                    <button type="submit" class="flex-1 bg-primary-600 text-white py-2 px-4 rounded-lg hover:bg-primary-700 transition"><i class="fas fa-save mr-2"></i>Simpan</button>
                    <a href="{{ route('admin.pages.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">Batal</a>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Gambar Header</h3>
                <div x-data="{ preview: '{{ isset($page) && $page->featured_image ? asset('storage/' . $page->featured_image) : '' }}' }">
                    <template x-if="preview">
                        <div class="mb-3"><img :src="preview" class="w-full h-32 object-cover rounded-lg"></div>
                    </template>
                    <input type="file" name="featured_image" accept="image/*" @change="preview = URL.createObjectURL($event.target.files[0])" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700">
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
    ClassicEditor.create(el).catch(error => console.error(error));
});
</script>
@endpush
