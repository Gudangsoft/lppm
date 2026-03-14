<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function index()
    {
        $languages = Language::orderBy('order')->get();
        return view('admin.languages.index', compact('languages'));
    }

    public function create()
    {
        return view('admin.languages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'max:5', 'unique:languages'],
            'name' => ['required', 'string', 'max:255'],
            'native_name' => ['required', 'string', 'max:255'],
        ]);

        $language = Language::create([
            'code' => $request->code,
            'name' => $request->name,
            'native_name' => $request->native_name,
            'flag' => $request->flag,
            'is_default' => $request->boolean('is_default'),
            'is_active' => $request->boolean('is_active', true),
            'order' => $request->order ?? 0,
        ]);

        if ($request->boolean('is_default')) {
            Language::where('id', '!=', $language->id)->update(['is_default' => false]);
        }

        return redirect()->route('admin.languages.index')
            ->with('success', __('Language created successfully'));
    }

    public function edit(Language $language)
    {
        return view('admin.languages.edit', compact('language'));
    }

    public function update(Request $request, Language $language)
    {
        $request->validate([
            'code' => ['required', 'string', 'max:5', 'unique:languages,code,' . $language->id],
            'name' => ['required', 'string', 'max:255'],
            'native_name' => ['required', 'string', 'max:255'],
        ]);

        $language->update([
            'code' => $request->code,
            'name' => $request->name,
            'native_name' => $request->native_name,
            'flag' => $request->flag,
            'is_default' => $request->boolean('is_default'),
            'is_active' => $request->boolean('is_active'),
            'order' => $request->order ?? 0,
        ]);

        if ($request->boolean('is_default')) {
            Language::where('id', '!=', $language->id)->update(['is_default' => false]);
        }

        return redirect()->route('admin.languages.index')
            ->with('success', __('Language updated successfully'));
    }

    public function destroy(Language $language)
    {
        if ($language->is_default) {
            return redirect()->back()
                ->with('error', __('Cannot delete default language'));
        }

        $language->delete();

        return redirect()->route('admin.languages.index')
            ->with('success', __('Language deleted successfully'));
    }
}
