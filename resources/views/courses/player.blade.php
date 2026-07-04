@extends('layouts.app')

@section('title', $course->title . ' — Reproductor LMS')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-10" x-data="lmsPlayer()">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('courses.index') }}" class="bg-white/5 text-white/70 hover:bg-white/10 hover:text-white px-4 py-2 rounded-lg text-xs font-bold transition-all no-underline cursor-pointer">
            Volver
        </a>
        <div>
            <h2 class="font-display font-black text-xl text-white tracking-tight" x-text="courseTitle">Cargando Clase...</h2>
        </div>
    </div>
    
    <!-- Player layout -->
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-8">
        <!-- Video & Tab content block -->
        <div>
            <!-- Video Screen -->
            <div class="aspect-video w-full bg-black rounded-2xl overflow-hidden relative border border-gray-800 shadow-2xl">
                <div x-show="!currentVideoUrl" class="absolute inset-0 flex flex-col items-center justify-center bg-brand-dark2">
                    <p class="text-brand-text-muted text-sm font-semibold">Selecciona una lección para comenzar</p>
                </div>
                <template x-if="currentVideoUrl">
                    <iframe :src="currentVideoUrl" class="absolute inset-0 w-full h-full border-none" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                </template>
            </div>
            
            <!-- Player Sub-tabs Navigation (Flat tabs) -->
            <div class="mt-8">
                <!-- Tabs bar -->
                <div class="flex gap-6 border-b border-gray-800 pb-1 mb-6 flex-wrap">
                    <button :class="activeTab === 'desc' ? 'text-white border-b-2 border-brand-accent font-bold pb-2' : 'text-white/60 hover:text-white pb-2'" class="text-xs font-bold uppercase tracking-wider transition-all cursor-pointer" @click="activeTab = 'desc'">Descripción</button>
                    <button :class="activeTab === 'comments' ? 'text-white border-b-2 border-brand-accent font-bold pb-2' : 'text-white/60 hover:text-white pb-2'" class="text-xs font-bold uppercase tracking-wider transition-all cursor-pointer" @click="activeTab = 'comments'">Soporte VIP</button>
                    <button :class="activeTab === 'quiz' ? 'text-white border-b-2 border-brand-accent font-bold pb-2' : 'text-white/60 hover:text-white pb-2'" class="text-xs font-bold uppercase tracking-wider transition-all cursor-pointer" @click="openQuizTab()">Evaluación</button>
                    <button :class="activeTab === 'resources' ? 'text-white border-b-2 border-brand-accent font-bold pb-2' : 'text-white/60 hover:text-white pb-2'" class="text-xs font-bold uppercase tracking-wider transition-all cursor-pointer" @click="activeTab = 'resources'">Recursos</button>
                </div>
                
                <!-- Tab contents -->
                <div class="bg-brand-dark2 border border-gray-800 p-6 rounded-2xl min-h-[150px]">
                    <div x-show="activeTab === 'desc'">
                        <p class="text-brand-text-muted text-sm sm:text-base leading-relaxed" x-text="lessonDescription || 'No hay descripción disponible para esta lección.'"></p>
                    </div>
                    
                    <div x-show="activeTab === 'comments'" class="flex flex-col gap-4">
                        <h3 class="font-display font-black text-base text-white">Canal de Soporte Privado</h3>
                        <p class="text-brand-text-muted text-xs sm:text-sm leading-relaxed">
                            Si tienes dudas sobre esta lección o necesitas destrabar tu código, conéctate directamente con nuestro equipo de mentores vía WhatsApp.
                        </p>
                        <a href="https://wa.me/584245318103" target="_blank" class="bg-[#10b981] hover:bg-[#059669] text-white text-xs font-bold px-4 py-2.5 rounded-lg no-underline inline-block shadow-sm w-fit">
                            Abrir Chat de Profesor
                        </a>
                    </div>
                    
                    <div x-show="activeTab === 'quiz'">
                        <!-- Quizzes evaluated dynamically -->
                        <div x-show="quizCompleted">
                            <div class="text-center p-6 bg-brand-success/5 border border-brand-success/30 rounded-xl">
                                <h3 class="text-brand-success font-bold text-lg mb-2">Evaluación Completada</h3>
                                <p class="text-white text-xs sm:text-sm">Ya realizaste este examen y tu calificación final es de <b class="text-brand-success font-display font-black text-lg" x-text="quizGrade + '%'"></b>.</p>
                            </div>
                        </div>
                        
                        <div x-show="!quizCompleted && !hasQuizzes">
                            <p class="text-brand-text-muted text-sm">Este módulo no tiene una evaluación configurada.</p>
                        </div>
                        
                        <div x-show="!quizCompleted && hasQuizzes">
                            <h3 class="font-display font-black text-base text-white mb-4">Evaluación Obligatoria</h3>
                            <form @submit.prevent="submitQuiz()" id="quiz-submission-form" class="flex flex-col gap-4">
                                <template x-for="(q, idx) in currentQuizzes" :key="q.id">
                                    <div class="bg-white/[0.01] border border-gray-800 p-5 rounded-xl flex flex-col gap-3">
                                        <b class="text-sm text-white" x-text="(idx + 1) + '. ' + q.question"></b>
                                        
                                        <!-- Selection option -->
                                        <template x-if="q.type === 'seleccion'">
                                            <div class="flex flex-col gap-2 text-xs sm:text-sm text-white/80">
                                                <label class="flex items-center gap-2.5 cursor-pointer"><input type="radio" :name="'question_' + q.id" value="A" required class="accent-brand-accent"> A) <span x-text="q.options.A"></span></label>
                                                <label class="flex items-center gap-2.5 cursor-pointer"><input type="radio" :name="'question_' + q.id" value="B" required class="accent-brand-accent"> B) <span x-text="q.options.B"></span></label>
                                            </div>
                                        </template>
                                        
                                        <!-- True or False option -->
                                        <template x-if="q.type === 'verdadero_falso'">
                                            <div class="flex flex-col gap-2 text-xs sm:text-sm text-white/80">
                                                <label class="flex items-center gap-2.5 cursor-pointer"><input type="radio" :name="'question_' + q.id" value="Verdadero" required class="accent-brand-accent"> Verdadero</label>
                                                <label class="flex items-center gap-2.5 cursor-pointer"><input type="radio" :name="'question_' + q.id" value="Falso" required class="accent-brand-accent"> Falso</label>
                                            </div>
                                        </template>

                                        <!-- Written answer option -->
                                        <template x-if="q.type === 'desarrollo'">
                                            <div class="mt-1">
                                                <textarea :name="'question_' + q.id" class="w-full h-24 bg-white/5 border border-white/10 rounded-lg p-3 text-white text-xs sm:text-sm focus:border-brand-accent focus:outline-none resize-none" required placeholder="Escribe tu respuesta detalladamente..."></textarea>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                <button type="submit" class="w-full bg-brand-accent hover:bg-brand-accent/90 text-white font-bold py-3 rounded-lg text-sm transition-all mt-4 cursor-pointer">
                                    Enviar Respuestas Definitivas
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div x-show="activeTab === 'resources'">
                        <h3 class="font-display font-black text-base text-white mb-4">Recursos de Descarga</h3>
                        <ul class="list-none p-0 flex flex-col gap-2.5 text-xs sm:text-sm" x-show="lessonResources && lessonResources.length > 0">
                            <template x-for="res in lessonResources">
                                <li>
                                    <a :href="res.url" target="_blank" class="text-brand-accent font-bold hover:underline no-underline" x-text="res.name"></a>
                                </li>
                            </template>
                        </ul>
                        <p x-show="!lessonResources || lessonResources.length === 0" class="text-brand-text-muted text-sm">No hay recursos adjuntos para esta lección.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar Navigation (Curriculum) -->
        <div class="bg-brand-dark2 border border-gray-800 rounded-2xl overflow-hidden shadow-xl h-fit">
            <div class="bg-white/5 px-5 py-4 font-display font-black text-xs uppercase tracking-wider text-white border-b border-gray-800">Módulos del Curso</div>
            @foreach($course->modules as $modIdx => $mod)
                <div class="px-5 py-3 font-display font-bold text-xs text-[#0052ff] bg-white/[0.01] border-b border-gray-800">
                    {{ $mod->title }}
                </div>
                @foreach($mod->lessons as $lessonIdx => $lesson)
                    <div :class="currentLessonId === {{ $lesson->id }} ? 'bg-brand-accent/15 text-white border-l-2 border-brand-accent' : 'text-white/70 hover:bg-white/[0.02] hover:text-white border-gray-800'" 
                         class="px-6 py-3 cursor-pointer text-xs transition-all border-b flex items-center gap-2" 
                         @click="playLesson({{ $lesson->id }}, '{{ $lesson->title }}', '{{ $lesson->video_url }}', '{{ $lesson->description }}', {{ json_encode($lesson->resources) }}, {{ $mod->id }}, '{{ $mod->title }}')">
                        {{ $lesson->title }}
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>

    <!-- Student Reviews Panel -->
    <div class="mt-10 bg-brand-dark2 border border-gray-800 p-6 sm:p-8 rounded-2xl shadow-xl" x-data="reviewsEngine('{{ $course->id }}')">
        <h3 class="font-display font-black text-lg text-white mb-6">Opiniones de los Estudiantes</h3>
    
        <div class="flex items-center gap-4 mb-8">
            <h2 class="font-display font-black text-5xl text-white tracking-tighter" x-text="avgRating">0.0</h2>
            <div>
                <div class="text-brand-gold text-xl tracking-wide leading-none" x-text="getStarsDisplay(avgRating)"></div>
                <p class="text-brand-text-muted text-xs font-bold mt-1.5" x-text="totalReviews + ' valoraciones'"></p>
            </div>
        </div>
    
        <!-- Leave a Review Form -->
        @auth
            <div class="bg-brand-dark2 p-6 border border-gray-800 rounded-xl mb-8">
                <h4 class="text-white font-bold text-xs sm:text-sm mb-2">Deja tu calificación oficial:</h4>
                <div class="flex gap-1.5 text-2xl cursor-pointer text-white/20 mb-4">
                    <template x-for="star in [1,2,3,4,5]">
                        <span :class="star <= userStars ? 'text-brand-gold' : 'text-white/20'" @click="userStars = star">★</span>
                    </template>
                </div>
                <textarea x-model="userComment" placeholder="¿Qué te pareció este curso? Escribe tu experiencia aquí..." class="w-full h-24 bg-white/5 border border-white/10 rounded-xl p-4 text-white text-xs sm:text-sm focus:border-brand-accent focus:outline-none resize-none mb-4 font-body"></textarea>
                <button @click="submitReview()" class="w-full bg-brand-accent hover:bg-brand-accent/90 text-white font-bold py-2.5 rounded-lg text-xs transition-all cursor-pointer">Publicar mi Opinión</button>
            </div>
        @endauth
    
        <!-- Review lists -->
        <div class="flex flex-col gap-4">
            <template x-for="r in reviewsList" :key="r.id">
                <div class="p-5 bg-white/[0.01] border border-gray-800 rounded-xl">
                    <div class="flex justify-between items-center mb-3 flex-wrap gap-2">
                        <strong class="text-white text-xs font-bold" x-text="r.user_name"></strong>
                        <div class="text-brand-gold text-xs" x-text="getStarsDisplay(r.stars)"></div>
                    </div>
                    <p class="text-white/80 text-xs sm:text-sm leading-relaxed italic" x-text="'\u201c' + r.comment + '\u201d'"></p>
                    <small class="text-brand-text-muted text-[10px] block mt-3" x-text="r.date"></small>
                </div>
            </template>
            <p x-show="reviewsList.length === 0" class="text-brand-text-muted text-xs sm:text-sm">Aún no hay opiniones sobre este curso. ¡Sé el primero en dejar una!</p>
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
                    showGlobalNotification('Por favor selecciona una puntuación', true);
                    return;
                }
                if (this.userComment.trim().length < 5) {
                    showGlobalNotification('Escribe un comentario descriptivo', true);
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
                    
                    // Refresh parent context stats
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                })
                .catch(() => {
                    showGlobalNotification('Error al guardar reseña.', true);
                });
            }
        }
    }
</script>
@endsection
