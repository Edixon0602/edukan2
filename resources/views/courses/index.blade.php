@extends('layouts.app')

@section('title', 'Cursos Premium — Edukan2')

@section('content')
<div id="section-courses" class="page-section visible" style="padding-top: 50px;">
    <div class="section-wrapper" x-data="coursesCatalog()">
        <div class="section-header">
            <span class="section-label">Catálogo Completo</span>
            <h2 class="section-title">Nuestros <span style="color:var(--accent)">Cursos Premium</span></h2>
        </div>
        
        <!-- Filters Bar -->
        <div class="courses-filter" id="catalogo-filtros-bar" style="margin-bottom: 30px;">
            <button :class="{ 'active': activeCategory === 'todos' }" class="filter-btn" @click="filterCategory('todos')">Todos</button>
            @foreach($categories as $cat)
                <button :class="{ 'active': activeCategory === '{{ $cat->id }}' }" class="filter-btn" @click="filterCategory('{{ $cat->id }}')">{{ $cat->name }}</button>
            @endforeach
        </div>
        
        <!-- Courses Grid -->
        <div class="courses-grid" id="courses-grid">
            @foreach($courses as $course)
                <div class="course-card" x-show="activeCategory === 'todos' || activeCategory === '{{ $course->category_id }}'" style="background:var(--dark2); border:1px solid var(--border); border-radius:12px; padding:15px; display:flex; flex-direction:column; justify-content:space-between; margin-bottom:15px;">
                    <div>
                        <div style="width:100%; height:160px; border-radius:8px; overflow:hidden; background:#121214; position:relative;">
                            <img src="{{ $course->flyer_path ? asset('storage/' . $course->flyer_path) : 'https://via.placeholder.com/300' }}" style="width:100%; height:100%; object-fit:cover;">
                        </div>
                        <h3 style="margin-top:12px; color:white; font-size:16px; font-weight:700; margin-bottom:4px;">{{ $course->title }}</h3>
                        
                        <!-- Rating -->
                        <div style="display:flex; align-items:center; gap:6px; margin-bottom: 8px;">
                            <span style="color:#FFD700; font-size:14px; letter-spacing:1px;">★★★★★</span>
                            <span style="color:var(--text-muted); font-size:11px; font-weight:600;">({{ $course->average_rating }}) Excelente</span>
                        </div>

                        <p style="font-size:12px; color:var(--text-muted); line-height:1.5; margin-top:4px;">{{ $course->short_description }}</p>
                    </div>
                    
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:15px; padding-top:10px; border-top:1px solid rgba(255,255,255,0.03);">
                        <span style="font-weight:bold; color:var(--accent); font-size:15px;">${{ $course->price }}</span>
                        @auth
                            <button class="btn btn-primary btn-sm" style="padding:6px 14px; font-size:11px;" @click="evaluateEntry('{{ $course->id }}', '{{ $course->title }}', '{{ $course->price }}', '{{ auth()->user()->membership }}', '{{ $course->required_membership }}')">
                                Ingresar →
                            </button>
                        @else
                            <button class="btn btn-primary btn-sm" style="padding:6px 14px; font-size:11px;" onclick="openAuthModal('login')">
                                Ingresar →
                            </button>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>
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
