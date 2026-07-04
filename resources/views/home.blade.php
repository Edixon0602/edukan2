@extends('layouts.app')

@section('title', 'Edukan2 — Ecosistema de Aceleración de Mentes Divergentes')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-12 md:py-24">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <!-- Hero Copy -->
        <div class="flex flex-col">
            <h1 class="font-display font-black text-4xl sm:text-5xl lg:text-6xl text-white leading-tight mb-6 tracking-tight">
                Escala tus ingresos con <span class="text-brand-accent">Habilidades</span> de <span class="text-brand-gold">Alto Valor.</span>
            </h1>
            
            <p class="text-brand-text-muted text-sm sm:text-base max-w-lg mb-8 leading-relaxed">
                Únete a la plataforma que está transformando mentes divergentes en fundadores rentables. Sin teoría de relleno, 100% ejecución estratégica.
            </p>
            
            <div class="flex gap-4 flex-wrap mb-10">
                <a href="{{ route('courses.index') }}" class="bg-brand-accent hover:bg-brand-accent/90 text-white font-bold px-6 py-3 rounded-lg text-sm transition-all no-underline">
                    Comenzar Ahora
                </a>
                
                <a href="https://t.me/tu_canal_aqui" target="_blank" class="border border-brand-accent text-brand-accent hover:bg-brand-accent/10 font-bold px-6 py-3 rounded-lg text-sm transition-all no-underline">
                    Comunidad Gratis
                </a>
            </div>

            <!-- Proof Social Metrics -->
            <div class="flex gap-8 sm:gap-12 border-t border-gray-800 pt-8">
                <div>
                    <h4 class="font-display text-2xl sm:text-3xl font-black text-white leading-none mb-1.5">{{ $alumnos ?? '1,250+' }}</h4>
                    <p class="text-[10px] text-brand-text-muted uppercase tracking-wider font-bold">Alumnos Activos</p>
                </div>
                <div>
                    <h4 class="font-display text-2xl sm:text-3xl font-black text-brand-success leading-none mb-1.5">{{ $exito ?? '98%' }}</h4>
                    <p class="text-[10px] text-brand-text-muted uppercase tracking-wider font-bold">Tasa de Éxito</p>
                </div>
                <div>
                    <h4 class="font-display text-2xl sm:text-3xl font-black text-brand-accent leading-none mb-1.5">{{ $paises ?? '15+' }}</h4>
                    <p class="text-[10px] text-brand-text-muted uppercase tracking-wider font-bold">Países Alcanzados</p>
                </div>
            </div>
        </div>

        <!-- Hero Visual Cards -->
        <div class="relative flex flex-col items-center w-full">
            <!-- Main visual card -->
            <div class="bg-brand-dark2 border border-gray-800 rounded-2xl p-6 shadow-2xl w-full max-w-md">
                <div class="mb-4">
                    <span class="font-display text-[10px] text-brand-accent font-black tracking-wider uppercase">Metodología Práctica</span>
                </div>
                <h3 class="text-white font-bold text-lg mb-2">Aprende. Ejecuta. Factura.</h3>
                <p class="text-brand-text-muted text-xs sm:text-sm leading-relaxed">
                    Olvídate de la teoría académica aburrida. Nuestros programas están diseñados con casos de estudio reales para que apliques lo aprendido desde el primer día.
                </p>
            </div>

            <!-- Sub visual cards -->
            <div class="grid grid-cols-2 gap-4 w-full max-w-md mt-4">
                <div class="bg-brand-dark2 border border-gray-800 hover:border-gray-700 rounded-2xl p-5 shadow-xl transition-all duration-300">
                    <h4 class="text-white text-xs font-bold mb-1">Networking VIP</h4>
                    <p class="text-brand-text-muted text-[10px] leading-relaxed">Conecta con fundadores e inversionistas de toda Latinoamérica.</p>
                </div>
                <div class="bg-brand-dark2 border border-gray-800 hover:border-gray-700 rounded-2xl p-5 shadow-xl transition-all duration-300">
                    <h4 class="text-white text-xs font-bold mb-1">Soporte Elite</h4>
                    <p class="text-brand-text-muted text-[10px] leading-relaxed">Mentores especializados para destrabar tu progreso cuando lo necesites.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
