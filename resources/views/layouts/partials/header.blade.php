<!-- Header Section -->
<nav class="fixed top-0 left-0 w-full h-[70px] bg-brand-dark/80 backdrop-blur-xl border-b border-white/5 px-6 flex items-center justify-between z-[9999]">
    <!-- Logo -->
    <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-3 no-underline">
        <div class="flex items-center gap-2">
            <svg class="w-9 h-9" viewBox="0 0 100 100">
                <linearGradient id="logoGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#00f2fe"/>
                    <stop offset="100%" stop-color="#0052ff"/>
                </linearGradient>
                <rect width="100" height="100" rx="25" fill="rgba(255,255,255,0.05)" stroke="url(#logoGrad)" stroke-width="4"/>
                <path d="M 70 30 L 35 30 L 35 50 L 60 50 L 60 65 L 35 65 L 35 85 L 75 85" fill="none" stroke="url(#logoGrad)" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M 60 30 L 75 15" fill="none" stroke="#00f2fe" stroke-width="8" stroke-linecap="round"/>
            </svg>
            <span class="font-display font-black text-xl tracking-wider text-white">
                Edukan<span class="text-[#00f2fe]">2</span>
            </span>
        </div>
    </a>
    
    <!-- Nav Links (Desktop) -->
    <ul class="hidden md:flex items-center gap-8 list-none m-0 p-0 text-sm font-semibold">
        <li><a href="{{ route('home') }}" wire:navigate class="text-white/70 hover:text-white transition-colors duration-200 no-underline {{ request()->routeIs('home') ? '!text-brand-accent font-bold' : '' }}">Inicio</a></li>
        <li><a href="{{ route('courses.index') }}" wire:navigate class="text-white/70 hover:text-white transition-colors duration-200 no-underline {{ request()->routeIs('courses.*') ? '!text-brand-accent font-bold' : '' }}">Cursos</a></li>
        <li><a href="{{ route('memberships.index') }}" wire:navigate class="text-white/70 hover:text-white transition-colors duration-200 no-underline {{ request()->routeIs('memberships.*') ? '!text-brand-accent font-bold' : '' }}">Membresías</a></li>
        @auth
            <li><a href="{{ route('profile.index') }}" wire:navigate class="text-white/70 hover:text-white transition-colors duration-200 no-underline {{ request()->routeIs('profile.*') ? '!text-brand-accent font-bold' : '' }}">Mi Perfil</a></li>
            @if(auth()->user()->isAdmin())
                <li><a href="{{ route('admin.dashboard') }}" wire:navigate class="text-brand-gold hover:text-brand-gold/80 transition-colors duration-200 no-underline {{ request()->routeIs('admin.*') ? 'font-bold' : '' }}">⚙️ Admin</a></li>
            @endif
        @endauth
    </ul>
    
    <!-- Actions (Desktop) -->
    <div class="flex items-center gap-4">
        <!-- Theme Switcher -->
        <button @click="toggleTheme()" class="relative w-12 h-6 bg-white/10 rounded-full flex items-center justify-between px-1.5 cursor-pointer border border-white/5">
            <span class="text-[10px]">☀️</span>
            <span class="text-[10px]">🌙</span>
            <span class="absolute w-4 h-4 bg-white rounded-full transition-transform duration-200" :class="isDark ? 'translate-x-5' : 'translate-x-0'"></span>
        </button>

        @auth
            <!-- Notification Bell -->
            <a href="{{ route('profile.index') }}#notif" wire:navigate class="relative p-2 text-white/70 hover:text-white transition-colors no-underline">
                🔔
                @if(auth()->user()->systemNotifications()->where('is_read', false)->count() > 0)
                    <span class="absolute top-0 right-0 w-4 h-4 bg-red-500 rounded-full text-[10px] font-bold text-white flex items-center justify-center">
                        {{ auth()->user()->systemNotifications()->where('is_read', false)->count() }}
                    </span>
                @endif
            </a>
            <span class="hidden lg:inline text-white/80 text-xs font-semibold">👤 {{ explode('@', auth()->user()->email)[0] }}</span>
            
            <form action="{{ route('logout.submit') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 border border-white/10 hover:bg-white/5 rounded-lg text-xs font-bold text-white transition-all cursor-pointer">
                    <span class="hidden md:inline">🔒 Salir</span>
                    <span class="md:hidden">🔒</span>
                </button>
            </form>
        @else
            <button class="text-white/80 hover:text-white text-xs font-bold px-3 py-2 cursor-pointer transition-all" onclick="openAuthModal('login')">Iniciar Sesión</button>
            <button class="bg-brand-accent hover:bg-brand-accent/90 text-white text-xs font-bold px-4 py-2 rounded-lg cursor-pointer transition-all shadow-neon-blue" onclick="openAuthModal('register')">Unirme →</button>
        @endauth
        
        <button id="btn-instalar-app" style="display:none;" class="bg-gradient-to-r from-brand-accent to-[#00f2fe] text-white text-xs font-bold px-3 py-2 rounded-lg cursor-pointer">
            📱 Instalar
        </button>
    </div>
</nav>

<!-- Mobile Navigation Bottom Bar -->
<div class="md:hidden fixed bottom-0 left-0 w-full h-[60px] bg-brand-dark/95 backdrop-blur-md border-t border-white/5 flex items-center justify-around z-[9999]">
    <a href="{{ route('home') }}" wire:navigate class="flex flex-col items-center justify-center text-white/50 no-underline {{ request()->routeIs('home') ? '!text-brand-accent' : '' }}">
        <span class="text-xl">🏠</span><span class="text-[10px] font-semibold">Inicio</span>
    </a>
    <a href="{{ route('courses.index') }}" wire:navigate class="flex flex-col items-center justify-center text-white/50 no-underline {{ request()->routeIs('courses.*') ? '!text-brand-accent' : '' }}">
        <span class="text-xl">📚</span><span class="text-[10px] font-semibold">Cursos</span>
    </a>
    <a href="{{ route('memberships.index') }}" wire:navigate class="flex flex-col items-center justify-center text-white/50 no-underline {{ request()->routeIs('memberships.*') ? '!text-brand-accent' : '' }}">
        <span class="text-xl">💎</span><span class="text-[10px] font-semibold">Planes</span>
    </a>
    @auth
        <a href="{{ route('profile.index') }}" wire:navigate class="flex flex-col items-center justify-center text-white/50 no-underline {{ request()->routeIs('profile.*') ? '!text-brand-accent' : '' }}">
            <span class="text-xl">👤</span><span class="text-[10px] font-semibold">Perfil</span>
        </a>
    @endauth
</div>
