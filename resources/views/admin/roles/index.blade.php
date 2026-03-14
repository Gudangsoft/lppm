@extends('admin.layouts.app')

@section('title', 'Role & Permissions')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Role & Permissions</h1>
    <a href="{{ route('admin.roles.create') }}" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition">
        <i class="fas fa-plus mr-2"></i>Tambah Role
    </a>
</div>

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($roles as $role)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">{{ ucfirst($role->name) }}</h3>
                <p class="text-sm text-gray-500">{{ $role->users->count() }} pengguna</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.roles.edit', $role) }}" class="text-primary-600 hover:text-primary-800"><i class="fas fa-edit"></i></a>
                @if(!in_array($role->name, ['super-admin', 'admin']))
                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus role ini?')">
                    @csrf @method('DELETE')
                    <button class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
                </form>
                @endif
            </div>
        </div>
        
        <div class="space-y-2">
            <p class="text-xs font-medium text-gray-500 uppercase">Permissions ({{ $role->permissions->count() }})</p>
            <div class="flex flex-wrap gap-1">
                @foreach($role->permissions->take(5) as $permission)
                <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">{{ $permission->name }}</span>
                @endforeach
                @if($role->permissions->count() > 5)
                <span class="px-2 py-1 text-xs bg-primary-100 text-primary-700 rounded">+{{ $role->permissions->count() - 5 }} lainnya</span>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
