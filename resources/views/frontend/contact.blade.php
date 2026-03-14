@extends('frontend.layouts.app')

@section('title', __('frontend.contact') . ' - ' . ($settings['site_name'] ?? 'LPPM'))

@section('content')
<!-- Page Header -->
<div class="bg-gradient-to-r from-primary-700 to-primary-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">{{ __('frontend.contact') }}</h1>
        <p class="text-xl text-primary-100">{{ __('frontend.contact_subtitle') }}</p>
    </div>
</div>

<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Contact Info -->
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-map-marker-alt text-xl text-primary-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-1">{{ __('frontend.address') }}</h3>
                            <p class="text-gray-600">{{ $settings['contact_address'] ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-phone text-xl text-primary-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-1">{{ __('frontend.phone') }}</h3>
                            <p class="text-gray-600">{{ $settings['contact_phone'] ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-envelope text-xl text-primary-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-1">{{ __('frontend.email') }}</h3>
                            <p class="text-gray-600">{{ $settings['contact_email'] ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Social Media -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">{{ __('frontend.follow_us') }}</h3>
                    <div class="flex space-x-3">
                        @foreach(['facebook', 'twitter', 'instagram', 'youtube', 'linkedin'] as $social)
                        @if($settings['social_' . $social] ?? false)
                        <a href="{{ $settings['social_' . $social] }}" target="_blank" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-600 hover:bg-primary-100 hover:text-primary-600 transition">
                            <i class="fab fa-{{ $social }}"></i>
                        </a>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm p-6 lg:p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('frontend.send_message') }}</h2>
                    
                    @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                        {{ session('success') }}
                    </div>
                    @endif
                    
                    <form action="{{ route('contact.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('frontend.name') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" required>
                                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('frontend.email') }} <span class="text-red-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" required>
                                @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('frontend.phone') }}</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('frontend.subject') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="subject" value="{{ old('subject') }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" required>
                                @error('subject')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('frontend.message') }} <span class="text-red-500">*</span></label>
                            <textarea name="message" rows="5" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" required>{{ old('message') }}</textarea>
                            @error('message')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        
                        <button type="submit" class="w-full md:w-auto bg-primary-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary-700 transition">
                            <i class="fas fa-paper-plane mr-2"></i>{{ __('frontend.send') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Map -->
        @if($settings['google_maps_embed'] ?? false)
        <div class="mt-8">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <iframe src="{{ $settings['google_maps_embed'] }}" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection
