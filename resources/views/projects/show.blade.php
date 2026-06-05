@extends('layouts.app')

@section('content')
<main class="container mx-auto py-8 px-4 md:px-8">

    {{-- Back --}}
    <div class="mb-6">
        <a href="{{ route('home') }}" class="inline-flex items-center text-green-600 hover:text-green-700 transition font-semibold text-sm">
            <i class="fa-solid fa-arrow-right ml-2"></i>
            العودة إلى قائمة الفرص
        </a>
    </div>

    {{-- Project Header --}}
    <div class="bg-white rounded-2xl shadow-md p-6 mb-6">
        <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-3">
                    <span class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1 rounded-full
                        {{ $project->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $project->status === 'active' ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                        {{ $project->status === 'active' ? 'متاح' : 'مغلق' }}
                    </span>
                    <span class="text-xs text-gray-400">{{ $project->created_at->format('d M Y') }}</span>
                </div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-800 mb-4">{{ $project->title }}</h1>
            </div>
        </div>

        <div class="flex flex-col md:flex-row md:items-center justify-between mb-5">
            <div class="flex items-center gap-3 mb-3 md:mb-0">
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-briefcase text-green-600"></i>
                </div>
                <div>
                    <span class="text-gray-500 text-sm">صاحب المشروع</span>
                    <p class="font-bold text-gray-800">
                        <a href="{{ route('profile.show', $project->user) }}" class="hover:text-green-600 transition">
                            {{ $project->user->name }}
                        </a>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-4 text-sm text-gray-500">
                <span><i class="fa-regular fa-clock ml-1"></i>عن بعد</span>
            </div>
        </div>

        @if($project->skills->isNotEmpty())
            <div class="flex flex-wrap gap-2">
                @foreach($project->skills as $skill)
                    <span class="bg-green-50 text-green-700 px-3 py-1 rounded-full text-sm font-medium">
                        {{ $skill->name }}
                    </span>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Description --}}
    <div class="bg-white rounded-2xl shadow-md p-6 mb-6">
        <h2 class="text-xl font-extrabold text-gray-800 mb-4">وصف الفرصة</h2>
        <div class="text-gray-700 leading-relaxed whitespace-pre-line">
            {{ $project->description }}
        </div>
    </div>

    {{-- Contact / Volunteer --}}
    <div class="bg-green-50 rounded-2xl shadow-md p-6 mb-6">
        <h2 class="text-xl font-extrabold text-gray-800 mb-3">تواصل مع صاحب المشروع</h2>

        @if($project->status !== 'active')
            <p class="text-gray-500 text-sm italic">هذه الفرصة مغلقة ولا تقبل متطوعين حالياً.</p>
        @elseif($isOwner)
            <div class="flex items-center gap-2 text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-4 py-3 text-sm">
                <i class="fa-solid fa-circle-info"></i>
                هذه فرصتك الخاصة — لا يمكنك التطوع فيها.
            </div>
        @else
            <p class="text-gray-700 mb-5 text-sm leading-relaxed">
                هذه فرصة مميزة للمساهمة في مشروع مؤثر. للتقديم على هذه الفرصة، يرجى التواصل مع صاحب المشروع مباشرة.
            </p>
            <div class="flex justify-center">
                @php
                    $contactInfo = $project->contact_info;
                    $isUrl = str_starts_with($contactInfo, 'http://') || str_starts_with($contactInfo, 'https://');
                @endphp
                @if($isUrl)
                    <a href="{{ $contactInfo }}" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-xl transition">
                        <i class="fa-solid fa-paper-plane"></i>
                        تقديم
                    </a>
                @else
                    <div class="text-center">
                        <p class="text-gray-500 text-sm mb-2">تواصل عبر:</p>
                        <span class="inline-block bg-white border border-green-200 text-gray-800 font-bold py-3 px-6 rounded-xl text-sm select-all">
                            {{ $contactInfo }}
                        </span>
                    </div>
                @endif
            </div>
        @endif
    </div>

    {{-- Back bottom --}}
    <div class="flex justify-center">
        <a href="{{ route('home') }}"
           class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 font-bold py-3 px-6 rounded-xl shadow-sm transition border border-gray-200 text-sm">
            <i class="fa-solid fa-arrow-right"></i>
            العودة إلى قائمة الفرص
        </a>
    </div>
</main>
@endsection
