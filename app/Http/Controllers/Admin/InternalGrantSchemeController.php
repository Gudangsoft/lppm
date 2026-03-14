<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternalGrantScheme;
use Illuminate\Http\Request;

class InternalGrantSchemeController extends Controller
{
    public function index()
    {
        $schemes = InternalGrantScheme::withCount('periods')->latest()->paginate(15);
        return view('admin.internal-grants.schemes.index', compact('schemes'));
    }

    public function create()
    {
        return view('admin.internal-grants.schemes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'max:20', 'unique:internal_grant_schemes'],
            'name' => ['required', 'string', 'max:255'],
            'max_budget' => ['nullable', 'numeric', 'min:0'],
            'max_duration_months' => ['required', 'integer', 'min:1'],
        ]);

        InternalGrantScheme::create($request->all());

        return redirect()->route('admin.internal-grants.schemes.index')
            ->with('success', 'Skema hibah berhasil dibuat');
    }

    public function edit(InternalGrantScheme $scheme)
    {
        return view('admin.internal-grants.schemes.edit', compact('scheme'));
    }

    public function update(Request $request, InternalGrantScheme $scheme)
    {
        $request->validate([
            'code' => ['required', 'string', 'max:20', 'unique:internal_grant_schemes,code,' . $scheme->id],
            'name' => ['required', 'string', 'max:255'],
            'max_budget' => ['nullable', 'numeric', 'min:0'],
            'max_duration_months' => ['required', 'integer', 'min:1'],
        ]);

        $scheme->update($request->all());

        return redirect()->route('admin.internal-grants.schemes.index')
            ->with('success', 'Skema hibah berhasil diperbarui');
    }

    public function destroy(InternalGrantScheme $scheme)
    {
        $scheme->delete();

        return redirect()->route('admin.internal-grants.schemes.index')
            ->with('success', 'Skema hibah berhasil dihapus');
    }
}
