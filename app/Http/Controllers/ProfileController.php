<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(User $user): View
    {
        $user->load(['skills', 'projects.skills']);

        return view('profile.show', compact('user'));
    }

    public function edit(): View
    {
        $user = Auth::user();
        $skills = Skill::orderBy('name')->get();
        $userSkillIds = $user->skills->pluck('id')->toArray();

        return view('profile.edit', compact('user', 'skills', 'userSkillIds'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'bio' => ['nullable', 'string', 'max:1000'],
            'headline' => ['nullable', 'string', 'max:255'],
            'skills' => ['array'],
            'skills.*' => ['integer', 'exists:skills,id'],
        ]);

        $user = Auth::user();
        $user->update([
            'bio' => $validated['bio'],
            'headline' => $validated['headline'],
        ]);

        $user->skills()->sync($validated['skills'] ?? []);

        return redirect()->route('profile.show', $user)
            ->with('success', 'Profile updated!');
    }
}
