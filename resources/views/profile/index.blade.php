@extends('layouts.app')

@section('title', 'Mi Perfil — Edukan2')

@section('content')
<div id="section-profile" class="page-section visible" style="padding-top: 40px;" x-data="profileEngine()">
    <div class="section-wrapper" style="max-width: 1200px; margin: 0 auto;">
      
        <!-- Profile Header Box -->
        <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 20px; padding: 40px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 30px;">
            <div style="display: flex; gap: 30px; align-items: center; flex-wrap: wrap;">
                <div id="profile-avatar-container" style="width: 120px; height: 120px; min-width: 120px; border-radius: 50%; background: var(--dark2); border: 3px solid var(--accent); display: flex; align-items: center; justify-content: center; font-size: 40px; font-weight: bold; color: white; overflow: hidden; box-shadow: 0 10px 20px rgba(0,0,0,0.3);">
                    @if($user->avatar_path)
                        <img src="{{ asset('storage/' . $user->avatar_path) }}" style="width:100%; height:100%; object-fit:cover;">
                    @else
                        <span>{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    @endif
                </div>
                <div>
                    <h2 style="color: white; font-size: 28px; font-weight: 800; margin: 0;">{{ $user->name }}</h2>
                    <p style="color: var(--text-muted); font-size: 14px; margin: 4px 0 0 0;">{{ $user->email }}</p>
                    <p style="color: var(--accent); font-size: 12px; font-weight: bold; margin-top: 8px; text-transform: uppercase; letter-spacing: 1px;">✓ Alumno Certificado</p>
                    <button @click="openConfigModal()" class="btn btn-outline btn-sm" style="margin-top: 16px; font-size: 12px; padding: 8px 20px; border-radius: 8px;">⚙️ Editar Mi Perfil</button>
                </div>
            </div>
            
            <!-- Rango / Membership visual card -->
            <div style="padding: 25px 35px; border-radius: 16px; display: flex; flex-direction: column; justify-content: center; min-width: 250px; text-align: center;" :style="getMembershipStyle('bg')">
                <div style="font-size: 11px; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Plan de Acceso Actual</div>
                <h3 style="font-size: 22px; font-weight: 900; margin: 8px 0 0 0;" :style="getMembershipStyle('text')" x-text="getMembershipName()">Miembro Regular</h3>
                <a href="{{ route('memberships.index') }}" class="btn btn-primary btn-sm" style="margin-top: 15px; width: 100%; justify-content: center; text-decoration: none;">⭐ Mejorar Membresía</a>
            </div>
        </div>
  
        <!-- Sub-tabs Navigation -->
        <div style="display: flex; gap: 12px; margin-bottom: 30px; border-bottom: 2px solid rgba(255,255,255,0.05); padding-bottom: 12px; overflow-x: auto;">
            <button :class="{ 'active': activeTab === 'ruta' }" class="admin-tab" @click="activeTab = 'ruta'">🛣️ Mis Cursos</button>
            <button :class="{ 'active': activeTab === 'stats' }" class="admin-tab" @click="openStatsTab()">📈 Mi Rendimiento</button>
            <button :class="{ 'active': activeTab === 'logros' }" class="admin-tab" @click="activeTab = 'logros'">🏆 Mis Logros</button>
            <button :class="{ 'active': activeTab === 'notif' }" class="admin-tab" @click="activeTab = 'notif'">🔔 Alertas</button>
        </div>
  
        <!-- 1. TAB: MIS CURSOS -->
        <div x-show="activeTab === 'ruta'" style="display: block;">
            <div style="display: flex; flex-direction: column; gap: 20px;">
                @foreach($enrolledCourses as $idx => $course)
                    <div style="background: var(--dark2); border: 1px solid var(--border); border-radius: 12px; overflow:hidden; margin-bottom: 5px;">
                        
                        <div @click="toggleAccordion('{{ $idx }}')" style="cursor:pointer; padding: 20px; display: flex; justify-content: space-between; align-items: center; gap: 15px; flex-wrap: wrap; background:rgba(255,255,255,0.01);">
                            <div>
                                <span style="font-size: 9px; font-weight: bold; text-transform: uppercase; color: var(--accent); background: rgba(255,255,255,0.02); padding: 3px 8px; border-radius: 4px; border: 1px solid var(--border);">
                                    {{ $course->category->name ?? 'General' }}
                                </span>
                                <h4 style="color: white; font-size: 15px; font-weight: 700; margin: 8px 0 2px 0;">
                                    {{ $course->title }} <span style="color:var(--accent); font-size:12px; margin-left:6px; font-weight:normal;">▼ (Ver Clases)</span>
                                </h4>
                            </div>
                            <div @click.stop>
                                @if($course->is_completed)
                                    <div style="display:flex; gap:6px;">
                                        <a href="{{ route('courses.player', $course->id) }}" class="btn btn-outline btn-sm" style="padding: 6px 12px; font-size: 11px; text-decoration: none;">Repasar</a>
                                        <!-- Certificado generation local -->
                                        <a href="{{ route('profile.certificate', $course->id) }}" target="_blank" class="btn btn-success btn-sm" style="text-decoration:none; display:inline-flex; align-items:center; padding: 6px 14px; font-size: 11px; font-weight: bold; background:var(--success); color:white; border:none; border-radius:6px; box-shadow: 0 0 10px rgba(0, 229, 160, 0.4);">
                                            📜 Descargar Certificado
                                        </a>
                                    </div>
                                @else
                                    <a href="{{ route('courses.player', $course->id) }}" class="btn btn-primary btn-sm" style="padding: 8px 16px; font-size: 11px; font-weight: 700; text-decoration: none; display: inline-block;">
                                        ▶️ Continuar Estudio
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div style="padding: 0 20px 25px 20px;">
                            <div style="display: flex; justify-content: space-between; font-size: 11px; color: var(--text-muted); margin-bottom: 6px; font-weight: 600;">
                                <span>Progreso en esta asignatura</span>
                                <span style="font-weight: bold;" :style="'color: ' + ({{ $course->is_completed ? 'true' : 'false' }} ? 'var(--success)' : 'var(--accent)')">{{ $course->progress_percent }}%</span>
                            </div>
                            <div style="width: 100%; height: 5px; background: rgba(255,255,255,0.04); border-radius: 100px; overflow: hidden;">
                                <div style="width: {{ $course->progress_percent }}%; height: 100%; border-radius: 100px; transition: width 0.5s ease;" :style="'background: ' + ({{ $course->is_completed ? 'true' : 'false' }} ? 'var(--success)' : 'var(--accent)')"></div>
                            </div>
                        </div>

                        <!-- Accordion Temario details -->
                        <div id="accordion-{{ $idx }}" style="display:none; background: rgba(0,0,0,0.15); border-top:1px solid rgba(255,255,255,0.03); padding:15px 20px;">
                            <div style="font-size:11px; color:var(--text-muted); font-weight:700; text-transform:uppercase; margin-bottom:8px;">Plan de Clases Detallado:</div>
                            @foreach($course->modules as $mod)
                                @foreach($mod->lessons as $lesson)
                                    <div style="display:flex; justify-content:space-between; align-items:center; padding:10px 12px; border-bottom:1px solid rgba(255,255,255,0.02); font-size:12px;">
                                        <span style="color:var(--white80);"> Lección: {{ $lesson->title }}</span>
                                        <span style="font-size:11px; font-weight:bold;" :style="'color: ' + ({{ $course->is_completed ? 'true' : 'false' }} ? 'var(--success)' : 'var(--accent)')">
                                            {{ $course->is_completed ? '✓ Completada' : '⏱️ Pendiente' }}
                                        </span>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
  
        <!-- 2. TAB: MI RENDIMIENTO -->
        <div x-show="activeTab === 'stats'" style="display: none;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 20px;">
                <div style="background: var(--dark2); border: 1px solid var(--border); padding: 20px; border-radius: 14px;">
                    <div style="font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Promedio Global</div>
                    <div style="font-size: 28px; font-weight: 800; color: var(--success); margin-top: 5px;">{{ $avgGrade }}%</div>
                </div>
                <div style="background: var(--dark2); border: 1px solid var(--border); padding: 20px; border-radius: 14px;">
                    <div style="font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Cursos Adquiridos</div>
                    <div style="font-size: 28px; font-weight: 800; color: white; margin-top: 5px;">{{ count($enrolledCourses) }}</div>
                </div>
            </div>
            
            <!-- Curve Chart -->
            <div style="background: rgba(255,255,255,0.01); border: 1px solid rgba(255,255,255,0.03); border-radius: 16px; padding: 30px; height: 300px; display: flex; flex-direction: column;">
                <h3 style="color: white; font-size: 16px; margin-bottom: 20px;">📈 Curva de Aprendizaje</h3>
                <div style="flex: 1; position: relative; width: 100%; height: 100%;">
                    <canvas id="grafica-progreso-alumno"></canvas>
                </div>
            </div>
        </div>
  
        <!-- 3. TAB: MIS LOGROS -->
        <div x-show="activeTab === 'logros'" style="display: none;">
            <div style="background: rgba(255,255,255,0.01); border: 1px solid rgba(255,255,255,0.03); border-radius: 16px; padding: 30px;">
                <h3 style="color: white; font-size: 18px; font-weight: 700; margin-bottom: 24px;">🏆 Vitrina de Prestigio</h3>
                
                <!-- Achievements list calculated dynamically based on achievements data -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px;">
                    <template x-for="logro in getAchievementsList()">
                        <div :style="logro.unlocked ? 'background: rgba(41,171,255,0.08); border: 1px solid var(--accent);' : 'background: rgba(255,255,255,0.01); border: 1px dashed rgba(255,255,255,0.1);'" 
                             style="border-radius: 12px; padding: 16px; display: flex; gap: 14px; align-items: center; position:relative; transition: 0.3s;">
                            
                            <span style="position:absolute; top:8px; right:8px; font-size:10px;" x-show="!logro.unlocked">🔒</span>
                            <div style="font-size: 32px;" :style="!logro.unlocked && 'filter: grayscale(100%); opacity: 0.5;'" x-text="logro.icon"></div>
                            
                            <div style="width: 100%;">
                                <h4 style="color: var(--white); font-size: 14px; font-weight:700; margin-bottom: 4px;" :style="!logro.unlocked && 'opacity:0.4;'" x-text="logro.title"></h4>
                                <p style="color: var(--text-muted); font-size: 11px; line-height: 1.4; margin-bottom: 8px;" :style="!logro.unlocked && 'opacity:0.4;'" x-text="logro.desc"></p>
                                <div style="width:100%; background:rgba(255,255,255,0.05); height:6px; border-radius:10px; overflow:hidden;">
                                    <div :style="'width:' + logro.progress + '%;'" style="background:var(--accent); height:100%; border-radius:10px;"></div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
  
        <!-- 4. TAB: ALERTAS / NOTIFICACIONES -->
        <div x-show="activeTab === 'notif'" style="display: none;">
            <div style="background: rgba(255,255,255,0.01); border: 1px solid rgba(255,255,255,0.03); border-radius: 16px; padding: 30px;">
                <h3 style="color: white; font-size: 18px; font-weight: 700; margin: 0 0 24px 0;">🔔 Mensajes del Sistema</h3>
                
                <div style="display: flex; flex-direction: column; gap: 15px;">
                    @forelse($notifications as $notif)
                        <div id="notif-box-{{ $notif->id }}" style="padding: 16px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: flex-start; gap: 15px; position:relative;"
                             :style="'background: ' + getNotifBg('{{ $notif->type }}')">
                            <div>
                                <h4 style="color: white; font-size: 14px; margin-bottom: 4px; font-weight: 700;">{{ $notif->title }}</h4>
                                <p style="color: var(--white80); font-size: 12px; line-height: 1.5;">{{ $notif->message }}</p>
                                <small style="color: var(--text-muted); font-size: 10px; margin-top: 8px; display: block;">{{ $notif->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                            <button @click="deleteNotification('{{ $notif->id }}')" style="background: none; border: none; color: var(--text-muted); cursor: pointer; font-size: 14px;">✕</button>
                        </div>
                    @empty
                        <p style="color:var(--text-muted); font-size:13px;">No tienes alertas en tu bandeja de entrada.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div> 

    <!-- Edit Profile Settings Modal -->
    <div class="modal-overlay" id="config-perfil-modal" style="display: none; z-index: 9999;">
        <div class="modal-box" style="background: #121214; border: 1px solid var(--border); width: 100%; max-width: 400px; border-radius: 16px; padding: 24px; position: relative;">
            <button @click="closeConfigModal()" style="position: absolute; top: 16px; right: 16px; background: none; border: none; color: white; font-size: 20px; cursor: pointer;">✕</button>
            <h2 style="color: white; font-size: 18px; font-weight: 800; margin-bottom: 20px;">⚙️ Editar Perfil</h2>
            
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nombre Completo</label>
                    <input type="text" name="name" value="{{ $user->name }}" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Teléfono / WhatsApp</label>
                    <input type="text" name="phone" value="{{ $user->phone }}" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">País</label>
                    <input type="text" name="country" value="{{ $user->country }}" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Subir Foto de Perfil</label>
                    <input type="file" name="foto" accept="image/*" class="form-input" style="padding: 8px; background: rgba(255,255,255,0.05); cursor:pointer;">
                    <small style="color:var(--text-muted); font-size:10px;">La imagen se guardará en tu cuenta.</small>
                </div>
                
                <p style="font-size: 11px; color: var(--text-muted); margin-bottom: 16px;">* El correo electrónico principal no puede modificarse desde aquí por seguridad.</p>
                <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">💾 Guardar Cambios</button>
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
                    el.style.display = el.style.display === 'none' ? 'block' : 'none';
                }
            },

            openConfigModal() {
                document.getElementById('config-perfil-modal').style.display = 'flex';
            },

            closeConfigModal() {
                document.getElementById('config-perfil-modal').style.display = 'none';
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
                            borderColor: '#29ABFF',
                            backgroundColor: 'rgba(41, 171, 255, 0.1)',
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
                // Return 12 accomplishments config checked dynamically
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
