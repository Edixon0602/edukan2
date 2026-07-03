@extends('layouts.app')

@section('title', $course->title . ' — Reproductor LMS')

@section('content')
<div id="section-player" class="page-section visible" style="padding-top: 40px;" x-data="lmsPlayer()">
    <div class="section-wrapper">
        <!-- Header -->
        <div style="display:flex; align-items:center; gap:16px; margin-bottom:24px;">
            <a href="{{ route('courses.index') }}" class="btn btn-ghost btn-sm" style="text-decoration: none;">← Volver</a>
            <div>
                <div style="font-size:18px; font-weight:700;" x-text="courseTitle">Cargando Clase...</div>
            </div>
        </div>
        
        <!-- Player layout -->
        <div class="player-layout">
            <div>
                <!-- Video Screen -->
                <div class="video-player" style="position:relative; width:100%; height:0; padding-bottom:56.25%; background:#000; border-radius:12px; overflow:hidden;">
                    <div x-show="!currentVideoUrl" style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; flex-direction:column;">
                        <div style="font-size:72px;">🤖</div>
                        <p style="color:var(--text-muted); margin-top: 15px;">Selecciona una lección para comenzar</p>
                    </div>
                    <template x-if="currentVideoUrl">
                        <iframe :src="currentVideoUrl" style="position:absolute; inset:0; width:100%; height:100%; border:none;" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                    </template>
                </div>
                
                <!-- Player Sub-tabs Navigation -->
                <div style="margin-top:24px;">
                    <div style="display:flex; gap:4px; background:var(--dark2); border:1px solid var(--border); border-radius:12px; padding:4px; width:fit-content; margin-bottom:24px; flex-wrap: wrap;">
                        <div :class="{ 'active': activeTab === 'desc' }" class="admin-tab" @click="activeTab = 'desc'">Descripción</div>
                        <div :class="{ 'active': activeTab === 'comments' }" class="admin-tab" @click="activeTab = 'comments'">💬 Soporte VIP</div>
                        <div :class="{ 'active': activeTab === 'quiz' }" class="admin-tab" @click="openQuizTab()">📝 Evaluación</div>
                        <div :class="{ 'active': activeTab === 'resources' }" class="admin-tab" @click="activeTab = 'resources'">📎 Recursos</div>
                    </div>
                    
                    <!-- Tab contents -->
                    <div id="player-desc" class="content-card" x-show="activeTab === 'desc'" style="display:block;">
                        <p x-text="lessonDescription || 'No hay descripción disponible para esta lección.'"></p>
                    </div>
                    
                    <div id="player-comments" class="content-card" x-show="activeTab === 'comments'" style="display:none;">
                        <h3>💬 Canal de Soporte Privado</h3>
                        <p style="margin-bottom: 15px;">Si tienes dudas sobre esta lección, conéctate directamente con nuestro equipo de mentores vía WhatsApp.</p>
                        <a href="https://wa.me/584245318103" target="_blank" class="btn btn-success" style="text-decoration: none; display: inline-block;">
                            🟢 Abrir Chat de Profesor
                        </a>
                    </div>
                    
                    <div id="player-quiz" class="content-card" x-show="activeTab === 'quiz'" style="display:none;">
                        <!-- Quizzes evaluated dynamically -->
                        <div x-show="quizCompleted">
                            <div style="text-align:center; padding:30px; background:rgba(57, 84, 68, 0.05); border:1px solid rgba(46,204,113,0.3); border-radius:12px;">
                                <h2 style="color:#2ecc71; margin-bottom: 10px;">✅ Evaluación Completada</h2>
                                <p style="color:white; font-size: 14px;">Ya realizaste este examen y tu calificación final es de <b style="font-size: 18px; color: #2ecc71;" x-text="quizGrade + '%'"></b>.</p>
                            </div>
                        </div>
                        
                        <div x-show="!quizCompleted && !hasQuizzes">
                            <p style="color:var(--text-muted);">Este módulo no tiene una evaluación configurada.</p>
                        </div>
                        
                        <div x-show="!quizCompleted && hasQuizzes">
                            <h3>📝 Evaluación Obligatoria</h3>
                            <form @submit.prevent="submitQuiz()" id="quiz-submission-form">
                                <template x-for="(q, idx) in currentQuizzes" :key="q.id">
                                    <div style="margin-top:20px; background: rgba(255,255,255,0.01); padding: 15px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                                        <b x-text="(idx + 1) + '. ' + q.question"></b>
                                        
                                        <!-- Selection option -->
                                        <template x-if="q.type === 'seleccion'">
                                            <div style="margin-top: 10px;">
                                                <label style="display:block; margin: 6px 0;"><input type="radio" :name="'question_' + q.id" value="A" required> A) <span x-text="q.options.A"></span></label>
                                                <label style="display:block; margin: 6px 0;"><input type="radio" :name="'question_' + q.id" value="B" required> B) <span x-text="q.options.B"></span></label>
                                            </div>
                                        </template>

                                        <!-- True or False option -->
                                        <template x-if="q.type === 'verdadero_falso'">
                                            <div style="margin-top: 10px;">
                                                <label style="display:block; margin: 6px 0;"><input type="radio" :name="'question_' + q.id" value="Verdadero" required> Verdadero</label>
                                                <label style="display:block; margin: 6px 0;"><input type="radio" :name="'question_' + q.id" value="Falso" required> Falso</label>
                                            </div>
                                        </template>

                                        <!-- Written answer option -->
                                        <template x-if="q.type === 'desarrollo'">
                                            <div style="margin-top: 10px;">
                                                <textarea :name="'question_' + q.id" style="width:100%; height:80px; background: rgba(255,255,255,0.05); color: white; border: 1px solid var(--border); border-radius: 6px; padding: 10px; resize: none;" required placeholder="Escribe tu respuesta detalladamente..."></textarea>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                <button type="submit" class="btn btn-primary" style="margin-top:20px; width:100%; justify-content:center;">
                                    🚀 Enviar Respuestas Definitivas
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div id="player-resources" class="content-card" x-show="activeTab === 'resources'" style="display:none;">
                        <h3>📎 Recursos de Descarga</h3>
                        <ul style="list-style: none; padding: 0; margin-top: 15px;" x-show="lessonResources && lessonResources.length > 0">
                            <template x-for="res in lessonResources">
                                <li style="margin-bottom:10px;">
                                    <a :href="res.url" target="_blank" style="color:var(--accent); font-weight:600; text-decoration:none;" x-text="'🔗 ' + res.name"></a>
                                </li>
                            </template>
                        </ul>
                        <p x-show="!lessonResources || lessonResources.length === 0" style="color:var(--text-muted);">No hay recursos adjuntos para esta lección.</p>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar Navigation (Curriculum) -->
            <div class="curriculum-panel">
                <div class="curriculum-header">📚 Módulos del Curso</div>
                @foreach($course->modules as $modIdx => $mod)
                    <div style="padding:10px 8px; color:var(--accent); font-weight:700; background: rgba(255,255,255,0.02); border-bottom: 1px solid rgba(255,255,255,0.04);">
                        {{ $mod->title }}
                    </div>
                    @foreach($mod->lessons as $lessonIdx => $lesson)
                        <div :class="{ 'active': currentLessonId === {{ $lesson->id }} }" class="module-item" style="padding:10px 15px; cursor:pointer; font-size:13px; color:white; transition: var(--transition);" 
                             @click="playLesson({{ $lesson->id }}, '{{ $lesson->title }}', '{{ $lesson->video_url }}', '{{ $lesson->description }}', {{ json_encode($lesson->resources) }}, {{ $mod->id }}, '{{ $mod->title }}')">
                            ▶️ {{ $lesson->title }}
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>

        <!-- Student Reviews Panel -->
        <div id="curso-reviews-wrapper" style="margin-top: 40px; background: rgba(255,255,255,0.01); padding: 30px; border-radius: 16px; border: 1px solid rgba(255,255,255,0.05);" x-data="reviewsEngine('{{ $course->id }}')">
            <h3 style="color: white; font-size: 20px; margin-bottom: 20px;">⭐ Opiniones de los Estudiantes</h3>
        
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 30px;">
                <h2 style="font-size: 48px; color: white; margin: 0; font-weight: 900; letter-spacing: -2px;" x-text="avgRating">0.0</h2>
                <div>
                    <div style="color: #FFD700; font-size: 22px; letter-spacing: 2px;" x-text="getStarsDisplay(avgRating)"></div>
                    <p style="color: var(--text-muted); font-size: 13px; margin: 5px 0 0 0; font-weight: 600;" x-text="totalReviews + ' valoraciones'"></p>
                </div>
            </div>
        
            <!-- Leave a Review Form -->
            @auth
                <div id="form-dejar-resena" style="background: var(--dark2); padding: 20px; border-radius: 12px; margin-bottom: 30px; border: 1px solid var(--border);">
                    <h4 style="color: white; margin: 0 0 10px 0; font-size: 14px;">Deja tu calificación oficial:</h4>
                    <div style="font-size: 28px; cursor: pointer; color: #444; margin-bottom: 15px; display: flex; gap: 5px;">
                        <template x-for="star in [1,2,3,4,5]">
                            <span :style="star <= userStars ? 'color: #FFD700;' : 'color: #444;'" @click="userStars = star">★</span>
                        </template>
                    </div>
                    <textarea x-model="userComment" placeholder="¿Qué te pareció este curso? Escribe tu experiencia aquí..." style="width: 100%; height: 80px; background: rgba(255,255,255,0.02); border: 1px solid var(--border); color: white; padding: 12px; border-radius: 8px; margin-bottom: 15px; font-family: inherit; resize: none;"></textarea>
                    <button @click="submitReview()" class="btn btn-primary btn-sm" style="width: 100%; justify-content: center;">🚀 Publicar mi Opinión</button>
                </div>
            @endauth
        
            <!-- Review lists -->
            <div style="display: flex; flex-direction: column; gap: 16px;">
                <template x-for="r in reviewsList" :key="r.id">
                    <div style="padding:15px; background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.04); border-radius:10px;">
                        <div style="display:flex; justify-content:space-between; margin-bottom:8px; flex-wrap:wrap; gap:5px;">
                            <strong style="color:white; font-size:13px;" x-text="r.user_name"></strong>
                            <div style="color:#FFD700; font-size:12px;" x-text="getStarsDisplay(r.stars)"></div>
                        </div>
                        <p style="color:var(--white80); font-size:13px; line-height:1.5; font-style:italic;" x-text="'\u201c' + r.comment + '\u201d'"></p>
                        <small style="color:var(--text-muted); font-size:10px; display:block; margin-top:8px;" x-text="r.date"></small>
                    </div>
                </template>
                <p x-show="reviewsList.length === 0" style="color: var(--text-muted); font-size: 13px;">Aún no hay opiniones sobre este curso. ¡Sé el primero en dejar una!</p>
            </div>
        </div>
    </div>
