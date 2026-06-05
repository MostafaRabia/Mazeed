<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $query = Project::with(['user', 'skills'])
            ->where('status', 'active');

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('skills')) {
            $skillIds = array_filter((array) $request->input('skills'));
            if ($skillIds) {
                $query->whereHas('skills', fn ($q) => $q->whereIn('skills.id', $skillIds));
            }
        }

        $projects = $query->latest()->paginate(12)->withQueryString();
        $skills = Skill::orderBy('name')->get();
        $selectedSkills = (array) $request->input('skills', []);

        return view('home', compact('projects', 'skills', 'selectedSkills'));
    }

    public function create(): View
    {
        $skills = Skill::orderBy('name')->get();

        return view('projects.create', compact('skills'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'contact_info' => ['required', 'string', 'max:255'],
            'skills' => ['array'],
            'skills.*' => ['integer', 'exists:skills,id'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('projects', 'public');
        }

        $project = Auth::user()->projects()->create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']).'-'.Str::random(6),
            'description' => $validated['description'],
            'contact_info' => $validated['contact_info'],
            'image' => $imagePath,
        ]);

        if (! empty($validated['skills'])) {
            $project->skills()->sync($validated['skills']);
        }

        return redirect()->route('projects.show', $project->slug)
            ->with('success', 'Project posted successfully!');
    }

    public function show(string $slug): View
    {
        $project = Project::with(['user', 'skills'])
            ->where('slug', $slug)
            ->firstOrFail();

        $isOwner = auth()->check() && auth()->id() === $project->user_id;

        return view('projects.show', compact('project', 'isOwner'));
    }
}
