@extends('admin.layouts.app')

@section('title', 'Menu')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Pengaturan Menu</h1>
    <p class="text-gray-500 mt-1">Kelola menu navigasi website</p>
</div>

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
@endif

<div x-data="{ activeMenu: '{{ $menus->first()?->id }}' }" class="flex flex-col lg:flex-row gap-6">
    <!-- Menu List -->
    <div class="lg:w-64 flex-shrink-0">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-900">Menu</h3>
                <button onclick="createMenu()" class="text-primary-600 hover:text-primary-800 text-sm"><i class="fas fa-plus"></i></button>
            </div>
            
            <div class="space-y-2">
                @foreach($menus as $menu)
                <button @click="activeMenu = '{{ $menu->id }}'" :class="activeMenu === '{{ $menu->id }}' ? 'bg-primary-50 text-primary-700 border-primary-200' : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-200'" class="w-full flex items-center justify-between px-3 py-2 rounded-lg border text-sm font-medium transition">
                    <span>{{ $menu->name }}</span>
                    <span class="text-xs px-2 py-0.5 bg-gray-100 rounded">{{ $menu->location }}</span>
                </button>
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- Menu Items -->
    <div class="flex-1">
        @foreach($menus as $menu)
        <div x-show="activeMenu === '{{ $menu->id }}'" x-cloak class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $menu->name }}</h3>
                        <p class="text-sm text-gray-500">Lokasi: {{ $menu->location }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <button onclick="addMenuItem('{{ $menu->id }}')" class="bg-primary-600 text-white px-3 py-2 rounded-lg hover:bg-primary-700 text-sm">
                            <i class="fas fa-plus mr-1"></i>Tambah Item
                        </button>
                        <button onclick="deleteMenu('{{ $menu->id }}')" class="bg-red-100 text-red-600 px-3 py-2 rounded-lg hover:bg-red-200 text-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                
                <div class="nested-menu" data-menu-id="{{ $menu->id }}">
                    @if($menu->items->count())
                    <ul class="space-y-2" id="menu-items-{{ $menu->id }}">
                        @foreach($menu->items->whereNull('parent_id')->sortBy('sort_order') as $item)
                        @include('admin.menus._item', ['item' => $item])
                        @endforeach
                    </ul>
                    @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-bars text-3xl mb-2 text-gray-300"></i>
                        <p>Menu kosong. Tambahkan item menu.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Add Menu Modal -->
<div id="menuModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4">
        <form action="{{ route('admin.menus.store') }}" method="POST">
            @csrf
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Buat Menu Baru</h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Menu <span class="text-red-500">*</span></label>
                    <input type="text" name="name" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                    <select name="location" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                        <option value="header">Header</option>
                        <option value="footer">Footer</option>
                        <option value="sidebar">Sidebar</option>
                    </select>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 rounded-b-xl flex justify-end space-x-3">
                <button type="button" onclick="closeMenuModal()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Add Menu Item Modal -->
<div id="menuItemModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg mx-4">
        <form id="menuItemForm" method="POST">
            @csrf
            <input type="hidden" name="menu_id" id="menuItemMenuId">
            <div id="menuItemMethodField"></div>
            
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 id="menuItemModalTitle" class="text-lg font-semibold text-gray-900">Tambah Menu Item</h3>
            </div>
            
            <div class="p-6 space-y-4">
                @foreach($languages as $lang)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Label ({{ $lang->native_name }}) <span class="text-red-500">*</span></label>
                    <input type="text" name="translations[{{ $lang->code }}][title]" id="item_title_{{ $lang->code }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" required>
                </div>
                @endforeach
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">URL <span class="text-red-500">*</span></label>
                    <input type="text" name="url" id="item_url" placeholder="/atau-https://..." class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" required>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Parent</label>
                        <select name="parent_id" id="item_parent" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                            <option value="">Tidak Ada</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Icon (Font Awesome)</label>
                        <input type="text" name="icon" id="item_icon" placeholder="fas fa-home" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Target</label>
                    <select name="target" id="item_target" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                        <option value="_self">Jendela Yang Sama</option>
                        <option value="_blank">Tab Baru</option>
                    </select>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="item_is_active" value="1" checked class="rounded border-gray-300 text-primary-600">
                    <label for="item_is_active" class="ml-2 text-sm text-gray-700">Aktif</label>
                </div>
            </div>
            
            <div class="px-6 py-4 bg-gray-50 rounded-b-xl flex justify-end space-x-3">
                <button type="button" onclick="closeMenuItemModal()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function createMenu() {
    document.getElementById('menuModal').classList.remove('hidden');
}

function closeMenuModal() {
    document.getElementById('menuModal').classList.add('hidden');
}

function addMenuItem(menuId) {
    document.getElementById('menuItemModal').classList.remove('hidden');
    document.getElementById('menuItemModalTitle').textContent = 'Tambah Menu Item';
    document.getElementById('menuItemForm').action = '{{ url("admin/menu-items") }}';
    document.getElementById('menuItemMethodField').innerHTML = '';
    document.getElementById('menuItemMenuId').value = menuId;
    document.getElementById('menuItemForm').reset();
    document.getElementById('item_is_active').checked = true;
}

function closeMenuItemModal() {
    document.getElementById('menuItemModal').classList.add('hidden');
}

function deleteMenu(menuId) {
    if (confirm('Yakin hapus menu ini beserta semua itemnya?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/menus/' + menuId;
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
    }
}

document.getElementById('menuModal').addEventListener('click', function(e) {
    if (e.target === this) closeMenuModal();
});

document.getElementById('menuItemModal').addEventListener('click', function(e) {
    if (e.target === this) closeMenuItemModal();
});
</script>
@endpush
