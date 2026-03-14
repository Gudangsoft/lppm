<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with(['items' => function($q) {
            $q->with('children')->whereNull('parent_id')->orderBy('order');
        }])->get();

        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.menus.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string'],
        ]);

        Menu::create([
            'name' => $request->name,
            'location' => $request->location,
        ]);

        return redirect()->route('admin.menus.index')
            ->with('success', __('Menu created successfully'));
    }

    public function edit(Menu $menu)
    {
        $menu->load(['items' => function($q) {
            $q->with('children')->whereNull('parent_id')->orderBy('order');
        }]);

        return view('admin.menus.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string'],
        ]);

        $menu->update([
            'name' => $request->name,
            'location' => $request->location,
        ]);

        return redirect()->route('admin.menus.index')
            ->with('success', __('Menu updated successfully'));
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return redirect()->route('admin.menus.index')
            ->with('success', __('Menu deleted successfully'));
    }

    public function saveItems(Request $request, Menu $menu)
    {
        $request->validate([
            'items' => ['array'],
        ]);

        // Delete existing items
        $menu->allItems()->delete();

        // Create new items
        if ($request->has('items')) {
            $this->saveMenuItems($menu, $request->items);
        }

        return response()->json(['success' => true]);
    }

    private function saveMenuItems(Menu $menu, array $items, $parentId = null, $order = 0)
    {
        foreach ($items as $index => $itemData) {
            $item = $menu->allItems()->create([
                'parent_id' => $parentId,
                'title' => $itemData['title'],
                'url' => $itemData['url'] ?? null,
                'type' => $itemData['type'] ?? 'custom',
                'reference_id' => $itemData['reference_id'] ?? null,
                'target' => $itemData['target'] ?? '_self',
                'icon' => $itemData['icon'] ?? null,
                'order' => $index,
                'is_active' => $itemData['is_active'] ?? true,
            ]);

            if (!empty($itemData['children'])) {
                $this->saveMenuItems($menu, $itemData['children'], $item->id, 0);
            }
        }
    }
}