</div>

<script>
    function lmsPlayer() {
        return {
            courseTitle: '{{ $course->title }}',
            currentLessonId: null,
            currentVideoUrl: '',
            lessonDescription: '',
            lessonResources: [],
            activeTab: 'desc',
            
            // Quiz States
            currentModuleId: null,
            currentModuleTitle: '',
            currentQuizzes: [],
            hasQuizzes: false,
            quizCompleted: false,
            quizGrade: 0,

            init() {
                // Autoplay first lesson if available
                @if($course->modules->count() > 0 && $course->modules->first()->lessons->count() > 0)
                    const firstLesson = {!! json_encode($course->modules->first()->lessons->first()) !!};
                    const firstModule = {!! json_encode($course->modules->first()) !!};
                    this.playLesson(
                        firstLesson.id, 
                        firstLesson.title, 
                        firstLesson.video_url, 
                        firstLesson.description, 
                        firstLesson.resources, 
                        firstModule.id, 
                        firstModule.title
                    );
                @endif
            },

            playLesson(id, title, videoUrl, description, resources, moduleId, moduleTitle) {
                this.currentLessonId = id;
                this.courseTitle = title;
                this.lessonDescription = description;
                this.lessonResources = resources || [];
                this.currentModuleId = moduleId;
                this.currentModuleTitle = moduleTitle;
                
                // Embed conversion if youtube / vimeo, etc.
                let embedUrl = videoUrl;
                if (videoUrl.includes('youtube.com/watch')) {
                    const videoId = new URL(videoUrl).searchParams.get('v');
                    embedUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
                } else if (videoUrl.includes('youtu.be/')) {
                    const videoId = videoUrl.split('/').pop();
                    embedUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
                }
                this.currentVideoUrl = embedUrl;
                this.quizCompleted = false;
            },

            openQuizTab() {
                this.activeTab = 'quiz';
                if (!this.currentModuleId) return;

                // Check if user already took this module quiz
                fetch(`/api/user/grades/${this.currentModuleId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.completed) {
                            this.quizCompleted = true;
                            this.quizGrade = data.grade;
                        } else {
                            this.quizCompleted = false;
                            this.loadQuizzes();
                        }
                    });
            },

            loadQuizzes() {
                fetch(`/api/modules/${this.currentModuleId}/quizzes`)
                    .then(res => res.json())
                    .then(data => {
                        this.currentQuizzes = data;
                        this.hasQuizzes = data.length > 0;
                    });
            },

            submitQuiz() {
                const form = document.getElementById('quiz-submission-form');
                const formData = new FormData(form);
                
                const data = {};
                formData.forEach((value, key) => { data[key] = value; });

                fetch(`/api/courses/{{ $course->id }}/modules/${this.currentModuleId}/evaluate`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.csrfToken
                    },
                    body: JSON.stringify(data)
                })
                .then(res => res.json())
                .then(result => {
                    if (result.status === 'success') {
                        showGlobalNotification(result.message);
                        this.quizCompleted = true;
                        this.quizGrade = result.grade;
                    } else {
                        showGlobalNotification(result.message, true);
                    }
                })
                .catch(() => {
                    showGlobalNotification('Error al enviar la evaluación.', true);
                });
            }
        }
    }

    function reviewsEngine(courseId) {
        return {
            courseId: courseId,
            avgRating: '{{ $averageRating }}',
            totalReviews: {{ $reviewsCount }},
            reviewsList: [],
            userStars: 0,
            userComment: '',

            init() {
                this.loadReviews();
            },

            loadReviews() {
                fetch(`/api/courses/${this.courseId}/reviews`)
                    .then(res => res.json())
                    .then(data => {
                        this.reviewsList = data;
                    });
            },

            getStarsDisplay(stars) {
                const count = Math.round(parseFloat(stars));
                return '★'.repeat(count) + '☆'.repeat(5 - count);
            },

            submitReview() {
                if (this.userStars === 0) {
                    showGlobalNotification('⚠️ Por favor selecciona una puntuación', true);
                    return;
                }
                if (this.userComment.trim().length < 5) {
                    showGlobalNotification('⚠️ Escribe un comentario descriptivo', true);
                    return;
                }

                fetch(`/api/courses/${this.courseId}/reviews`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.csrfToken
                    },
                    body: JSON.stringify({
                        stars: this.userStars,
                        comment: this.userComment
                    })
                })
                .then(res => res.json())
                .then(data => {
                    showGlobalNotification(data.message);
                    this.userStars = 0;
                    this.userComment = '';
                    this.loadReviews();
                    
                    // Reload Average stats
                    location.reload();
                })
                .catch(() => {
                    showGlobalNotification('Error al guardar reseña.', true);
                });
            }
        }
    }
</script>
@endsection
