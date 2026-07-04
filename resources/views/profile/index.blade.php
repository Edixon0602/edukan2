@extends('layouts.app')

@section('title', 'Mi Perfil Estudiantil — Edukan2')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8">
        <!-- Sidebar Info -->
        <div class="flex flex-col gap-6">
            <div class="bg-brand-dark2 border border-gray-800 p-6 rounded-2xl text-center">
                <div class="w-20 h-20 bg-brand-accent/15 border border-brand-accent/30 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-brand-accent font-display font-black text-2xl">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                </div>
                <h3 class="text-white font-bold text-base mb-1">{{ auth()->user()->name }}</h3>
                <div class="text-brand-gold text-xs font-bold uppercase tracking-wider mt-1">{{ auth()->user()->membership }}</div>
                
                <div class="text-left mt-6 flex flex-col gap-3 text-xs text-brand-text-muted border-t border-gray-800 pt-6 font-semibold">
                    <span>Email: {{ auth()->user()->email }}</span>
                    <span>WhatsApp: {{ auth()->user()->phone ?? 'No ingresado' }}</span>
                    <span>País: {{ auth()->user()->country ?? 'No especificado' }}</span>
                </div>
            </div>

            <!-- Grades stats -->
            <div class="bg-brand-dark2 border border-gray-800 p-6 rounded-2xl">
                <h4 class="text-white font-display text-xs font-bold uppercase tracking-wider mb-4">Rendimiento Académico</h4>
                <div class="flex flex-col gap-4">
                    <div class="flex justify-between items-center border-b border-gray-800 pb-3">
                        <span class="text-brand-text-muted text-xs font-semibold">Promedio</span>
                        <strong class="text-white font-display font-black text-base">{{ $academicAvg }}%</strong>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-brand-text-muted text-xs font-semibold">Exámenes</span>
                        <strong class="text-white font-display font-black text-base">{{ $completedExams }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main section -->
        <div class="flex flex-col gap-8">
            <!-- Student API Access Token -->
            <div class="bg-brand-dark2 border border-gray-800 p-6 rounded-2xl">
                <h3 class="text-brand-accent font-display font-black text-xs uppercase tracking-wider mb-2">Token de Acceso Estudiante</h3>
                <p class="text-brand-text-muted text-xs sm:text-sm leading-relaxed mb-4">Utiliza este token de autenticación para consumir nuestra API externa de roadmaps y materiales complementarios.</p>
                <div class="flex gap-2">
                    <input type="text" readonly value="{{ auth()->user()->api_token ?? 'Token no generado' }}" class="bg-brand-dark border border-gray-800 rounded-lg px-4 py-2.5 text-xs text-white/70 select-all focus:outline-none flex-grow font-mono">
                </div>
            </div>

            <!-- Enrolled courses -->
            <div class="bg-brand-dark2 border border-gray-800 p-6 rounded-2xl">
                <h3 class="text-white font-display font-black text-sm uppercase tracking-wider mb-6">Mis Cursos Matriculados</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($enrolledCourses as $course)
                        <div class="p-4 bg-white/[0.01] border border-gray-800 rounded-xl flex justify-between items-center gap-4">
                            <div>
                                <h4 class="text-white text-xs sm:text-sm font-bold">{{ $course->title }}</h4>
                                <span class="text-brand-text-muted text-[10px] font-semibold mt-1 block">Inscripción Activa</span>
                            </div>
                            <a href="/courses/{{ $course->id }}" class="bg-brand-accent hover:bg-brand-accent/90 text-white font-bold px-4 py-2 rounded-lg text-xs transition-all no-underline cursor-pointer">
                                Continuar
                            </a>
                        </div>
                    @endforeach
                    @if(count($enrolledCourses) === 0)
                        <p class="text-brand-text-muted text-xs sm:text-sm col-span-2">Aún no estás inscrito en ningún curso individual.</p>
                    @endif
                </div>
            </div>

            <!-- Notifications Center -->
            <div class="bg-brand-dark2 border border-gray-800 p-6 rounded-2xl" id="notif">
                <h3 class="text-white font-display font-black text-sm uppercase tracking-wider mb-6">Alertas y Notificaciones</h3>
                <div class="flex flex-col gap-3">
                    @foreach(auth()->user()->systemNotifications()->orderBy('created_at', 'desc')->take(10)->get() as $n)
                        <div class="p-4 bg-white/[0.01] border border-gray-800 rounded-xl">
                            <div class="flex justify-between items-center gap-2 mb-2 flex-wrap">
                                <b class="text-xs text-white font-bold">{{ $n->title }}</b>
                                <span class="text-[9px] font-semibold text-brand-text-muted bg-white/5 px-2 py-0.5 rounded">{{ $n->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-white/80 text-xs sm:text-sm leading-relaxed">{{ $n->message }}</p>
                        </div>
                    @endforeach
                    @if(auth()->user()->systemNotifications()->count() === 0)
                        <p class="text-brand-text-muted text-xs sm:text-sm">No tienes alertas pendientes de lectura.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
