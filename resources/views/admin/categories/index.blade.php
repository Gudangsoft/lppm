@extends('admin.layouts.app')

@section('title', 'Kategori')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Kategori</h1>
    <button onclick="openModal()" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition">
        <i class="fas fa-plus mr-2"></i>Tambah Kategori
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($categories as $category)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $category->getTranslation()?->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $category->slug }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500 capitalize">{{ $category->type }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                            {{ $category->news_count ?? 0 }} berita
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button onclick="editCategory({{ $category->id }}, '{{ $category->slug }}', '{{ $category->type }}', {{ json_encode($category->translations->pluck('name', 'locale')) }})" class="text-primary-600 hover:text-primary-800 mr-3"><i class="fas fa-edit"></i></button>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus kategori ini?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada kategori</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($categories->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">{{ $categories->links() }}</div>
    @endif
</div>

<!-- Modal -->
<div id="categoryModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg mx-4">
        <form id="categoryForm" method="POST">
            @csrf
            <div id="methodField"></div>
            
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Tambah Kategori</h3>
            </div>
            
            <div class="p-6 space-y-4">
                @foreach($languages as $lang)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama ({{ $lang->native_name }}) <span class="text-red-500">*</span></label>
                    <input type="text" name="translations[{{ $lang->code }}][name]" id="name_{{ $lang->code }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" required>
                </div>
                @endforeach
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug <span class="text-red-500">*</span></label>
                    <input type="text" name="slug" id="slug" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
                    <select name="type" id="type" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                        <option value="news">Berita</option>
                        <option value="research">Penelitian</option>
                        <option value="pengabdian">Pengabdian</option>
                        <option value="publication">Publikasi</option>
                    </select>
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
    document.getElementById('categoryModal').classList.remove('hidden');
    document.getElementById('modalTitle').textContent = 'Tambah Kategori';
    document.getElementById('categoryForm').action = '{{ route("admin.categories.store") }}';
    document.getElementById('methodField').innerHTML = '';
    document.getElementById('categoryForm').reset();
}

function closeModal() {
    document.getElementById('categoryModal').classList.add('hidden');
}

function editCategory(id, slug, type, translations) {
    document.getElementById('categoryModal').classList.remove('hidden');
    document.getElementById('modalTitle').textContent = 'Edit Kategori';
    document.getElementById('categoryForm').action = '/admin/categories/' + id;
    document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('slug').value = slug;
    document.getElementById('type').value = type;
    
    @foreach($languages as $lang)
    document.getElementById('name_{{ $lang->code }}').value = translations['{{ $lang->code }}'] || '';
    @endforeach
}

document.getElementById('categoryModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
@endpush
