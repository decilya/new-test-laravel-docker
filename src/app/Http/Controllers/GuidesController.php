<?php

namespace App\Http\Controllers;

use App\Models\Guide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class GuidesController extends Controller
{
    public function index(Request $request)
    {
        $minExperience = $request->query('min_experience');
        $query = Guide::query();

        if ($minExperience) {
            $query->where('experience_years', '>=', $minExperience);
        }

        $guides = $query->latest()->paginate(10);

        return view('guides.index', compact('guides', 'minExperience'));
    }

    public function create()
    {
        return view('guides.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0',
            'is_active' => 'boolean'
        ]);

        Guide::create($validatedData);

        return redirect()
            ->route('guides.index')
            ->with('success', 'Гид успешно добавлен');
    }

    public function show(Guide $guide)
    {
        return view('guides.show', compact('guide'));
    }

    public function edit(Guide $guide)
    {
        return view('guides.edit', compact('guide'));
    }

    public function update(Request $request, Guide $guide)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0',
            'is_active' => 'boolean'
        ]);

        $guide->update($validatedData);

        return redirect()
            ->route('guides.index')
            ->with('success', 'Гид успешно обновлен');
    }

    public function destroy(Guide $guide)
    {
        $guide->delete();

        return Redirect::route('guides.index')
            ->with('success', 'Гид успешно удален');
    }

}

