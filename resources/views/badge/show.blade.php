@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 md:px-8 py-16 text-center">
    <div class="max-w-xl mx-auto">

        <div class="mb-6">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                <i class="fa-solid fa-certificate text-3xl text-green-600"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-gray-800 mb-2">أهلاً بك في مزيد!</h1>
            <p class="text-gray-500 text-sm">
                أنت الآن جزء من مجتمعنا. شارك شارة تطوعك على LinkedIn ليعلم شبكتك بانضمامك!
            </p>
        </div>

        {{-- Badge Preview --}}
        <div class="rounded-2xl overflow-hidden border border-gray-200 shadow-lg mb-8">
            <img src="{{ $badgeUrl }}" alt="شارة متطوع مزيد" class="w-full">
        </div>

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row-reverse gap-3 justify-center">
            <form method="POST" action="{{ route('badge.share') }}">
                @csrf
                <button type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-[#0A66C2] hover:bg-[#004182] text-white font-bold px-6 py-3 rounded-xl transition text-sm shadow">
                    <i class="fa-brands fa-linkedin"></i>
                    مشاركة على LinkedIn
                </button>
            </form>

            <form method="POST" action="{{ route('badge.skip') }}">
                @csrf
                <button type="submit"
                        class="w-full sm:w-auto text-sm text-gray-500 hover:text-gray-700 px-6 py-3 rounded-xl border border-gray-200 hover:border-gray-300 transition">
                    تخطي الآن
                </button>
            </form>
        </div>

        <p class="text-xs text-gray-400 mt-4">
            يمكنك دائماً مشاركة الشارة لاحقاً من ملفك الشخصي.
        </p>
    </div>
</div>
@endsection
