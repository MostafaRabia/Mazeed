@extends('layouts.app')

@section('content')
<main class="container mx-auto px-4 md:px-8 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-extrabold text-gray-800">تعديل الملف الشخصي</h1>
        </div>

        <form method="POST" action="{{ route('profile.update') }}"
              class="bg-white rounded-2xl shadow-sm p-8 space-y-6">
            @csrf
            @method('PUT')

            {{-- Headline --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">المسمى الوظيفي</label>
                <input type="text" name="headline" value="{{ old('headline', $user->headline) }}"
                       placeholder="مثال: مطور ويب | مهتم بالبرمجيات مفتوحة المصدر"
                       class="w-full py-3 px-4 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm @error('headline') border-red-400 @enderror">
                @error('headline')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Bio --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">نبذة شخصية</label>
                <textarea name="bio" rows="4"
                          placeholder="أخبرنا عن نفسك وما يمكنك تقديمه للمشاريع..."
                          class="w-full py-3 px-4 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm resize-none @error('bio') border-red-400 @enderror">{{ old('bio', $user->bio) }}</textarea>
                @error('bio')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Skills --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">مهاراتك</label>
                <div class="flex flex-wrap gap-2">
                    @foreach($skills as $skill)
                        <label class="cursor-pointer">
                            <input type="checkbox" name="skills[]" value="{{ $skill->id }}"
                                   class="sr-only peer"
                                   {{ in_array($skill->id, old('skills', $userSkillIds)) ? 'checked' : '' }}>
                            <span class="inline-block px-3 py-1.5 rounded-full text-sm border border-gray-200 bg-gray-50 text-gray-600 peer-checked:bg-green-500 peer-checked:border-green-500 peer-checked:text-white hover:border-green-400 transition cursor-pointer">
                                {{ $skill->name }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="bg-green-500 hover:bg-green-600 text-white font-bold px-6 py-3 rounded-lg transition text-sm">
                    حفظ التغييرات
                </button>
                <a href="{{ route('profile.show', auth()->user()) }}" class="text-sm text-gray-500 hover:text-gray-700">إلغاء</a>
            </div>
        </form>
    </div>
</main>
@endsection
