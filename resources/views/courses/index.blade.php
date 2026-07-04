@extends('layouts.app')

@section('title', 'Cursos Premium — Edukan2')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-12" x-data="coursesCatalog()">
    <!-- Header -->
    <div class="mb-10">
        <h2 class="font-display font-black text-3xl text-white">Nuestros <span class="text-brand-accent">Cursos Premium</span></h2>
    </div>
    
    <!-- Filters Bar (Flat tabs) -->
    <div class="flex gap-4 border-b border-gray-800 pb-1 mb-8 overflow-x-auto scrollbar-none">
        <button :class="activeCategory === 'todos' ? 'text-white border-b-2 border-brand-accent font-bold pb-2' : 'text-white/60 hover:text-white pb-2'" class="text-xs font-bold uppercase tracking-wider transition-all cursor-pointer" @click="filterCategory('todos')">Todos</button>
        @foreach($categories as $cat)
            <button :class="activeCategory === '{{ $cat->id }}' ? 'text-white border-b-2 border-brand-accent font-bold pb-2' : 'text-white/60 hover:text-white pb-2'" class="text-xs font-bold uppercase tracking-wider transition-all cursor-pointer" @click="filterCategory('{{ $cat->id }}')">{{ $cat->name }}</button>
        @endforeach
    </div>
    
    <!-- Courses Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($courses as $course)
            <div class="bg-brand-dark2 border border-gray-800 hover:border-gray-700 rounded-2xl p-5 flex flex-col justify-between transition-all duration-300 shadow-xl" x-show="activeCategory === 'todos' || activeCategory === '{{ $course->category_id }}'">
                <div>
                    <!-- Flyer wrapper -->
                    <div class="aspect-video w-full rounded-xl overflow-hidden bg-brand-dark relative">
                        <img src="{{ $course->flyer_path ? $course->flyer_path : 'https://via.placeholder.com/300' }}" class="w-full h-full object-cover">
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
                <div class="flex justify-between items-center border-t border-gray-800 pt-4 mt-auto">
                    <span class="font-display font-black text-brand-gold text-base">${{ $course->price }}</span>
                    @auth
                        <button class="bg-brand-accent hover:bg-brand-accent/90 text-white font-bold px-4 py-2 rounded-lg text-xs transition-all cursor-pointer" @click="evaluateEntry('{{ $course->id }}', '{{ $course->title }}', '{{ $course->price }}', '{{ auth()->user()->membership }}', '{{ $course->required_membership }}')">
                            Ingresar
                        </button>
                    @else
                        <button class="bg-brand-accent hover:bg-brand-accent/90 text-white font-bold px-4 py-2 rounded-lg text-xs transition-all cursor-pointer" onclick="openAuthModal('login')">
                            Ingresar
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
