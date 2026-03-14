<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::with(['translations', 'parent.translations']);

        if ($request->filled('type')) {
            $query->ofType($request->type);
        }

        if ($request->filled('search')) {
            $query->whereHas('translations', function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            });
        }

        $categories = $query->orderBy('order')->paginate(15)->onEachSide(2);
        $types = ['news', 'research', 'pengabdian', 'publication', 'gallery', 'download'];

        return view('admin.categories.index', compact('categories', 'types'));
    }

    public function create()
    {
        $languages = Language::active()->get();
        $categories = Category::with('translations')->get();
        $types = ['news', 'research', 'pengabdian', 'publication', 'gallery', 'download'];
        
        return view('admin.categories.create', compact('languages', 'categories', 'types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'slug' => ['required', 'string', 'max:255', 'unique:categories'],
            'type' => ['required', 'string'],
            'translations' => ['required', 'array'],
            'translations.*.name' => ['required', 'string', 'max:255'],
        ]);

        $category = Category::create([
            'slug' => Str::slug($request->slug),
            'type' => $request->type,
            'parent_id' => $request->parent_id,
            'is_active' => $request->boolean('is_active', true),
            'order' => $request->order ?? 0,
        ]);

        if ($request->hasFile('image')) {
            $category->update([
                'image' => $request->file('image')->store('categories', 'public')
            ]);
        }

        foreach ($request->translations as $locale => $data) {
            $category->translations()->create([
                'locale' => $locale,
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
            ]);
        }

        return redirect()->route('admin.categories.index')
            ->with('success', __('Category created successfully'));
    }

    public function edit(Category $category)
    {
        $languages = Language::active()->get();
        $categories = Category::where('id', '!=', $category->id)->with('translations')->get();
        $types = ['news', 'research', 'pengabdian', 'publication', 'gallery', 'download'];
        $category->load('translations');
        
        return view('admin.categories.edit', compact('category', 'languages', 'categories', 'types'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'slug' => ['required', 'string', 'max:255', 'unique:categories,slug,' . $category->id],
            'type' => ['required', 'string'],
            'translations' => ['required', 'array'],
            'translations.*.name' => ['required', 'string', 'max:255'],
        ]);

        $category->update([
            'slug' => Str::slug($request->slug),
            'type' => $request->type,
            'parent_id' => $request->parent_id,
            'is_active' => $request->boolean('is_active'),
            'order' => $request->order ?? 0,
        ]);

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $category->update([
                'image' => $request->file('image')->store('categories', 'public')
            ]);
        }

        foreach ($request->translations as $locale => $data) {
            $category->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'name' => $data['name'],
                    'description' => $data['description'] ?? null,
                ]
            );
        }

        return redirect()->route('admin.categories.index')
            ->with('success', __('Category updated successfully'));
    }

    public function destroy(Category $category)
    {
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', __('Category deleted successfully'));
    }
}
