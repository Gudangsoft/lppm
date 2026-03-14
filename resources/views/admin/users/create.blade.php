@extends('admin.layouts.app')

@section('title', isset($user) ? 'Edit Pengguna' : 'Tambah Pengguna')

@section('content')
<div class="mb-6">
    <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
        <a href="{{ route('admin.users.index') }}" class="hover:text-primary-600">Pengguna</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span>{{ isset($user) ? 'Edit' : 'Tambah' }}</span>
    </div>
    <h1 class="text-2xl font-bold text-gray-900">{{ isset($user) ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}</h1>
</div>

<form action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}" method="POST">
    @csrf
    @if(isset($user)) @method('PUT') @endif
    
    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" required>
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" required>
                    @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password @if(!isset($user))<span class="text-red-500">*</span>@endif</label>
                    <input type="password" name="password" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" {{ isset($user) ? '' : 'required' }}>
                    @if(isset($user))<p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah</p>@endif
                    @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Role <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($roles as $role)
                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="role" value="{{ $role->name }}" {{ old('role', isset($user) ? $user->roles->first()?->name : '') == $role->name ? 'checked' : '' }} class="text-primary-600 border-gray-300 focus:ring-primary-500">
                        <span class="ml-2 text-sm font-medium text-gray-700">{{ ucfirst($role->name) }}</span>
                    </label>
                    @endforeach
                </div>
                @error('role')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                    <span class="ml-2 text-sm text-gray-700">Aktif</span>
                </label>
            </div>
            
            <div class="flex items-center space-x-3 pt-4 border-t border-gray-100">
                <button type="submit" class="bg-primary-600 text-white py-2 px-6 rounded-lg hover:bg-primary-700 transition">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">Batal</a>
            </div>
        </div>
    </div>
</form>
@endsection
