<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'مزيد — منصة التطوع للمحترفين' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Tajawal', sans-serif; }
        ::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased min-h-screen flex flex-col">

    {{-- Navbar --}}
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <nav class="container mx-auto px-4 md:px-8 py-3 flex items-center justify-between">
            {{-- Logo + Nav --}}
            <div class="flex items-center gap-6">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded-full bg-green-500 flex items-center justify-center text-white font-extrabold text-lg">م</div>
                    <span class="font-extrabold text-gray-800 text-xl hidden sm:block">مزيد</span>
                </a>
                <nav class="hidden md:flex items-center gap-6 text-sm font-semibold text-gray-600">
                    <a href="{{ route('home') }}" class="hover:text-green-600 transition {{ request()->routeIs('home') ? 'text-green-600 border-b-2 border-green-500 pb-1' : '' }}">الفرص</a>
                    @auth
                        <a href="{{ route('profile.show', auth()->user()) }}" class="hover:text-green-600 transition {{ request()->routeIs('profile.show') ? 'text-green-600 border-b-2 border-green-500 pb-1' : '' }}">ملفي</a>
                    @endauth
                </nav>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('projects.create') }}"
                       class="hidden sm:inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-sm font-bold px-4 py-2 rounded-lg transition">
                        <i class="fa-solid fa-plus text-xs"></i>
                        نشر فرصة
                    </a>
                    <a href="{{ route('profile.show', auth()->user()) }}" class="flex items-center gap-2">
                        @if(auth()->user()->avatar_url)
                            <img src="{{ auth()->user()->avatar_url }}" class="w-9 h-9 rounded-full object-cover border-2 border-green-200" alt="{{ auth()->user()->name }}">
                        @else
                            <div class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-sm">
                                {{ mb_substr(auth()->user()->name, 0, 1) }}
                            </div>
                        @endif
                        <span class="hidden sm:block text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                        @csrf
                        <button type="submit" class="text-sm text-gray-400 hover:text-gray-600 transition">خروج</button>
                    </form>
                @else
                    <a href="{{ route('auth.linkedin') }}"
                       class="flex items-center gap-2 border-2 border-green-500 text-green-600 hover:bg-green-50 text-sm font-bold px-4 py-2 rounded-full transition">
                        <i class="fa-brands fa-linkedin text-[#0A66C2]"></i>
                        تسجيل الدخول
                    </a>
                @endauth
            </div>
        </nav>
    </header>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="container mx-auto px-4 md:px-8 mt-4">
            <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm font-medium">
                {{ session('success') }}
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="container mx-auto px-4 md:px-8 mt-4">
            <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 text-sm font-medium">
                {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- Main Content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-white border-t border-gray-200 mt-16 py-8">
        <div class="container mx-auto px-4 md:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white font-extrabold text-sm">م</div>
                    <span class="text-xl font-extrabold text-gray-800">مزيد</span>
                </div>
                <p class="text-sm text-gray-500">منصة التطوع الأولى للمحترفين وأصحاب المشاريع</p>
                <div class="flex gap-4 text-gray-400">
                    <a href="#" class="hover:text-green-500 transition"><i class="fa-brands fa-twitter text-lg"></i></a>
                    <a href="#" class="hover:text-green-500 transition"><i class="fa-brands fa-linkedin text-lg"></i></a>
                    <a href="#" class="hover:text-green-500 transition"><i class="fa-brands fa-instagram text-lg"></i></a>
                </div>
            </div>
            <div class="border-t border-gray-100 mt-6 pt-6 text-center">
                <p class="text-gray-400 text-sm">© {{ date('Y') }} مزيد. جميع الحقوق محفوظة</p>
            </div>
        </div>
    </footer>

</body>
</html>
