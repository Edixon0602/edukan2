@extends('layouts.app')

@section('title', 'Planes de Acceso — Edukan2')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-12 md:py-24" x-data="membershipsPublic()">
    <!-- Header -->
    <div class="text-center mb-12">
        <span class="bg-brand-accent/10 border border-brand-accent/20 text-[#00f2fe] text-[10px] font-bold px-4 py-1.5 rounded-full uppercase tracking-wider inline-block mb-3">Planes de Acceso</span>
        <h2 class="font-display font-black text-3xl sm:text-4xl text-white mb-3">Invierte en tu <span class="gradient-text">Mentalidad</span> de Alto Valor</h2>
        <p class="text-brand-text-muted text-xs sm:text-sm max-w-md mx-auto leading-relaxed">Elige el nivel de aceleración que necesitas para transformar tus habilidades en activos rentables.</p>
        
        <!-- Monthly/Yearly toggle switch -->
        <div class="flex items-center justify-center gap-4 mt-8">
            <span :class="!isYearly ? 'text-white font-bold' : 'text-brand-text-muted'" class="text-xs sm:text-sm transition-all">Pago Mensual</span>
            <button @click="isYearly = !isYearly" class="relative w-11 h-6 bg-white/10 rounded-full flex items-center p-0.5 cursor-pointer border border-white/5 transition-all">
                <span class="w-4.5 h-4.5 bg-brand-accent rounded-full transition-transform duration-200" :class="isYearly ? 'translate-x-5' : 'translate-x-0'"></span>
            </button>
            <span :class="isYearly ? 'text-white font-bold' : 'text-brand-text-muted'" class="text-xs sm:text-sm transition-all">
                Pago Anual <span class="bg-brand-success text-white text-[9px] px-2 py-0.5 rounded-md ml-1 font-bold">AHORRA 35%</span>
            </span>
        </div>
    </div>

    <!-- Memberships Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch mt-6">
        @foreach($memberships as $plan)
            <div class="rounded-3xl p-8 flex flex-col justify-between relative overflow-hidden transition-all duration-300 shadow-2xl hover:-translate-y-1" 
                 :style="getCardStyle('{{ $plan->visual_style }}')">
                
                <div>
                    <!-- Ribbons -->
                    @if($plan->visual_style === 'vip')
                        <div class="absolute top-4 -right-10 bg-brand-gold text-black text-[9px] font-black py-1 px-10 rotate-45 uppercase tracking-wider">PRESTIGE</div>
                    @elseif($plan->visual_style === 'pro')
                        <div class="absolute top-4 -right-10 bg-brand-accent text-white text-[9px] font-black py-1 px-10 rotate-45 uppercase tracking-wider">POPULAR</div>
                    @endif

                    <!-- Title -->
                    <h3 class="font-display font-black text-lg uppercase" :style="getTitleColor('{{ $plan->visual_style }}')">{{ $plan->name }}</h3>
                    
                    <!-- Dynamic Price -->
                    <div class="mt-5 mb-6 flex flex-col gap-1">
                        <div>
                            <span class="font-display font-black text-4xl sm:text-5xl text-white" x-text="isYearly ? '${{ round($plan->price_yearly) }}' : '${{ round($plan->price_monthly) }}'"></span>
                            <span class="text-brand-text-muted text-xs" x-text="isYearly ? '/año' : '/mes'"></span>
                        </div>
                        <p class="text-brand-success text-[10px] font-bold" x-show="isYearly">✓ Pago anual diferido completo</p>
                    </div>
                    
                    <!-- Benefits list -->
                    <ul class="list-none p-0 m-0 flex flex-col gap-3.5 text-xs sm:text-sm text-white/80 border-t border-white/5 pt-6">
                        @foreach($plan->benefits as $benefit)
                            <li class="flex items-center gap-2.5">
                                <span class="text-brand-success font-bold text-sm">✔</span>
                                <span>{{ $benefit }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Footer CTA -->
                <div class="mt-8 border-t border-white/5 pt-6">
                    @auth
                        <button @click="buyPlan('{{ $plan->name }}', isYearly ? '{{ $plan->price_yearly }}' : '{{ $plan->price_monthly }}')" 
                                class="w-full font-bold py-3 rounded-lg text-sm transition-all cursor-pointer text-center"
                                :style="getButtonStyle('{{ $plan->visual_style }}')">
                            Adquirir Plan →
                        </button>
                    @else
                        <button onclick="openAuthModal('login')" class="w-full font-bold py-3 rounded-lg text-sm transition-all cursor-pointer text-center"
                                :style="getButtonStyle('{{ $plan->visual_style }}')">
                            Adquirir Plan →
                        </button>
                    @endauth
                </div>
            </div>
        @endforeach
    </div>
</div>

@include('checkout.modal')

<script>
    function membershipsPublic() {
        return {
            isYearly: false,

            getCardStyle(style) {
                if (style === 'vip') {
                    return 'background: linear-gradient(135deg, #090b11 0%, #15110a 50%, #090b11 100%); border: 1px solid rgba(255, 215, 0, 0.25); box-shadow: 0 15px 35px rgba(255, 215, 0, 0.05);';
                } else if (style === 'pro') {
                    return 'background: linear-gradient(135deg, #0b111e 0%, #161a29 100%); border: 1px solid rgba(0, 82, 255, 0.3); box-shadow: 0 15px 35px rgba(0, 82, 255, 0.08);';
                }
                return 'background: var(--color-brand-dark2); border: 1px solid rgba(255,255,255,0.06);';
            },

            getTitleColor(style) {
                if (style === 'vip') return 'color: #ffd700;';
                if (style === 'pro') return 'color: #0052ff;';
                return 'color: white;';
            },

            getButtonStyle(style) {
                if (style === 'vip') {
                    return 'background: linear-gradient(90deg, #ffd700, #ff8c00); color: black; border: none; font-weight: 800; box-shadow: 0 0 15px rgba(255, 215, 0, 0.3);';
                } else if (style === 'pro') {
                    return 'background: var(--color-brand-accent); color: white; border: none; font-weight: 800; box-shadow: 0 0 15px rgba(0, 82, 255, 0.3);';
                }
                return 'background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white;';
            },

            buyPlan(name, price) {
                openCheckoutModal(name, price);
            }
        }
    }
</script>
@endsection
