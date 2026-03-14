<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Language;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::with(['translations', 'category.translations', 'author']);

        if ($request->filled('search')) {
            $query->whereHas('translations', function($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } else {
                $query->where('is_published', false);
            }
        }

        $news = $query->latest()->paginate(15)->onEachSide(2);
        $categories = Category::where('type', 'news')->where('is_active', true)->with('translations')->get();

        return view('admin.news.index', compact('news', 'categories'));
    }

    public function create()
    {
        $languages = Language::where('is_active', true)->get();
        $categories = Category::where('type', 'news')->where('is_active', true)->with('translations')->get();
        
        return view('admin.news.create', compact('languages', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'slug' => ['required', 'string', 'max:255', 'unique:news'],
            'translations' => ['required', 'array'],
            'translations.*.title' => ['required', 'string', 'max:255'],
        ]);

        $news = News::create([
            'slug' => Str::slug($request->slug),
            'category_id' => $request->category_id,
            'is_featured' => $request->boolean('is_featured'),
            'is_published' => $request->boolean('is_published'),
            'published_at' => $request->published_at ?? now(),
            'created_by' => auth()->id(),
        ]);

        if ($request->hasFile('featured_image')) {
            $news->update([
                'featured_image' => $request->file('featured_image')->store('news', 'public')
            ]);
        }

        foreach ($request->translations as $locale => $data) {
            $news->translations()->create([
                'locale' => $locale,
                'title' => $data['title'],
                'excerpt' => $data['excerpt'] ?? null,
                'content' => $data['content'] ?? null,
                'meta_title' => $data['meta_title'] ?? null,
                'meta_description' => $data['meta_description'] ?? null,
            ]);
        }

        return redirect()->route('admin.news.index')
            ->with('success', __('News created successfully'));
    }

    public function edit(News $news)
    {
        $languages = Language::where('is_active', true)->get();
        $categories = Category::where('type', 'news')->where('is_active', true)->with('translations')->get();
        $news->load('translations');
        
        return view('admin.news.create', compact('news', 'languages', 'categories'));
    }

    public function update(Request $request, News $news)
    {
        $request->validate([
            'slug' => ['required', 'string', 'max:255', 'unique:news,slug,' . $news->id],
            'translations' => ['required', 'array'],
            'translations.*.title' => ['required', 'string', 'max:255'],
        ]);

        $news->update([
            'slug' => Str::slug($request->slug),
            'category_id' => $request->category_id,
            'is_featured' => $request->boolean('is_featured'),
            'is_published' => $request->boolean('is_published'),
            'published_at' => $request->published_at,
        ]);

        if ($request->hasFile('featured_image')) {
            if ($news->featured_image) {
                Storage::disk('public')->delete($news->featured_image);
            }
            $news->update([
                'featured_image' => $request->file('featured_image')->store('news', 'public')
            ]);
        }

        foreach ($request->translations as $locale => $data) {
            $news->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title' => $data['title'],
                    'excerpt' => $data['excerpt'] ?? null,
                    'content' => $data['content'] ?? null,
                    'meta_title' => $data['meta_title'] ?? null,
                    'meta_description' => $data['meta_description'] ?? null,
                ]
            );
        }

        return redirect()->route('admin.news.index')
            ->with('success', __('News updated successfully'));
    }

    public function destroy(News $news)
    {
        if ($news->featured_image) {
            Storage::disk('public')->delete($news->featured_image);
        }
        
        $news->delete();

        return redirect()->route('admin.news.index')
            ->with('success', __('News deleted successfully'));
    }
}
