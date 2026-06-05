@extends('layouts.app')

@section('content')
<main class="container mx-auto px-4 md:px-8 py-8">
    <div class="max-w-3xl mx-auto">

        {{-- LinkedIn Token Warning (for current user) --}}
        @if(auth()->id() === $user->id && $user->linkedin_id && !$user->linkedin_token_expires_at)
            <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-4 flex items-start gap-3">
                <i class="fa-solid fa-exclamation-circle text-yellow-600 mt-0.5 shrink-0"></i>
                <div class="flex-1">
                    <p class="font-semibold text-yellow-900 mb-1">تنبيه: توصيل LinkedIn يحتاج تحديث</p>
                    <p class="text-sm text-yellow-800 mb-2">يرجى إعادة ربط حساب LinkedIn الخاص بك لتتمكن من مشاركة الشارات على LinkedIn.</p>
                    <a href="{{ route('auth.linkedin') }}"
                       class="inline-flex items-center gap-1.5 text-sm border border-yellow-400 hover:border-yellow-500 bg-yellow-100 hover:bg-yellow-200 text-yellow-800 px-3 py-1.5 rounded-lg transition font-medium">
                        <i class="fa-brands fa-linkedin text-xs"></i>
                        تحديث LinkedIn
                    </a>
                </div>
            </div>
        @endif

        {{-- Profile Header --}}
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-6">
            <div class="h-24 bg-gradient-to-r from-green-500 to-green-700"></div>
            <div class="px-6 pb-6">
                <div class="flex items-end gap-4 -mt-10 mb-4">
                    @if($user->avatar_url)
                        <img src="{{ $user->avatar_url }}" class="w-20 h-20 rounded-full object-cover border-4 border-white shadow" alt="{{ $user->name }}">
                    @else
                        <div class="w-20 h-20 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-extrabold text-3xl border-4 border-white shadow">
                            {{ mb_substr($user->name, 0, 1) }}
                        </div>
                    @endif
                    <div class="pb-1 flex items-center gap-3">
                        @if(auth()->id() === $user->id)
                            <a href="{{ route('profile.edit') }}"
                               class="inline-flex items-center gap-1 text-sm border border-gray-200 hover:border-green-400 text-gray-500 hover:text-green-600 px-3 py-1.5 rounded-lg transition">
                                <i class="fa-solid fa-pen text-xs"></i>
                                تعديل الملف
                            </a>
                        @endif
                        @if($user->linkedin_profile_url)
                            <a href="{{ $user->linkedin_profile_url }}" target="_blank" rel="noopener noreferrer"
                               class="text-[#0A66C2] hover:opacity-75 transition">
                                <i class="fa-brands fa-linkedin text-xl"></i>
                            </a>
                        @elseif(auth()->id() === $user->id)
                            <a href="{{ route('auth.linkedin') }}"
                               class="inline-flex items-center gap-1 text-sm border border-blue-200 hover:border-blue-400 text-blue-600 hover:text-blue-700 px-3 py-1.5 rounded-lg transition bg-blue-50 hover:bg-blue-100">
                                <i class="fa-brands fa-linkedin text-xs"></i>
                                ربط LinkedIn
                            </a>
                        @endif
                    </div>
                </div>

                <h1 class="text-2xl font-extrabold text-gray-900">{{ $user->name }}</h1>
                @if($user->headline)
                    <p class="text-gray-500 mt-0.5 text-sm">{{ $user->headline }}</p>
                @endif
                @if($user->bio)
                    <p class="text-gray-700 mt-3 text-sm leading-relaxed">{{ $user->bio }}</p>
                @endif

                @if($user->skills->isNotEmpty())
                    <div class="flex flex-wrap gap-2 mt-4">
                        @foreach($user->skills as $skill)
                            <span class="bg-green-50 text-green-700 border border-green-200 text-xs px-2.5 py-1 rounded-full font-medium">
                                {{ $skill->name }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Posted Projects --}}
        @if($user->projects->isNotEmpty())
            <h2 class="text-lg font-extrabold text-gray-800 mb-4">فرص {{ $user->name }}</h2>
            <div class="space-y-4">
                @foreach($user->projects as $project)
                    <a href="{{ route('projects.show', $project->slug) }}"
                       class="flex gap-4 bg-white rounded-xl shadow-sm hover:shadow-md transition p-4 border border-transparent hover:border-green-200">
                        @if($project->image)
                            <img src="{{ Storage::url($project->image) }}" class="w-20 h-20 rounded-lg object-cover shrink-0" alt="{{ $project->title }}">
                        @else
                            <div class="w-20 h-20 rounded-lg bg-green-50 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-briefcase text-2xl text-green-300"></i>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="font-extrabold text-gray-800 truncate">{{ $project->title }}</h3>
                                <span class="shrink-0 text-xs px-2 py-0.5 rounded-full
                                    {{ $project->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $project->status === 'active' ? 'متاح' : 'مغلق' }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 line-clamp-2">{{ $project->description }}</p>
                            @if($project->skills->isNotEmpty())
                                <div class="flex flex-wrap gap-1 mt-2">
                                    @foreach($project->skills->take(3) as $skill)
                                        <span class="text-xs bg-green-50 text-green-600 px-2 py-0.5 rounded-full border border-green-100">{{ $skill->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-10 text-gray-400">
                <i class="fa-solid fa-folder-open text-4xl mb-3 block opacity-30"></i>
                <p class="text-sm">لا توجد فرص منشورة بعد.</p>
            </div>
        @endif
    </div>
</main>
@endsection
