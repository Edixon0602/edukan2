@extends('layouts.app')

@section('title', 'Mi Perfil — Edukan2')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-10" x-data="profileEngine()">
    <!-- Profile Header Box -->
    <div class="bg-brand-dark2 border border-white/5 rounded-3xl p-8 sm:p-10 mb-8 flex flex-col md:flex-row justify-between items-center gap-8 shadow-2xl">
        <div class="flex flex-col sm:flex-row gap-6 sm:gap-8 items-center text-center sm:text-left">
            <div class="w-28 h-28 min-w-[112px] rounded-full bg-brand-dark border-4 border-brand-accent flex items-center justify-center text-4xl font-bold text-white overflow-hidden shadow-2xl">
                @if($user->avatar_path)
                    <img src="{{ asset('storage/' . $user->avatar_path) }}" class="w-full h-full object-cover">
                @else
                    <span>{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                @endif
            </div>
            <div class="flex flex-col">
                <h2 class="text-white text-3xl font-black mb-1 leading-tight tracking-tight">{{ $user->name }}</h2>
                <p class="text-brand-text-muted text-sm mb-2">{{ $user->email }}</p>
                <p class="text-brand-accent text-xs font-bold uppercase tracking-wider flex items-center justify-center sm:justify-start gap-1">✓ Alumno Certificado</p>
                <button @click="configModalOpen = true" class="border border-white/10 hover:bg-white/5 text-white text-xs font-bold px-4 py-2.5 rounded-lg mt-4 cursor-pointer transition-all w-fit mx-auto sm:mx-0">⚙️ Editar Mi Perfil</button>
            </div>
        </div>
        
        <!-- Rango / Membership visual card -->
        <div class="p-6 rounded-2xl flex flex-col justify-center text-center min-w-[260px] shadow-2xl" :style="getMembershipStyle('bg')">
            <div class="text-[10px] text-brand-text-muted font-bold uppercase tracking-wider">Plan de Acceso Actual</div>
            <h3 class="text-xl font-black mt-2 leading-none" :style="getMembershipStyle('text')" x-text="getMembershipName()">Miembro Regular</h3>
            <a href="{{ route('memberships.index') }}" wire:navigate class="bg-brand-accent hover:bg-brand-accent/90 text-white font-bold py-2.5 rounded-lg text-xs mt-4 shadow-neon-blue transition-all no-underline inline-block">⭐ Mejorar Membresía</a>
        </div>
    </div>

    <!-- Sub-tabs Navigation -->
    <div class="flex gap-2 mb-8 border-b border-white/5 pb-1 overflow-x-auto scrollbar-none">
        <button :class="activeTab === 'ruta' ? 'text-white border-b-2 border-brand-accent font-bold' : 'text-white/50 hover:text-white'" class="pb-3 px-4 text-xs sm:text-sm font-semibold whitespace-nowrap cursor-pointer transition-all" @click="activeTab = 'ruta'">🛣️ Mis Cursos</button>
        <button :class="activeTab === 'stats' ? 'text-white border-b-2 border-brand-accent font-bold' : 'text-white/50 hover:text-white'" class="pb-3 px-4 text-xs sm:text-sm font-semibold whitespace-nowrap cursor-pointer transition-all" @click="openStatsTab()">📈 Mi Rendimiento</button>
        <button :class="activeTab === 'logros' ? 'text-white border-b-2 border-brand-accent font-bold' : 'text-white/50 hover:text-white'" class="pb-3 px-4 text-xs sm:text-sm font-semibold whitespace-nowrap cursor-pointer transition-all" @click="activeTab = 'logros'">🏆 Mis Logros</button>
        <button :class="activeTab === 'notif' ? 'text-white border-b-2 border-brand-accent font-bold' : 'text-white/50 hover:text-white'" class="pb-3 px-4 text-xs sm:text-sm font-semibold whitespace-nowrap cursor-pointer transition-all" @click="activeTab = 'notif'">🔔 Alertas</button>
    </div>

    <!-- 1. TAB: MIS CURSOS -->
    <div x-show="activeTab === 'ruta'" class="flex flex-col gap-5">
        @forelse($enrolledCourses as $idx => $course)
            <div class="bg-brand-dark2 border border-white/5 rounded-2xl overflow-hidden shadow-xl">
                <div @click="toggleAccordion('{{ $idx }}')" class="cursor-pointer p-6 flex justify-between items-center gap-6 flex-wrap bg-white/[0.01] hover:bg-white/[0.02] transition-all">
                    <div>
                        <span class="text-[9px] font-bold uppercase text-brand-accent bg-brand-accent/10 border border-brand-accent/20 px-2 py-0.5 rounded-full">
                            {{ $course->category->name ?? 'General' }}
                        </span>
                        <h4 class="text-white text-base font-bold mt-2">
                            {{ $course->title }} <span class="text-brand-accent text-xs ml-1.5 font-normal">▼ (Ver Clases)</span>
                        </h4>
                    </div>
                    <div @click.stop class="flex gap-2">
                        @if($course->is_completed)
                            <a href="{{ route('courses.player', $course->id) }}" wire:navigate class="border border-white/10 hover:bg-white/5 text-white text-xs font-bold px-3 py-2 rounded-lg no-underline transition-all">Repasar</a>
                            <a href="{{ route('profile.certificate', $course->id) }}" target="_blank" class="bg-brand-success hover:bg-brand-success/90 text-white text-xs font-bold px-4 py-2 rounded-lg no-underline transition-all shadow-neon-gold">
                                📜 Certificado
                            </a>
                        @else
                            <a href="{{ route('courses.player', $course->id) }}" wire:navigate class="bg-brand-accent hover:bg-brand-accent/90 text-white text-xs font-bold px-4 py-2.5 rounded-lg no-underline transition-all shadow-neon-blue">
                                ▶️ Continuar
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="px-6 pb-6">
                    <div class="flex justify-between text-xs text-brand-text-muted mb-2 font-bold">
                        <span>Progreso en esta asignatura</span>
                        <span class="font-bold" :class="{{ $course->is_completed ? 'true' : 'false' }} ? 'text-brand-success' : 'text-brand-accent'">{{ $course->progress_percent }}%</span>
                    </div>
                    <div class="w-full h-1.5 bg-white/5 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-500" :class="{{ $course->is_completed ? 'true' : 'false' }} ? 'bg-brand-success' : 'bg-brand-accent'" style="width: {{ $course->progress_percent }}%"></div>
                    </div>
                </div>

                <!-- Accordion Temario details -->
                <div id="accordion-{{ $idx }}" class="hidden bg-black/20 border-t border-white/5 p-6">
                    <div class="text-[10px] text-brand-text-muted font-bold uppercase tracking-wider mb-3">Plan de Clases Detallado:</div>
                    <div class="flex flex-col gap-1">
                        @foreach($course->modules as $mod)
                            @foreach($mod->lessons as $lesson)
                                <div class="flex justify-between items-center py-2.5 border-b border-white/[0.02] text-xs">
                                    <span class="text-white/80">Lección: {{ $lesson->title }}</span>
                                    <span class="text-[10px] font-bold" :class="{{ $course->is_completed ? 'true' : 'false' }} ? 'text-brand-success' : 'text-brand-accent'">
                                        {{ $course->is_completed ? '✓ Completada' : '⏱️ Pendiente' }}
                                    </span>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
        @empty
            <p class="text-brand-text-muted text-sm text-center py-10">Aún no estás inscrito en ningún curso. ¡Ve al catálogo!</p>
        @endforelse
    </div>

    <!-- 2. TAB: MI RENDIMIENTO -->
    <div x-show="activeTab === 'stats'" class="hidden" :class="activeTab === 'stats' ? '!block' : ''">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
            <div class="bg-brand-dark2 border border-white/5 p-6 rounded-2xl shadow-xl">
                <div class="text-[10px] text-brand-text-muted font-bold uppercase tracking-wider">Promedio Global</div>
                <div class="text-3xl font-black text-brand-success mt-2 font-display leading-none">{{ $avgGrade }}%</div>
            </div>
            <div class="bg-brand-dark2 border border-white/5 p-6 rounded-2xl shadow-xl">
                <div class="text-[10px] text-brand-text-muted font-bold uppercase tracking-wider">Cursos Adquiridos</div>
                <div class="text-3xl font-black text-white mt-2 font-display leading-none">{{ count($enrolledCourses) }}</div>
            </div>
        </div>
        
        <!-- Curve Chart -->
        <div class="bg-brand-dark2 border border-white/5 rounded-2xl p-6 h-[320px] flex flex-col shadow-xl">
            <h3 class="text-white font-bold text-sm mb-4">📈 Curva de Aprendizaje</h3>
            <div class="flex-grow relative w-full h-full">
                <canvas id="grafica-progreso-alumno"></canvas>
            </div>
        </div>
    </div>

    <!-- 3. TAB: MIS LOGROS -->
    <div x-show="activeTab === 'logros'" class="hidden" :class="activeTab === 'logros' ? '!block' : ''">
        <div class="bg-brand-dark2 border border-white/5 rounded-2xl p-6 sm:p-8 shadow-xl">
            <h3 class="text-white font-display font-black text-base uppercase tracking-wider mb-6">🏆 Vitrina de Prestigio</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <template x-for="logro in getAchievementsList()">
                    <div :class="logro.unlocked ? 'bg-brand-accent/5 border border-brand-accent/30' : 'bg-white/[0.01] border border-dashed border-white/10'" 
                         class="rounded-2xl p-5 flex gap-4 items-center relative transition-all duration-300 shadow-xl">
                        
                        <span class="absolute top-3 right-3 text-xs opacity-50" x-show="!logro.unlocked">🔒</span>
                        <div class="text-3xl" :class="!logro.unlocked && 'filter grayscale opacity-30'" x-text="logro.icon"></div>
                        
                        <div class="w-full">
                            <h4 class="text-white text-xs sm:text-sm font-bold mb-1" :class="!logro.unlocked && 'opacity-40'" x-text="logro.title"></h4>
                            <p class="text-brand-text-muted text-[10px] leading-relaxed mb-2.5" :class="!logro.unlocked && 'opacity-40'" x-text="logro.desc"></p>
                            <div class="w-full bg-white/5 h-1 rounded-full overflow-hidden">
                                <div :style="'width:' + logro.progress + '%;'" class="bg-brand-accent h-full rounded-full"></div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- 4. TAB: ALERTAS / NOTIFICACIONES -->
    <div x-show="activeTab === 'notif'" class="hidden" :class="activeTab === 'notif' ? '!block' : ''">
        <div class="bg-brand-dark2 border border-white/5 rounded-2xl p-6 sm:p-8 shadow-xl">
            <h3 class="text-white font-display font-black text-base uppercase tracking-wider mb-6">🔔 Mensajes del Sistema</h3>
            
            <div class="flex flex-col gap-4">
                @forelse($notifications as $notif)
                    <div id="notif-box-{{ $notif->id }}" class="p-5 rounded-2xl border flex justify-between items-start gap-4 relative shadow-md"
                         :style="'background: ' + getNotifBg('{{ $notif->type }}')">
                        <div class="flex-grow">
                            <h4 class="text-white text-sm font-bold mb-1">{{ $notif->title }}</h4>
                            <p class="text-white/80 text-xs leading-relaxed">{{ $notif->message }}</p>
                            <small class="text-brand-text-muted text-[9px] block mt-2.5">{{ $notif->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                        <button @click="deleteNotification('{{ $notif->id }}')" class="bg-none border-none text-brand-text-muted hover:text-white cursor-pointer text-sm">✕</button>
                    </div>
                @empty
                    <p class="text-brand-text-muted text-sm text-center py-6">No tienes alertas en tu bandeja de entrada.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Edit Profile Settings Modal -->
    <div class="fixed inset-0 bg-brand-dark/85 backdrop-blur-md flex items-center justify-center z-[99999] transition-all duration-300" x-show="configModalOpen" x-transition class="hidden" :class="configModalOpen ? '!flex' : 'hidden'">
        <div class="bg-brand-dark2 border border-white/10 w-full max-w-md rounded-2xl p-8 relative shadow-2xl mx-6" @click.outside="configModalOpen = false">
            <button @click="configModalOpen = false" class="absolute top-4 right-4 text-white/50 hover:text-white text-lg cursor-pointer">✕</button>
            <h2 class="text-white font-display font-black text-lg mb-6">⚙️ Editar Perfil</h2>
            
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
                @csrf
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-white/70">Nombre Completo</label>
                    <input type="text" name="name" value="{{ $user->name }}" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white focus:border-brand-accent focus:outline-none" required>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-white/70">Teléfono / WhatsApp</label>
                    <input type="text" name="phone" value="{{ $user->phone }}" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white focus:border-brand-accent focus:outline-none" required>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-white/70">País</label>
                    <input type="text" name="country" value="{{ $user->country }}" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white focus:border-brand-accent focus:outline-none" required>
                </div>
                
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-white/70">Subir Foto de Perfil</label>
                    <input type="file" name="foto" accept="image/*" class="w-full bg-white/5 border border-white/10 rounded-lg p-2 text-xs text-white focus:border-brand-accent focus:outline-none cursor-pointer">
                    <small class="text-brand-text-muted text-[10px]">La imagen se guardará en tu cuenta.</small>
                </div>
                
                <p class="text-[10px] text-brand-text-muted leading-relaxed">* El correo electrónico principal no puede modificarse desde aquí por seguridad.</p>
                <button type="submit" class="w-full bg-brand-accent hover:bg-brand-accent/90 text-white font-bold py-3 rounded-lg text-sm transition-all shadow-neon-blue cursor-pointer">💾 Guardar Cambios</button>
            </form>
        </div>
    </div>
</div>

<script>
    function profileEngine() {
        return {
            activeTab: 'ruta',
            chartLoaded: false,
            chartInstance: null,
            configModalOpen: false,

            getMembershipName() {
                const membership = '{{ $user->membership }}'.toLowerCase();
                if (membership === 'vip') return 'VIP PRESTIGE MEMBER';
                if (membership === 'pro') return 'MIEMBRO ACADEMIA PRO';
                if (membership === 'estandar') return 'ESTUDIANTE ESTÁNDAR';
                return 'Estudiante Regular';
            },

            getMembershipStyle(type) {
                const membership = '{{ $user->membership }}'.toLowerCase();
                if (type === 'bg') {
                    if (membership === 'vip') return 'background: linear-gradient(135deg, #0a0a0b 0%, #1c160e 60%, #0f0d0a 100%); border: 1px solid rgba(255,215,0,0.25);';
                    if (membership === 'pro') return 'background: linear-gradient(135deg, #0b111e 0%, #161a29 100%); border: 1px solid rgba(0,82,255,0.2);';
                    if (membership === 'estandar') return 'background: linear-gradient(135deg, #0d1a14 0%, #12241b 100%); border: 1px solid rgba(46,204,113,0.15);';
                    return 'background: var(--dark2); border: 1px solid var(--border);';
                } else if (type === 'text') {
                    if (membership === 'vip') return 'color: #ffd700;';
                    if (membership === 'pro') return 'color: #0052ff;';
                    if (membership === 'estandar') return 'color: #2ecc71;';
                    return 'color: white;';
                }
            },

            toggleAccordion(idx) {
                const el = document.getElementById('accordion-' + idx);
                if (el) {
                    el.classList.toggle('hidden');
                }
            },

            getNotifBg(type) {
                if (type === 'success') return 'linear-gradient(135deg, rgba(0,229,160,0.06), rgba(0,229,160,0.02)); border-color: rgba(0,229,160,0.15);';
                if (type === 'warning') return 'linear-gradient(135deg, rgba(255,77,106,0.06), rgba(255,77,106,0.02)); border-color: rgba(255,77,106,0.15);';
                return 'linear-gradient(135deg, rgba(41,171,255,0.06), rgba(41,171,255,0.02)); border-color: rgba(41,171,255,0.15);';
            },

            deleteNotification(id) {
                fetch(`/api/notifications/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': window.csrfToken
                    }
                })
                .then(res => res.json())
                .then(data => {
                    showGlobalNotification(data.message);
                    const el = document.getElementById('notif-box-' + id);
                    if (el) el.remove();
                });
            },

            openStatsTab() {
                this.activeTab = 'stats';
                if (this.chartLoaded) return;
                
                // Fetch progress data and build Chart
                fetch('/api/user/performance-data')
                    .then(res => res.json())
                    .then(result => {
                        this.chartLoaded = true;
                        this.$nextTick(() => {
                            this.renderProgressChart(result.labels, result.data);
                        });
                    });
            },

            renderProgressChart(labels, data) {
                const ctx = document.getElementById('grafica-progreso-alumno').getContext('2d');
                this.chartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels.length > 0 ? labels : ['Matrícula'],
                        datasets: [{
                            label: 'Nota Obtenida (%)',
                            data: data.length > 0 ? data : [0],
                            borderColor: '#0052ff',
                            backgroundColor: 'rgba(0, 82, 255, 0.05)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: { min: 0, max: 100, grid: { color: 'rgba(255,255,255,0.05)' } },
                            x: { grid: { color: 'rgba(255,255,255,0.05)' } }
                        }
                    }
                });
            },

            getAchievementsList() {
                const userMembership = '{{ $user->membership }}'.toLowerCase();
                const totalBought = {{ count($enrolledCourses) }};
                const totalEvaluations = {{ $totalQuizzes }};
                const avg = {{ $avgGrade }};

                return [
                    { id: 'mente_divergente', icon: '🧠', title: 'Mente Divergente', desc: 'Te uniste a la Élite registrando tu cuenta por primera vez.', progress: 100, unlocked: true },
                    { id: 'primera_sangre', icon: '🩸', title: 'Primera Sangre', desc: 'Completaste tu primera clase. El inicio de la grandeza.', progress: totalEvaluations > 0 ? 100 : 10, unlocked: totalEvaluations > 0 },
                    { id: 'estudiante_fiel', icon: '🔥', title: 'Racha Imparable', desc: 'Te has conectado a la plataforma 3 días seguidos.', progress: 35, unlocked: false },
                    { id: 'cazador_ofertas', icon: '🎯', title: 'Visionario Comercial', desc: 'Aprovechaste una oferta o un cupón estratégico.', progress: {{ $user->global_discount > 0 ? 100 : 0 }}, unlocked: {{ $user->global_discount > 0 ? 'true' : 'false' }} },
                    { id: 'erudito_digital', icon: '🦉', title: 'Erudito Digital', desc: 'Obtuviste una calificación perfecta (100%) en evaluación.', progress: avg >= 100 ? 100 : 50, unlocked: avg >= 100 },
                    { id: 'pionero', icon: '🚀', title: 'Fundador Pionero', desc: 'Formas parte de los primeros 1,000 estudiantes de la plataforma.', progress: 100, unlocked: true },
                    { id: 'maratonista', icon: '🏃‍♂️', title: 'Maratonista', desc: 'Consumiste más de 5 clases en un solo día de enfoque.', progress: 20, unlocked: false },
                    { id: 'inversor_maestro', icon: '👑', title: 'Inversor Maestro', desc: 'Adquiriste una membresía PRO o VIP Prestige.', progress: (userMembership === 'pro' || userMembership === 'vip') ? 100 : 20, unlocked: (userMembership === 'pro' || userMembership === 'vip') },
                    { id: 'noctambulo', icon: '🦇', title: 'Vampiro Digital', desc: 'Aprobaste un módulo después de la medianoche.', progress: 0, unlocked: false },
                    { id: 'francotirador', icon: '🎯', title: 'Francotirador', desc: 'Aprobaste la evaluación final a tu primer intento, sin errores.', progress: 0, unlocked: false },
                    { id: 'multidisciplinario', icon: '🌐', title: 'Mente Multidisciplinaria', desc: 'Dominas habilidades de al menos 2 rutas distintas.', progress: totalBought >= 2 ? 100 : 50, unlocked: totalBought >= 2 },
                    { id: 'networking', icon: '🤝', title: 'Conector de Élite', desc: 'Ingresaste a nuestra comunidad privada de networking.', progress: userMembership === 'vip' ? 100 : 0, unlocked: userMembership === 'vip' }
                ];
            }
        }
    }

    // Direct hash links handler (e.g. #notif opens alerts tab)
    document.addEventListener('DOMContentLoaded', () => {
        if(window.location.hash === '#notif') {
            const modal = document.querySelector('[x-data]');
            const alpineData = Alpine.$data(modal);
            if(alpineData) alpineData.activeTab = 'notif';
        }
    });
</script>
@endsection
