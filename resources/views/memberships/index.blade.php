@extends('layouts.app')

@section('title', 'Membresías VIP — Edukan2')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-12" x-data="membershipsManager()">
    <!-- Header -->
    <div class="text-center max-w-xl mx-auto mb-16">
        <h2 class="font-display font-black text-3xl sm:text-4xl text-white mb-4">Elige tu Plan de Aceleración</h2>
        <p class="text-brand-text-muted text-sm leading-relaxed">Únete a nuestra membresía y desbloquea el catálogo completo de rutas formativas, asesorías semanales y comunidad.</p>
        
        <!-- Toggle button for monthly / annual billing (Flat tabs style) -->
        <div class="flex gap-6 justify-center mt-8 text-xs font-bold uppercase tracking-wider border-b border-gray-800 pb-1 w-fit mx-auto">
            <button :class="billingCycle === 'monthly' ? 'text-brand-accent border-b-2 border-brand-accent pb-2' : 'text-white/60 hover:text-white pb-2'" class="transition-all cursor-pointer" @click="billingCycle = 'monthly'">Mensual</button>
            <button :class="billingCycle === 'annual' ? 'text-brand-accent border-b-2 border-brand-accent pb-2' : 'text-white/60 hover:text-white pb-2'" class="transition-all cursor-pointer" @click="billingCycle = 'annual'">Anual (Ahorro 20%)</button>
        </div>
    </div>

    <!-- Pricing Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @foreach($plans as $plan)
            <div :class="billingCycle === 'monthly' ? '' : ''" class="bg-brand-dark2 border rounded-2xl p-6 flex flex-col justify-between shadow-xl transition-all duration-300 relative {{ ($plan->level === 'pro' || $plan->level === 'vip') ? 'border-brand-accent' : 'border-gray-800' }}">
                <div>
                    <!-- Name & Subtitle -->
                    <span class="font-display text-[10px] text-brand-accent font-black tracking-wider uppercase block mb-2">{{ $plan->level }}</span>
                    <h3 class="font-display font-black text-xl text-white mb-4">{{ $plan->name }}</h3>
                    
                    <!-- Cost -->
                    <div class="mb-6 flex items-baseline gap-1">
                        <span class="font-display font-black text-3xl text-white" x-text="billingCycle === 'monthly' ? '${{ $plan->price_monthly }}' : '${{ $plan->price_annual }}'"></span>
                        <span class="text-brand-text-muted text-xs font-semibold" x-text="billingCycle === 'monthly' ? '/mes' : '/año'"></span>
                    </div>

                    <!-- Core capabilities / items list -->
                    <ul class="list-none p-0 m-0 flex flex-col gap-3 text-xs sm:text-sm mb-8 border-t border-gray-800 pt-6">
                        @foreach($plan->capabilities as $cap)
                            <li class="flex items-start gap-2.5 text-white/80">
                                <svg class="w-4 h-4 text-brand-success shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>{{ $cap }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Footer button -->
                <div>
                    @auth
                        @if(auth()->user()->membership === $plan->level)
                            <button disabled class="w-full bg-brand-success/10 text-brand-success font-bold py-3 rounded-lg text-xs cursor-default">
                                Plan Actual Activo
                            </button>
                        @else
                            <button @click="purchaseMembership('{{ $plan->id }}', '{{ $plan->name }}')" class="w-full bg-brand-accent hover:bg-brand-accent/90 text-white font-bold py-3 rounded-lg text-xs transition-all cursor-pointer">
                                Adquirir Membresía
                            </button>
                        @endif
                    @else
                        <button onclick="openAuthModal('login')" class="w-full bg-brand-accent hover:bg-brand-accent/90 text-white font-bold py-3 rounded-lg text-xs transition-all cursor-pointer">
                            Adquirir Membresía
                        </button>
                    @endauth
                </div>
            </div>
        @endforeach
    </div>
</div>

@include('checkout.modal')

<script>
    function membershipsManager() {
        return {
            billingCycle: 'monthly',

            purchaseMembership(planId, planName) {
                const price = this.billingCycle === 'monthly' ? 
                    (planName.includes('Estandar') ? '19.99' : planName.includes('Pro') ? '49.99' : '99.99') : 
                    (planName.includes('Estandar') ? '190.00' : planName.includes('Pro') ? '480.00' : '960.00');
                
                openCheckoutModal(planName, price, planId, true);
            }
        }
    }
</script>
@endsection
