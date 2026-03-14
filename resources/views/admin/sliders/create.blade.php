@extends('admin.layouts.app')

@section('title', isset($slider) ? 'Edit Slider' : 'Tambah Slider')

@section('content')
<div class="mb-6">
    <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
        <a href="{{ route('admin.sliders.index') }}" class="hover:text-primary-600">Slider</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span>{{ isset($slider) ? 'Edit' : 'Tambah' }}</span>
    </div>
    <h1 class="text-2xl font-bold text-gray-900">{{ isset($slider) ? 'Edit Slider' : 'Tambah Slider Baru' }}</h1>
</div>

<form action="{{ isset($slider) ? route('admin.sliders.update', $slider) : route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($slider)) @method('PUT') @endif
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div x-data="{ activeTab: '{{ $languages->first()->code }}' }" class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="border-b border-gray-100">
                    <nav class="flex -mb-px">
                        @foreach($languages as $lang)
                        <button type="button" @click="activeTab = '{{ $lang->code }}'" :class="activeTab === '{{ $lang->code }}' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500'" class="px-6 py-3 border-b-2 font-medium text-sm transition">
                            {{ $lang->flag }} {{ $lang->native_name }}
                        </button>
                        @endforeach
                    </nav>
                </div>
                
                <div class="p-6">
                    @foreach($languages as $lang)
                    @php $translation = isset($slider) ? $slider->translations->where('locale', $lang->code)->first() : null; @endphp
                    <div x-show="activeTab === '{{ $lang->code }}'" x-cloak class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                            <input type="text" name="translations[{{ $lang->code }}][title]" value="{{ old("translations.{$lang->code}.title", $translation?->title) }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Subjudul</label>
                            <input type="text" name="translations[{{ $lang->code }}][subtitle]" value="{{ old("translations.{$lang->code}.subtitle", $translation?->subtitle) }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Teks Tombol</label>
                            <input type="text" name="translations[{{ $lang->code }}][button_text]" value="{{ old("translations.{$lang->code}.button_text", $translation?->button_text) }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">URL Link</label>
                        <input type="url" name="button_url" value="{{ old('button_url', $slider->button_url ?? '') }}" placeholder="https://" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $slider->sort_order ?? 0) }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $slider->is_active ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600">
                        <label for="is_active" class="ml-2 text-sm text-gray-700">Aktif</label>
                    </div>
                </div>
                
                <div class="mt-6 flex space-x-3">
                    <button type="submit" class="flex-1 bg-primary-600 text-white py-2 px-4 rounded-lg hover:bg-primary-700"><i class="fas fa-save mr-2"></i>Simpan</button>
                    <a href="{{ route('admin.sliders.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">Batal</a>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Gambar Slider</h3>
                
                <div x-data="{ preview: '{{ isset($slider) && $slider->image ? asset('storage/' . $slider->image) : '' }}' }">
                    <template x-if="preview">
                        <div class="mb-3"><img :src="preview" class="w-full h-40 object-cover rounded-lg"></div>
                    </template>
                    <input type="file" name="image" accept="image/*" @change="preview = URL.createObjectURL($event.target.files[0])" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700">
                    <p class="mt-1 text-xs text-gray-500">Resolusi: 1920x600px</p>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
