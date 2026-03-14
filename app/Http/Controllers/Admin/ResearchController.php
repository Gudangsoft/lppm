<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Language;
use App\Models\Research;
use App\Models\Researcher;
use App\Models\ResearchScheme;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ResearchController extends Controller
{
    public function index(Request $request)
    {
        $query = Research::with(['translations', 'scheme.translations', 'creator', 'team']);

        if ($request->filled('search')) {
            $query->whereHas('translations', function($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('scheme')) {
            $query->where('scheme_id', $request->scheme);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $researches = $query->latest()->paginate(15)->onEachSide(2);
        $schemes = ResearchScheme::active()->with('translations')->get();
        $years = Research::distinct()->pluck('year')->filter()->sort()->reverse();

        return view('admin.researches.index', compact('researches', 'schemes', 'years'));
    }

    public function create()
    {
        $languages = Language::active()->get();
        $schemes = ResearchScheme::active()->with('translations')->get();
        $categories = Category::ofType('research')->active()->with('translations')->get();
        $researchers = Researcher::active()->get();
        
        return view('admin.researches.create', compact('languages', 'schemes', 'categories', 'researchers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'slug' => ['required', 'string', 'max:255', 'unique:researches'],
            'translations' => ['required', 'array'],
            'translations.*.title' => ['required', 'string', 'max:255'],
        ]);

        $research = Research::create([
            'slug' => Str::slug($request->slug),
            'scheme_id' => $request->scheme_id,
            'category_id' => $request->category_id,
            'year' => $request->year,
            'budget' => $request->budget,
            'status' => $request->status ?? 'draft',
            'is_published' => $request->boolean('is_published'),
            'created_by' => auth()->id(),
        ]);

        if ($request->hasFile('featured_image')) {
            $research->update([
                'featured_image' => $request->file('featured_image')->store('researches', 'public')
            ]);
        }

        foreach ($request->translations as $locale => $data) {
            $research->translations()->create([
                'locale' => $locale,
                'title' => $data['title'],
                'abstract' => $data['abstract'] ?? null,
                'content' => $data['content'] ?? null,
                'keywords' => $data['keywords'] ?? null,
            ]);
        }

        // Sync team members
        if ($request->has('team')) {
            foreach ($request->team as $member) {
                $research->team()->attach($member['researcher_id'], ['role' => $member['role']]);
            }
        }

        return redirect()->route('admin.researches.index')
            ->with('success', __('Research created successfully'));
    }

    public function edit(Research $research)
    {
        $languages = Language::active()->get();
        $schemes = ResearchScheme::active()->with('translations')->get();
        $categories = Category::ofType('research')->active()->with('translations')->get();
        $researchers = Researcher::active()->get();
        $research->load(['translations', 'team']);
        
        return view('admin.researches.edit', compact('research', 'languages', 'schemes', 'categories', 'researchers'));
    }

    public function update(Request $request, Research $research)
    {
        $request->validate([
            'slug' => ['required', 'string', 'max:255', 'unique:researches,slug,' . $research->id],
            'translations' => ['required', 'array'],
            'translations.*.title' => ['required', 'string', 'max:255'],
        ]);

        $research->update([
            'slug' => Str::slug($request->slug),
            'scheme_id' => $request->scheme_id,
            'category_id' => $request->category_id,
            'year' => $request->year,
            'budget' => $request->budget,
            'status' => $request->status,
            'is_published' => $request->boolean('is_published'),
        ]);

        if ($request->hasFile('featured_image')) {
            if ($research->featured_image) {
                Storage::disk('public')->delete($research->featured_image);
            }
            $research->update([
                'featured_image' => $request->file('featured_image')->store('researches', 'public')
            ]);
        }

        foreach ($request->translations as $locale => $data) {
            $research->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title' => $data['title'],
                    'abstract' => $data['abstract'] ?? null,
                    'content' => $data['content'] ?? null,
                    'keywords' => $data['keywords'] ?? null,
                ]
            );
        }

        // Sync team members
        $research->team()->detach();
        if ($request->has('team')) {
            foreach ($request->team as $member) {
                $research->team()->attach($member['researcher_id'], ['role' => $member['role']]);
            }
        }

        return redirect()->route('admin.researches.index')
            ->with('success', __('Research updated successfully'));
    }

    public function destroy(Research $research)
    {
        if ($research->featured_image) {
            Storage::disk('public')->delete($research->featured_image);
        }
        
        $research->delete();

        return redirect()->route('admin.researches.index')
            ->with('success', __('Research deleted successfully'));
    }
}
