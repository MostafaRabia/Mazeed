@extends('layouts.app')

@section('content')
<main class="container mx-auto px-4 md:px-8 py-8">
    <div class="max-w-2xl mx-auto">

        {{-- Form Header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-extrabold text-gray-800 mb-1">إضافة فرصة تطوع جديدة</h1>
            <p class="text-gray-500 text-sm">شارك فرصتك واعثر على متطوعين ذوي الكفاءة المناسبة</p>
        </div>

        <form method="POST" action="{{ route('projects.store') }}" enctype="multipart/form-data"
              class="bg-white rounded-2xl shadow-sm p-8 space-y-6">
            @csrf

            {{-- Title --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                    عنوان الفرصة <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title') }}"
                       placeholder="مثال: مصمم واجهات مستخدم لمنصة تعليمية"
                       class="w-full py-3 px-4 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm @error('title') border-red-400 @enderror">
                @error('title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                    وصف الفرصة <span class="text-red-500">*</span>
                </label>
                <textarea name="description" rows="5"
                          placeholder="اشرح تفاصيل الفرصة وما تحتاجه من المتطوع..."
                          class="w-full py-3 px-4 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm resize-none @error('description') border-red-400 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Skills --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">المهارات المطلوبة</label>
                <div class="flex flex-wrap gap-2">
                    @foreach($skills as $skill)
                        <label class="cursor-pointer">
                            <input type="checkbox" name="skills[]" value="{{ $skill->id }}"
                                   class="sr-only peer"
                                   {{ in_array($skill->id, (array) old('skills', [])) ? 'checked' : '' }}>
                            <span class="inline-block px-3 py-1.5 rounded-full text-sm border border-gray-200 bg-gray-50 text-gray-600 peer-checked:bg-green-500 peer-checked:border-green-500 peer-checked:text-white hover:border-green-400 transition cursor-pointer">
                                {{ $skill->name }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Contact --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                    رابط التواصل <span class="text-red-500">*</span>
                </label>
                <input type="text" name="contact_info" value="{{ old('contact_info', auth()->user()->linkedin_profile_url) }}"
                       placeholder="https://linkedin.com/in/username أو أي رابط تواصل"
                       class="w-full py-3 px-4 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm @error('contact_info') border-red-400 @enderror">
                <p class="text-gray-400 text-xs mt-1">سيتواصل المتطوعون معك عبر هذا الرابط</p>
                @error('contact_info')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Image --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                    صورة الفرصة <span class="text-gray-400 font-normal">(اختياري)</span>
                </label>
                <input type="file" name="image" accept="image/*"
                       class="block w-full text-sm text-gray-500 file:ml-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 cursor-pointer @error('image') border border-red-400 rounded-lg @enderror">
                @error('image')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="bg-green-500 hover:bg-green-600 text-white font-bold px-6 py-3 rounded-lg transition text-sm">
                    نشر الفرصة
                </button>
                <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-gray-700">إلغاء</a>
            </div>
        </form>
    </div>
</main>
@endsection
