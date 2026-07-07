<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminResourceController extends Controller
{
    public function index()
    {
        $resources = Resource::with('university')->orderBy('created_at', 'desc')->get();
        $universities = University::orderBy('name')->get();
        return view('admin.resources.index', compact('resources', 'universities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'url' => ['required', 'url', 'max:2048'],
            'subject_tag' => ['required', 'string', 'max:255'],
            'university_id' => ['nullable', 'exists:universities,id'],
        ]);

        Resource::create($validated);

        return redirect()->route('admin.resources')->with('success', 'Resource added successfully.');
    }

    public function update(Request $request, Resource $resource)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'url' => ['required', 'url', 'max:2048'],
            'subject_tag' => ['required', 'string', 'max:255'],
            'university_id' => ['nullable', 'exists:universities,id'],
        ]);

        $resource->update($validated);

        return redirect()->route('admin.resources')->with('success', 'Resource updated successfully.');
    }

    public function destroy(Resource $resource)
    {
        $resource->delete();

        return redirect()->route('admin.resources')->with('success', 'Resource deleted successfully.');
    }
}
