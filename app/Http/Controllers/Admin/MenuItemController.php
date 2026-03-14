<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'menu_id' => ['required', 'exists:menus,id'],
            'url' => ['required', 'string', 'max:255'],
            'translations' => ['required', 'array'],
            'translations.*.title' => ['required', 'string', 'max:255'],
        ]);

        $maxOrder = MenuItem::where('menu_id', $request->menu_id)
            ->whereNull('parent_id')
            ->max('sort_order');

        $menuItem = MenuItem::create([
            'menu_id' => $request->menu_id,
            'parent_id' => $request->parent_id,
            'url' => $request->url,
            'target' => $request->target ?? '_self',
            'icon' => $request->icon,
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => ($maxOrder ?? 0) + 1,
        ]);

        foreach ($request->translations as $locale => $data) {
            $menuItem->translations()->create([
                'locale' => $locale,
                'title' => $data['title'],
            ]);
        }

        return redirect()->back()->with('success', 'Menu item created successfully');
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $request->validate([
            'url' => ['required', 'string', 'max:255'],
            'translations' => ['required', 'array'],
            'translations.*.title' => ['required', 'string', 'max:255'],
        ]);

        $menuItem->update([
            'parent_id' => $request->parent_id,
            'url' => $request->url,
            'target' => $request->target ?? '_self',
            'icon' => $request->icon,
            'is_active' => $request->boolean('is_active', true),
        ]);

        foreach ($request->translations as $locale => $data) {
            $menuItem->translations()->updateOrCreate(
                ['locale' => $locale],
                ['title' => $data['title']]
            );
        }

        return redirect()->back()->with('success', 'Menu item updated successfully');
    }

    public function destroy(MenuItem $menuItem)
    {
        // Delete children first
        $menuItem->children()->delete();
        $menuItem->translations()->delete();
        $menuItem->delete();

        return redirect()->back()->with('success', 'Menu item deleted successfully');
    }
}
