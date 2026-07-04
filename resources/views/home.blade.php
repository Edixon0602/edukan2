@extends('layouts.app')

@section('title', 'Edukan2 — Ecosistema de Aceleración de Mentes Divergentes')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-12 md:py-24">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <!-- Hero Copy -->
        <div class="flex flex-col">
            <div class="bg-brand-accent/10 border border-brand-accent/30 text-[#00f2fe] text-xs font-bold px-4 py-2 rounded-full w-fit flex items-center gap-2.5 mb-6">
                <span class="w-2.5 h-2.5 bg-[#00f2fe] rounded-full animate-pulse"></span>
                <span>Ecosistema de Aceleración 2026</span>
            </div>
            
            <h1 class="font-display font-black text-4xl sm:text-5xl lg:text-6xl text-white leading-tight mb-4 tracking-tight">
                Escala tus ingresos con <span class="gradient-text">Habilidades</span> de <span class="text-brand-gold">Alto Valor.</span>
            </h1>
            
            <p class="text-brand-text-muted text-sm sm:text-base max-w-lg mb-8 leading-relaxed">
                Únete a la plataforma que está transformando mentes divergentes en fundadores rentables. Sin teoría de relleno, 100% ejecución estratégica.
            </p>
            
            <div class="flex gap-4 flex-wrap mb-10">
                <a href="{{ route('courses.index') }}" wire:navigate class="bg-brand-accent hover:bg-brand-accent/90 text-white font-bold px-6 py-3 rounded-lg text-sm shadow-neon-blue transition-all no-underline">
                    Comenzar Ahora →
                </a>
                
                <a href="https://t.me/tu_canal_aqui" target="_blank" class="border border-[#00f2fe] text-[#00f2fe] hover:bg-[#00f2fe]/10 font-bold px-6 py-3 rounded-lg text-sm transition-all no-underline flex items-center gap-2">
                    <span>✈️</span> Comunidad Gratis
                </a>
            </div>

            <!-- Proof Social Metrics -->
            <div class="flex gap-8 sm:gap-12 border-t border-white/5 pt-8">
                <div>
                    <h4 class="font-display text-2xl sm:text-3xl font-black text-white leading-none mb-1.5">{{ $alumnos ?? '1,250+' }}</h4>
                    <p class="text-[10px] text-brand-text-muted uppercase tracking-wider font-bold">Alumnos Activos</p>
                </div>
                <div>
                    <h4 class="font-display text-2xl sm:text-3xl font-black text-brand-success leading-none mb-1.5">{{ $exito ?? '98%' }}</h4>
                    <p class="text-[10px] text-brand-text-muted uppercase tracking-wider font-bold">Tasa de Éxito</p>
                </div>
                <div>
                    <h4 class="font-display text-2xl sm:text-3xl font-black text-[#00f2fe] leading-none mb-1.5">{{ $paises ?? '15+' }}</h4>
                    <p class="text-[10px] text-brand-text-muted uppercase tracking-wider font-bold">Países Alcanzados</p>
                </div>
            </div>
        </div>

        <!-- Hero Visual Cards -->
        <div class="relative flex flex-col items-center">
            <!-- Main visual card -->
            <div class="bg-brand-dark2/80 border border-[#00f2fe]/20 rounded-2xl p-6 shadow-2xl backdrop-blur-md w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                    <span class="font-display text-[10px] text-[#00f2fe] font-black tracking-wider uppercase">METODOLOGÍA 100% PRÁCTICA</span>
                    <span class="bg-[#00f2fe]/10 text-[#00f2fe] px-2.5 py-1 rounded-full text-[9px] font-bold">⚡ Acción Inmediata</span>
                </div>
                <h3 class="text-white font-bold text-lg mb-2">Aprende. Ejecuta. Factura.</h3>
                <p class="text-brand-text-muted text-xs sm:text-sm leading-relaxed">
                    Olvídate de la teoría académica aburrida. Nuestros programas están diseñados con casos de estudio reales para que apliques lo aprendido desde el día uno.
                </p>
            </div>

            <!-- Sub visual cards -->
            <div class="grid grid-cols-2 gap-4 w-full max-w-md mt-4">
                <div class="bg-brand-dark2/80 border border-white/5 hover:border-white/10 rounded-2xl p-5 shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="text-2xl mb-3">🌍</div>
                    <h4 class="text-white text-xs font-bold mb-1">Networking VIP</h4>
                    <p class="text-brand-text-muted text-[10px] leading-relaxed">Conecta con fundadores e inversionistas de toda Latinoamérica.</p>
                </div>
                <div class="bg-brand-dark2/80 border border-brand-gold/10 hover:border-brand-gold/30 rounded-2xl p-5 shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="text-2xl mb-3">👑</div>
                    <h4 class="text-white text-xs font-bold mb-1">Soporte Elite</h4>
                    <p class="text-brand-text-muted text-[10px] leading-relaxed">Mentores 24/7 para destrabar tu progreso cuando lo necesites.</p>
                </div>
            </div>

            <!-- Floating Badge -->
            <div class="absolute -top-4 -right-4 bg-brand-success/10 border border-brand-success/30 text-brand-success text-xs font-bold px-4 py-1.5 rounded-full flex items-center gap-2 shadow-lg">
                <span class="w-2 h-2 bg-brand-success rounded-full animate-pulse"></span>
                <span>Clases Verificadas</span>
            </div>
        </div>
    </div>
</div>
@endsection
