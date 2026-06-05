@extends('layouts.app')

@section('content')

{{-- Page Title --}}
<div class="container mx-auto px-4 md:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-800 mb-1">استكشف الفرص</h1>
        <p class="text-gray-500">ابحث عن فرص التطوع المناسبة لمهاراتك واهتماماتك</p>
    </div>

    {{-- Filter Bar --}}
    <form method="GET" action="{{ route('home') }}"
          class="bg-white rounded-xl shadow-sm p-4 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Search --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">عنوان الفرصة</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="ابحث عن فرصة..."
                           class="w-full py-3 px-4 pl-10 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                </div>
            </div>

            {{-- Skills --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">المهارات</label>
                <div class="relative">
                    <select name="skills[]" multiple
                            class="w-full py-3 px-4 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm appearance-none">
                        <option value="">جميع المهارات</option>
                        @foreach($skills as $skill)
                            <option value="{{ $skill->id }}" {{ in_array($skill->id, $selectedSkills) ? 'selected' : '' }}>
                                {{ $skill->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex items-end gap-2">
                <button type="submit"
                        class="flex-1 bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 rounded-lg transition text-sm">
                    بحث
                </button>
                @if(request()->hasAny(['search', 'skills']))
                    <a href="{{ route('home') }}"
                       class="py-3 px-4 border border-gray-200 rounded-lg text-gray-500 hover:bg-gray-50 text-sm transition whitespace-nowrap">
                        مسح
                    </a>
                @endif
            </div>
        </div>
    </form>

    {{-- Projects Grid --}}
    @if($projects->isEmpty())
        <div class="text-center py-20 text-gray-400">
            <i class="fa-solid fa-folder-open text-5xl mb-4 block opacity-30"></i>
            <p class="text-lg font-bold">لا توجد فرص حالياً</p>
            <p class="text-sm mt-1">كن أول من ينشر فرصة تطوع!</p>
            @auth
                <a href="{{ route('projects.create') }}"
                   class="inline-flex items-center gap-2 mt-4 bg-green-500 hover:bg-green-600 text-white font-bold px-5 py-2.5 rounded-lg transition text-sm">
                    نشر فرصة
                </a>
            @endauth
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($projects as $project)
                <div class="bg-white rounded-xl shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md flex flex-col">
                    @if($project->image)
                        <img src="{{ Storage::url($project->image) }}" alt="{{ $project->title }}"
                             class="w-full h-44 object-cover">
                    @else
                        <div class="w-full h-44 bg-gradient-to-br from-green-50 to-green-100 flex items-center justify-center">
                            <i class="fa-solid fa-briefcase text-4xl text-green-300"></i>
                        </div>
                    @endif
                    <div class="p-5 flex flex-col flex-1">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="text-lg font-extrabold text-gray-800 leading-tight line-clamp-2 flex-1">
                                {{ $project->title }}
                            </h3>
                            <span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-1 rounded-full mr-2 shrink-0">
                                عن بعد
                            </span>
                        </div>

                        <p class="text-sm text-gray-500 mb-2">
                            <i class="fa-solid fa-user ml-1"></i>{{ $project->user->name }}
                        </p>

                        <p class="text-gray-600 text-sm mb-4 line-clamp-3 flex-1">
                            {{ $project->description }}
                        </p>

                        @if($project->skills->isNotEmpty())
                            <div class="flex flex-wrap gap-1.5 mb-4">
                                @foreach($project->skills->take(3) as $skill)
                                    <span class="bg-gray-100 text-gray-600 text-xs px-2.5 py-1 rounded-full">
                                        {{ $skill->name }}
                                    </span>
                                @endforeach
                                @if($project->skills->count() > 3)
                                    <span class="text-gray-400 text-xs self-center">+{{ $project->skills->count() - 3 }}</span>
                                @endif
                            </div>
                        @endif

                        <a href="{{ route('projects.show', $project->slug) }}"
                           class="block w-full bg-green-500 hover:bg-green-600 text-white text-center font-bold py-2.5 px-4 rounded-lg transition text-sm">
                            عرض التفاصيل
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($projects->hasPages())
            <div class="flex justify-center">
                {{ $projects->links() }}
            </div>
        @endif
    @endif
</div>

@endsection

