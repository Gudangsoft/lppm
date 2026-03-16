<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Page;
use App\Models\PageTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $query = Page::with('translations');

        if ($request->filled('search')) {
            $query->whereHas('translations', function($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%");
            });
        }

        $pages = $query->orderBy('order')->paginate(15)->onEachSide(2);

        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        $languages = Language::where('is_active', true)->get();
        return view('admin.pages.create', compact('languages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'slug' => ['required', 'string', 'max:255', 'unique:pages'],
            'translations' => ['required', 'array'],
            'translations.*.title' => ['required', 'string', 'max:255'],
        ]);

        $page = Page::create([
            'slug' => Str::slug($request->slug),
            'template' => $request->template ?? 'default',
            'is_published' => $request->boolean('is_published'),
            'order' => $request->sort_order ?? $request->order ?? 0,
            'created_by' => auth()->id(),
        ]);

        if ($request->hasFile('featured_image')) {
            $page->update([
                'featured_image' => $request->file('featured_image')->store('pages', 'public')
            ]);
        }

        foreach ($request->translations as $locale => $data) {
            $page->translations()->create([
                'locale' => $locale,
                'title' => $data['title'],
                'content' => $data['content'] ?? null,
                'meta_title' => $data['meta_title'] ?? null,
                'meta_description' => $data['meta_description'] ?? null,
            ]);
        }

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page created successfully');
    }

    public function edit(Page $page)
    {
        $languages = Language::where('is_active', true)->get();
        $page->load('translations');
        
        return view('admin.pages.create', compact('page', 'languages'));
    }

    public function update(Request $request, Page $page)
    {
        $request->validate([
            'slug' => ['required', 'string', 'max:255', 'unique:pages,slug,' . $page->id],
            'template' => ['required', 'string'],
            'translations' => ['required', 'array'],
            'translations.*.title' => ['required', 'string', 'max:255'],
        ]);

        $page->update([
            'slug' => Str::slug($request->slug),
            'template' => $request->template,
            'is_published' => $request->boolean('is_published'),
            'order' => $request->order ?? 0,
        ]);

        if ($request->hasFile('featured_image')) {
            if ($page->featured_image) {
                Storage::disk('public')->delete($page->featured_image);
            }
            $page->update([
                'featured_image' => $request->file('featured_image')->store('pages', 'public')
            ]);
        }

        foreach ($request->translations as $locale => $data) {
            $page->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title' => $data['title'],
                    'content' => $data['content'] ?? null,
                    'meta_title' => $data['meta_title'] ?? null,
                    'meta_description' => $data['meta_description'] ?? null,
                ]
            );
        }

        return redirect()->route('admin.pages.index')
            ->with('success', __('Page updated successfully'));
    }

    public function destroy(Page $page)
    {
        if ($page->featured_image) {
            Storage::disk('public')->delete($page->featured_image);
        }
        
        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', __('Page deleted successfully'));
    }
}
