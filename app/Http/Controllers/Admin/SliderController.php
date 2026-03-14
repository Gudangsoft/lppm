<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::with('translations')->orderBy('order')->paginate(12);
        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        $languages = Language::active()->get();
        return view('admin.sliders.create', compact('languages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'max:2048'],
        ]);

        $slider = Slider::create([
            'image' => $request->file('image')->store('sliders', 'public'),
            'url' => $request->url,
            'target' => $request->target ?? '_self',
            'is_active' => $request->boolean('is_active', true),
            'order' => $request->order ?? 0,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        if ($request->has('translations')) {
            foreach ($request->translations as $locale => $data) {
                $slider->translations()->create([
                    'locale' => $locale,
                    'title' => $data['title'] ?? null,
                    'subtitle' => $data['subtitle'] ?? null,
                    'button_text' => $data['button_text'] ?? null,
                ]);
            }
        }

        return redirect()->route('admin.sliders.index')
            ->with('success', __('Slider created successfully'));
    }

    public function edit(Slider $slider)
    {
        $languages = Language::active()->get();
        $slider->load('translations');
        
        return view('admin.sliders.edit', compact('slider', 'languages'));
    }

    public function update(Request $request, Slider $slider)
    {
        $request->validate([
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($slider->image);
            $slider->update([
                'image' => $request->file('image')->store('sliders', 'public')
            ]);
        }

        $slider->update([
            'url' => $request->url,
            'target' => $request->target ?? '_self',
            'is_active' => $request->boolean('is_active'),
            'order' => $request->order ?? 0,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        if ($request->has('translations')) {
            foreach ($request->translations as $locale => $data) {
                $slider->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'title' => $data['title'] ?? null,
                        'subtitle' => $data['subtitle'] ?? null,
                        'button_text' => $data['button_text'] ?? null,
                    ]
                );
            }
        }

        return redirect()->route('admin.sliders.index')
            ->with('success', __('Slider updated successfully'));
    }

    public function destroy(Slider $slider)
    {
        Storage::disk('public')->delete($slider->image);
        $slider->delete();

        return redirect()->route('admin.sliders.index')
            ->with('success', __('Slider deleted successfully'));
    }

    public function updateOrder(Request $request)
    {
        foreach ($request->order as $index => $id) {
            Slider::where('id', $id)->update(['order' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
