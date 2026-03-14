@extends('admin.layouts.app')

@section('title', isset($role) ? 'Edit Role' : 'Tambah Role')

@section('content')
<div class="mb-6">
    <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
        <a href="{{ route('admin.roles.index') }}" class="hover:text-primary-600">Roles</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span>{{ isset($role) ? 'Edit' : 'Tambah' }}</span>
    </div>
    <h1 class="text-2xl font-bold text-gray-900">{{ isset($role) ? 'Edit Role: ' . ucfirst($role->name) : 'Tambah Role Baru' }}</h1>
</div>

<form action="{{ isset($role) ? route('admin.roles.update', $role) : route('admin.roles.store') }}" method="POST">
    @csrf
    @if(isset($role)) @method('PUT') @endif
    
    <div class="max-w-4xl">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Role <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $role->name ?? '') }}" class="w-full max-w-md rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" required {{ isset($role) && in_array($role->name, ['super-admin', 'admin']) ? 'readonly' : '' }}>
                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Permissions</label>
                
                @php
                $groupedPermissions = $permissions->groupBy(function($item) {
                    return explode('-', $item->name)[0];
                });
                @endphp
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($groupedPermissions as $group => $perms)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-medium text-gray-900 capitalize">{{ $group }}</h4>
                            <label class="text-xs text-primary-600 cursor-pointer">
                                <input type="checkbox" class="select-all sr-only" data-group="{{ $group }}">
                                <span class="hover:underline">Pilih Semua</span>
                            </label>
                        </div>
                        <div class="space-y-2">
                            @foreach($perms as $permission)
                            <label class="flex items-center">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" data-group="{{ $group }}" {{ isset($role) && $role->permissions->contains($permission->id) ? 'checked' : '' }} class="permission-checkbox rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                <span class="ml-2 text-sm text-gray-700">{{ $permission->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div class="flex items-center space-x-3 pt-4 border-t border-gray-100">
                <button type="submit" class="bg-primary-600 text-white py-2 px-6 rounded-lg hover:bg-primary-700 transition">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
                <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">Batal</a>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.select-all').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const group = this.dataset.group;
        document.querySelectorAll(`.permission-checkbox[data-group="${group}"]`).forEach(cb => {
            cb.checked = this.checked;
        });
    });
});
</script>
@endpush
