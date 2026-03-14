@extends('admin.layouts.app')

@section('title', 'Bahasa')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Bahasa</h1>
    <button onclick="openModal()" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition">
        <i class="fas fa-plus mr-2"></i>Tambah Bahasa
    </button>
</div>

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bahasa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Flag</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($languages as $language)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $language->native_name }}</div>
                        <div class="text-sm text-gray-500">{{ $language->name }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $language->code }}</td>
                    <td class="px-6 py-4 text-2xl">{{ $language->flag }}</td>
                    <td class="px-6 py-4">
                        @if($language->is_active)
                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Aktif</span>
                        @else
                        <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">Nonaktif</span>
                        @endif
                        @if($language->is_default)
                        <span class="px-2 py-1 text-xs font-medium bg-primary-100 text-primary-800 rounded-full ml-1">Default</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button onclick="editLanguage({{ $language->id }}, '{{ $language->code }}', '{{ $language->name }}', '{{ $language->native_name }}', '{{ $language->flag }}', {{ $language->is_active ? 'true' : 'false' }}, {{ $language->is_default ? 'true' : 'false' }})" class="text-primary-600 hover:text-primary-800 mr-3"><i class="fas fa-edit"></i></button>
                        @if(!$language->is_default)
                        <form action="{{ route('admin.languages.destroy', $language) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus bahasa ini?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada bahasa</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="languageModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4">
        <form id="languageForm" method="POST">
            @csrf
            <div id="methodField"></div>
            
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Tambah Bahasa</h3>
            </div>
            
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode <span class="text-red-500">*</span></label>
                        <input type="text" name="code" id="code" maxlength="5" placeholder="id, en" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Flag</label>
                        <input type="text" name="flag" id="flag" maxlength="10" placeholder="🇮🇩" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama (English) <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" placeholder="Indonesian" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lokal <span class="text-red-500">*</span></label>
                    <input type="text" name="native_name" id="native_name" placeholder="Bahasa Indonesia" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" required>
                </div>
                
                <div class="flex items-center space-x-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" class="rounded border-gray-300 text-primary-600" checked>
                        <span class="ml-2 text-sm text-gray-700">Aktif</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_default" id="is_default" value="1" class="rounded border-gray-300 text-primary-600">
                        <span class="ml-2 text-sm text-gray-700">Default</span>
                    </label>
                </div>
            </div>
            
            <div class="px-6 py-4 bg-gray-50 rounded-b-xl flex justify-end space-x-3">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openModal() {
    document.getElementById('languageModal').classList.remove('hidden');
    document.getElementById('modalTitle').textContent = 'Tambah Bahasa';
    document.getElementById('languageForm').action = '{{ route("admin.languages.store") }}';
    document.getElementById('methodField').innerHTML = '';
    document.getElementById('languageForm').reset();
    document.getElementById('is_active').checked = true;
}

function closeModal() {
    document.getElementById('languageModal').classList.add('hidden');
}

function editLanguage(id, code, name, nativeName, flag, isActive, isDefault) {
    document.getElementById('languageModal').classList.remove('hidden');
    document.getElementById('modalTitle').textContent = 'Edit Bahasa';
    document.getElementById('languageForm').action = '/admin/languages/' + id;
    document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('code').value = code;
    document.getElementById('name').value = name;
    document.getElementById('native_name').value = nativeName;
    document.getElementById('flag').value = flag;
    document.getElementById('is_active').checked = isActive;
    document.getElementById('is_default').checked = isDefault;
}

document.getElementById('languageModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
@endpush
