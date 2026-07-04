<!-- Header Section -->
<nav class="fixed top-0 left-0 w-full h-[70px] bg-brand-dark border-b border-gray-800 px-6 flex items-center z-[9999]">
    <div class="max-w-6xl w-full mx-auto flex items-center justify-between">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex items-center gap-3 no-underline">
            <div class="flex items-center gap-2.5">
                <img src="{{ asset('images/apple-touch-icon.png') }}" class="w-9 h-9 object-contain" alt="Logo Edukan2">
                <span class="font-display font-black text-xl tracking-wider text-white">
                    Edukan<span class="text-brand-accent">2</span>
                </span>
            </div>
        </a>
        
        <!-- Nav Links (Desktop) -->
        <ul class="hidden md:flex items-center gap-8 list-none m-0 p-0 text-sm font-semibold">
            <li><a href="{{ route('home') }}" class="text-white/70 hover:text-white transition-colors duration-200 no-underline {{ request()->routeIs('home') ? '!text-brand-accent font-bold' : '' }}">Inicio</a></li>
            <li><a href="{{ route('courses.index') }}" class="text-white/70 hover:text-white transition-colors duration-200 no-underline {{ request()->routeIs('courses.*') ? '!text-brand-accent font-bold' : '' }}">Cursos</a></li>
            <li><a href="{{ route('memberships.index') }}" class="text-white/70 hover:text-white transition-colors duration-200 no-underline {{ request()->routeIs('memberships.*') ? '!text-brand-accent font-bold' : '' }}">Membresías</a></li>
            @auth
                <li><a href="{{ route('profile.index') }}" class="text-white/70 hover:text-white transition-colors duration-200 no-underline {{ request()->routeIs('profile.*') ? '!text-brand-accent font-bold' : '' }}">Mi Perfil</a></li>
                @if(auth()->user()->isAdmin())
                    <li><a href="{{ route('admin.dashboard') }}" class="text-brand-gold hover:text-brand-gold/80 transition-colors duration-200 no-underline {{ request()->routeIs('admin.*') ? 'font-bold' : '' }}">Admin</a></li>
                @endif
            @endauth
        </ul>
        
        <!-- Actions (Desktop) -->
        <div class="flex items-center gap-4">
            <!-- Theme Switcher -->
            <button id="theme-toggle-btn" type="button" class="relative w-12 h-6 bg-white/10 rounded-full flex items-center justify-between px-1.5 cursor-pointer border border-white/5">
                <!-- Sun Icon -->
                <svg class="w-3.5 h-3.5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                </svg>
                <!-- Moon Icon -->
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
                <span class="absolute w-4 h-4 bg-white rounded-full transition-transform duration-200" :class="isDark ? 'translate-x-5' : 'translate-x-0'"></span>
            </button>

            @auth
                <!-- Notification Bell -->
                <a href="{{ route('profile.index') }}#notif" class="relative p-2 text-white/70 hover:text-white transition-colors no-underline">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    @if(auth()->user()->systemNotifications()->where('is_read', false)->count() > 0)
                        <span class="absolute top-1 right-1 w-3.5 h-3.5 bg-red-500 rounded-full text-[9px] font-bold text-white flex items-center justify-center">
                            {{ auth()->user()->systemNotifications()->where('is_read', false)->count() }}
                        </span>
                    @endif
                </a>
                <span class="hidden lg:inline text-white/80 text-xs font-semibold">{{ explode('@', auth()->user()->email)[0] }}</span>
                
                <form action="{{ route('logout.submit') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 border border-white/10 hover:bg-white/5 rounded-lg text-xs font-bold text-white transition-all cursor-pointer">
                        <span>Salir</span>
                    </button>
                </form>
            @else
                <button class="text-white/80 hover:text-white text-xs font-bold px-3 py-2 cursor-pointer transition-all" onclick="openAuthModal('login')">Iniciar Sesión</button>
                <button class="bg-brand-accent hover:bg-brand-accent/90 text-white text-xs font-bold px-4 py-2 rounded-lg cursor-pointer transition-all" onclick="openAuthModal('register')">Unirme →</button>
            @endauth
            
            <button id="btn-instalar-app" style="display:none;" class="bg-brand-accent hover:bg-brand-accent/90 text-white text-xs font-bold px-3 py-2 rounded-lg cursor-pointer">
                Instalar
            </button>
        </div>
    </div>
</nav>

<!-- Mobile Navigation Bottom Bar -->
<div class="md:hidden fixed bottom-0 left-0 w-full h-[60px] bg-brand-dark border-t border-gray-800 flex items-center justify-around z-[9999]">
    <a href="{{ route('home') }}" class="flex flex-col items-center justify-center text-white/50 no-underline {{ request()->routeIs('home') ? '!text-brand-accent' : '' }}">
        <svg class="w-5 h-5 mb-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        <span class="text-[9px] font-semibold">Inicio</span>
    </a>
    <a href="{{ route('courses.index') }}" class="flex flex-col items-center justify-center text-white/50 no-underline {{ request()->routeIs('courses.*') ? '!text-brand-accent' : '' }}">
        <svg class="w-5 h-5 mb-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.168.477 4 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.168.477-4 1.253"/>
        </svg>
        <span class="text-[9px] font-semibold">Cursos</span>
    </a>
    <a href="{{ route('memberships.index') }}" class="flex flex-col items-center justify-center text-white/50 no-underline {{ request()->routeIs('memberships.*') ? '!text-brand-accent' : '' }}">
        <svg class="w-5 h-5 mb-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
        </svg>
        <span class="text-[9px] font-semibold">Planes</span>
    </a>
    @auth
        <a href="{{ route('profile.index') }}" class="flex flex-col items-center justify-center text-white/50 no-underline {{ request()->routeIs('profile.*') ? '!text-brand-accent' : '' }}">
            <svg class="w-5 h-5 mb-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span class="text-[9px] font-semibold">Perfil</span>
        </a>
    @endauth
</div>
