<li class="menu-item bg-white border border-gray-200 rounded-lg p-3" data-id="{{ $item->id }}">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <i class="fas fa-grip-vertical text-gray-400 cursor-move handle"></i>
            @if($item->icon)
            <i class="{{ $item->icon }} text-gray-500"></i>
            @endif
            <div>
                <span class="font-medium text-gray-900">{{ $item->getTranslation()?->title ?? 'No Title' }}</span>
                <span class="text-xs text-gray-500 ml-2">{{ $item->url }}</span>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            @if(!$item->is_active)
            <span class="px-2 py-0.5 text-xs bg-gray-100 text-gray-600 rounded">Nonaktif</span>
            @endif
            <button onclick="editMenuItem({{ $item->id }})" class="text-primary-600 hover:text-primary-800 p-1"><i class="fas fa-edit"></i></button>
            <form action="{{ route('admin.menu-items.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Hapus item menu ini?')">
                @csrf @method('DELETE')
                <button class="text-red-600 hover:text-red-800 p-1"><i class="fas fa-trash"></i></button>
            </form>
        </div>
    </div>
    
    @if($item->children->count())
    <ul class="mt-2 ml-6 space-y-2">
        @foreach($item->children->sortBy('sort_order') as $child)
        @include('admin.menus._item', ['item' => $child])
        @endforeach
    </ul>
    @endif
</li>
