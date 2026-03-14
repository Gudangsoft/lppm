@extends('admin.layouts.app')

@section('title', 'Pengaturan')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Pengaturan Website</h1>
    <p class="text-gray-500 mt-1">Kelola pengaturan umum, tampilan, dan konfigurasi website</p>
</div>

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
@endif

<div x-data="{ activeTab: 'general' }" class="flex flex-col lg:flex-row gap-6">
    <!-- Sidebar Tabs -->
    <div class="lg:w-64 flex-shrink-0">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <nav class="space-y-1 p-3">
                <button @click="activeTab = 'general'" :class="activeTab === 'general' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50'" class="w-full flex items-center px-3 py-2 rounded-lg text-sm font-medium transition">
                    <i class="fas fa-cog w-5 mr-2"></i>Umum
                </button>
                <button @click="activeTab = 'appearance'" :class="activeTab === 'appearance' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50'" class="w-full flex items-center px-3 py-2 rounded-lg text-sm font-medium transition">
                    <i class="fas fa-palette w-5 mr-2"></i>Tampilan
                </button>
                <button @click="activeTab = 'social'" :class="activeTab === 'social' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50'" class="w-full flex items-center px-3 py-2 rounded-lg text-sm font-medium transition">
                    <i class="fas fa-share-alt w-5 mr-2"></i>Media Sosial
                </button>
                <button @click="activeTab = 'contact'" :class="activeTab === 'contact' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50'" class="w-full flex items-center px-3 py-2 rounded-lg text-sm font-medium transition">
                    <i class="fas fa-phone w-5 mr-2"></i>Kontak
                </button>
                <button @click="activeTab = 'seo'" :class="activeTab === 'seo' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50'" class="w-full flex items-center px-3 py-2 rounded-lg text-sm font-medium transition">
                    <i class="fas fa-search w-5 mr-2"></i>SEO
                </button>
                <button @click="activeTab = 'footer'" :class="activeTab === 'footer' ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50'" class="w-full flex items-center px-3 py-2 rounded-lg text-sm font-medium transition">
                    <i class="fas fa-shoe-prints w-5 mr-2"></i>Footer
                </button>
            </nav>
        </div>
    </div>
    
    <!-- Content -->
    <div class="flex-1">
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- General Settings -->
            <div x-show="activeTab === 'general'" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Pengaturan Umum</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Website</label>
                        <input type="text" name="settings[site_name]" value="{{ $settings['site_name'] ?? '' }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tagline / Slogan</label>
                        <input type="text" name="settings[site_tagline]" value="{{ $settings['site_tagline'] ?? '' }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Website</label>
                        <textarea name="settings[site_description]" rows="3" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">{{ $settings['site_description'] ?? '' }}</textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bahasa Default</label>
                        <select name="settings[default_language]" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                            @foreach($languages as $lang)
                            <option value="{{ $lang->code }}" {{ ($settings['default_language'] ?? 'id') == $lang->code ? 'selected' : '' }}>{{ $lang->native_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Appearance Settings -->
            <div x-show="activeTab === 'appearance'" x-cloak class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Pengaturan Tampilan</h3>
                
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Logo</label>
                            @if($settings['site_logo'] ?? false)
                            <div class="mb-2"><img src="{{ asset('storage/' . $settings['site_logo']) }}" class="h-12"></div>
                            @endif
                            <input type="file" name="site_logo" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Favicon</label>
                            @if($settings['site_favicon'] ?? false)
                            <div class="mb-2"><img src="{{ asset('storage/' . $settings['site_favicon']) }}" class="h-8"></div>
                            @endif
                            <input type="file" name="site_favicon" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Warna Tema Utama</label>
                        <div class="flex items-center space-x-3">
                            <input type="color" name="settings[primary_color]" value="{{ $settings['primary_color'] ?? '#2563eb' }}" class="w-12 h-12 rounded-lg border border-gray-300 cursor-pointer">
                            <input type="text" name="settings[primary_color_hex]" value="{{ $settings['primary_color'] ?? '#2563eb' }}" class="w-32 rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 text-sm">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Header Style</label>
                        <select name="settings[header_style]" class="w-full md:w-64 rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                            <option value="default" {{ ($settings['header_style'] ?? '') == 'default' ? 'selected' : '' }}>Default</option>
                            <option value="centered" {{ ($settings['header_style'] ?? '') == 'centered' ? 'selected' : '' }}>Centered</option>
                            <option value="transparent" {{ ($settings['header_style'] ?? '') == 'transparent' ? 'selected' : '' }}>Transparent</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Social Media Settings -->
            <div x-show="activeTab === 'social'" x-cloak class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Media Sosial</h3>
                
                <div class="space-y-4">
                    @foreach(['facebook' => 'Facebook', 'twitter' => 'Twitter/X', 'instagram' => 'Instagram', 'youtube' => 'YouTube', 'linkedin' => 'LinkedIn'] as $key => $label)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-3 py-2 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                <i class="fab fa-{{ $key }}"></i>
                            </span>
                            <input type="url" name="settings[social_{{ $key }}]" value="{{ $settings['social_' . $key] ?? '' }}" placeholder="https://" class="flex-1 rounded-r-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Contact Settings -->
            <div x-show="activeTab === 'contact'" x-cloak class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Informasi Kontak</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea name="settings[contact_address]" rows="2" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">{{ $settings['contact_address'] ?? '' }}</textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                            <input type="text" name="settings[contact_phone]" value="{{ $settings['contact_phone'] ?? '' }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="settings[contact_email]" value="{{ $settings['contact_email'] ?? '' }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Google Maps Embed URL</label>
                        <input type="text" name="settings[google_maps_embed]" value="{{ $settings['google_maps_embed'] ?? '' }}" placeholder="https://www.google.com/maps/embed?pb=..." class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                    </div>
                </div>
            </div>
            
            <!-- SEO Settings -->
            <div x-show="activeTab === 'seo'" x-cloak class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">SEO & Analytics</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Meta Keywords</label>
                        <input type="text" name="settings[meta_keywords]" value="{{ $settings['meta_keywords'] ?? '' }}" placeholder="keyword1, keyword2, keyword3" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Google Analytics ID</label>
                        <input type="text" name="settings[google_analytics]" value="{{ $settings['google_analytics'] ?? '' }}" placeholder="G-XXXXXXXXXX" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Custom Head Scripts</label>
                        <textarea name="settings[custom_head_scripts]" rows="4" class="w-full font-mono text-sm rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" placeholder="<!-- Custom scripts -->">{{ $settings['custom_head_scripts'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>
            
            <!-- Footer Settings -->
            <div x-show="activeTab === 'footer'" x-cloak class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Footer</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Teks Copyright</label>
                        <input type="text" name="settings[footer_copyright]" value="{{ $settings['footer_copyright'] ?? '© ' . date('Y') . ' LPPM. All rights reserved.' }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Footer</label>
                        <textarea name="settings[footer_description]" rows="3" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">{{ $settings['footer_description'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>
            
            <div class="mt-6">
                <button type="submit" class="bg-primary-600 text-white py-2 px-6 rounded-lg hover:bg-primary-700 transition">
                    <i class="fas fa-save mr-2"></i>Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
