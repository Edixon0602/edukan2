@extends('layouts.app')

@section('title', 'Cursos Premium — Edukan2')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-12" x-data="coursesCatalog()">
    <!-- Header -->
    <div class="mb-10">
        <span class="font-display text-[10px] text-[#00f2fe] font-black tracking-wider uppercase block mb-1">Catálogo Completo</span>
        <h2 class="font-display font-black text-3xl text-white">Nuestros <span class="text-brand-accent">Cursos Premium</span></h2>
    </div>
    
    <!-- Filters Bar -->
    <div class="flex gap-2.5 flex-wrap mb-8">
        <button :class="activeCategory === 'todos' ? 'bg-brand-accent text-white shadow-neon-blue border-brand-accent/20' : 'bg-white/5 text-white/70 border-white/5 hover:bg-white/10 hover:text-white'" class="border px-4 py-2 rounded-full text-xs font-bold transition-all cursor-pointer" @click="filterCategory('todos')">Todos</button>
        @foreach($categories as $cat)
            <button :class="activeCategory === '{{ $cat->id }}' ? 'bg-brand-accent text-white shadow-neon-blue border-brand-accent/20' : 'bg-white/5 text-white/70 border-white/5 hover:bg-white/10 hover:text-white'" class="border px-4 py-2 rounded-full text-xs font-bold transition-all cursor-pointer" @click="filterCategory('{{ $cat->id }}')">{{ $cat->name }}</button>
        @endforeach
    </div>
    
    <!-- Courses Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($courses as $course)
            <div class="bg-brand-dark2 border border-white/5 hover:border-[#00f2fe]/20 rounded-2xl p-5 flex flex-col justify-between transition-all duration-300 shadow-xl hover:-translate-y-1" x-show="activeCategory === 'todos' || activeCategory === '{{ $course->category_id }}'">
                <div>
                    <!-- Flyer wrapper -->
                    <div class="aspect-video w-full rounded-xl overflow-hidden bg-brand-dark relative">
                        <img src="{{ $course->flyer_path ? asset('storage/' . $course->flyer_path) : 'https://via.placeholder.com/300' }}" class="w-full h-full object-cover">
                    </div>
                    
                    <!-- Title -->
                    <h3 class="mt-4 font-bold text-white text-base leading-tight mb-1">{{ $course->title }}</h3>
                    
                    <!-- Rating -->
                    <div class="flex items-center gap-1.5 mb-3">
                        <span class="text-brand-gold text-xs tracking-wide">★★★★★</span>
                        <span class="text-brand-text-muted text-[10px] font-bold">({{ $course->average_rating ?? '5.0' }}) Excelente</span>
                    </div>

                    <!-- Description -->
                    <p class="text-brand-text-muted text-xs sm:text-sm leading-relaxed mb-6">{{ $course->short_description }}</p>
                </div>
                
                <!-- Footer -->
                <div class="flex justify-between items-center border-t border-white/5 pt-4 mt-auto">
                    <span class="font-display font-black text-brand-gold text-base">${{ $course->price }}</span>
                    @auth
                        <button class="bg-brand-accent hover:bg-brand-accent/90 text-white font-bold px-4 py-2 rounded-lg text-xs transition-all shadow-neon-blue cursor-pointer" @click="evaluateEntry('{{ $course->id }}', '{{ $course->title }}', '{{ $course->price }}', '{{ auth()->user()->membership }}', '{{ $course->required_membership }}')">
                            Ingresar →
                        </button>
                    @else
                        <button class="bg-brand-accent hover:bg-brand-accent/90 text-white font-bold px-4 py-2 rounded-lg text-xs transition-all shadow-neon-blue cursor-pointer" onclick="openAuthModal('login')">
                            Ingresar →
                        </button>
                    @endauth
                </div>
            </div>
        @endforeach
    </div>
</div>

@include('checkout.modal')

<script>
    function coursesCatalog() {
        return {
            activeCategory: 'todos',
            
            filterCategory(catId) {
                this.activeCategory = catId;
            },
            
            evaluateEntry(courseId, title, price, userMembership, requiredMembership) {
                const membershipLevels = { 'regular': 0, 'estandar': 1, 'pro': 2, 'vip': 3 };
                const userLvl = membershipLevels[userMembership] || 0;
                const reqLvl = membershipLevels[requiredMembership] || 0;

                // Admin bypass
                @if(auth()->check() && auth()->user()->isAdmin())
                    window.location.href = `/courses/${courseId}`;
                    return;
                @endif

                // If user membership is sufficient, let them enter directly
                if (userLvl >= reqLvl && reqLvl > 0) {
                    window.location.href = `/courses/${courseId}`;
                } else {
                    // Check if they are enrolled in the course manually
                    fetch(`/api/user/has-enrollment/${courseId}`, {
                        headers: {
                            'X-CSRF-TOKEN': window.csrfToken
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.enrolled) {
                            window.location.href = `/courses/${courseId}`;
                        } else {
                            // Open checkout modal
                            openCheckoutModal(title, price, courseId);
                        }
                    })
                    .catch(() => {
                        openCheckoutModal(title, price, courseId);
                    });
                }
            }
        }
    }
</script>
@endsection
