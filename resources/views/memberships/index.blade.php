@extends('layouts.app')

@section('title', 'Planes de Acceso — Edukan2')

@section('content')
<div id="section-memberships" class="page-section visible" style="padding: 100px 20px 60px 20px;" x-data="membershipsPublic()">
    <div class="section-wrapper" style="max-width: 1100px; margin: 0 auto;">
      
        <div class="section-header" style="text-align: center; margin-bottom: 50px;">
            <span class="section-label" style="background: rgba(0,82,255,0.08); color: var(--accent); padding: 4px 12px; border-radius: 100px; font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;">Planes de Acceso</span>
            <h2 class="section-title" style="font-family: var(--font-display); font-size: 36px; font-weight: 900; color: white; margin: 12px 0 6px 0;">Invierte en tu <span class="gradient-text">Mentalidad</span> de Alto Valor</h2>
            <p style="color: var(--text-muted); font-size: 14px; max-width: 550px; margin: 0 auto;">Elige el nivel de aceleración que necesitas para transformar tus habilidades en activos rentables.</p>
            
            <!-- Monthly/Yearly toggle switch -->
            <div style="display: flex; align-items: center; justify-content: center; gap: 14px; margin-top: 32px;">
                <span :style="!isYearly ? 'color: white; font-weight: 700;' : 'color: var(--text-muted);'" style="font-size: 13px;">Pago Mensual</span>
                <label class="switch" style="position: relative; display: inline-block; width: 44px; height: 24px;">
                    <input type="checkbox" x-model="isYearly" style="opacity: 0; width: 0; height: 0;">
                    <span class="slider" style="position: absolute; cursor: pointer; inset: 0; background-color: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.05); transition: .4s; border-radius: 34px;"></span>
                </label>
                <span :style="isYearly ? 'color: white; font-weight: 700;' : 'color: var(--text-muted);'" style="font-size: 13px;">
                    Pago Anual <span style="background: var(--success); color: white; font-size: 9px; padding: 2px 6px; border-radius: 4px; margin-left: 4px;">AHORRA 35%</span>
                </span>
            </div>
        </div>
  
        <!-- Memberships Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; align-items: stretch; margin-top: 20px;">
            @foreach($memberships as $plan)
                <div class="membership-card-visual" style="border-radius: 20px; padding: 35px 30px; display: flex; flex-direction: column; justify-content: space-between; position: relative; overflow: hidden; transition: var(--transition);" 
                     :style="getCardStyle('{{ $plan->visual_style }}')">
                    
                    <div>
                        @if($plan->visual_style === 'vip')
                            <div style="position: absolute; top: 15px; right: -30px; background: var(--gold); color: black; font-size: 9px; font-weight: 900; padding: 4px 30px; transform: rotate(45deg); text-transform: uppercase; letter-spacing: 1px;">PRESTIGE</div>
                        @elseif($plan->visual_style === 'pro')
                            <div style="position: absolute; top: 15px; right: -30px; background: var(--accent); color: white; font-size: 9px; font-weight: 900; padding: 4px 30px; transform: rotate(45deg); text-transform: uppercase; letter-spacing: 1px;">POPULAR</div>
                        @endif

                        <h3 style="font-family: var(--font-display); font-size: 20px; font-weight: 800; text-transform: uppercase;" :style="getTitleColor('{{ $plan->visual_style }}')">{{ $plan->name }}</h3>
                        
                        <!-- Dynamic Price -->
                        <div style="margin-top: 20px; margin-bottom: 25px;">
                            <span style="font-size: 44px; font-weight: 900; color: white;" x-text="isYearly ? '${{ round($plan->price_yearly) }}' : '${{ round($plan->price_monthly) }}'"></span>
                            <span style="color: var(--text-muted); font-size: 13px;" x-text="isYearly ? '/año' : '/mes'"></span>
                            <p style="color: var(--success); font-size: 11px; font-weight: 700; margin-top: 6px;" x-show="isYearly">✓ Pago anual diferido completo</p>
                        </div>
                        
                        <!-- Benefits list -->
                        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 12px; font-size: 13px; color: var(--white80); border-top: 1px solid rgba(255,255,255,0.05); padding-top: 25px;">
                            @foreach($plan->benefits as $benefit)
                                <li style="display: flex; align-items: center; gap: 10px;">
                                    <span style="color: var(--success);">✔</span>
                                    <span>{{ $benefit }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div style="margin-top: 35px; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 25px;">
                        @auth
                            <button @click="buyPlan('{{ $plan->name }}', isYearly ? '{{ $plan->price_yearly }}' : '{{ $plan->price_monthly }}')" 
                                    class="btn btn-lg" style="width: 100%; justify-content: center;"
                                    :style="getButtonStyle('{{ $plan->visual_style }}')">
                                Adquirir Plan →
                            </button>
                        @else
                            <button onclick="openAuthModal('login')" class="btn btn-lg" style="width: 100%; justify-content: center;"
                                    :style="getButtonStyle('{{ $plan->visual_style }}')">
                                Adquirir Plan →
                            </button>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@include('checkout.modal')

<style>
  .switch .slider:before {
    position: absolute; content: ""; height: 16px; width: 16px; left: 3px; bottom: 3px;
    background-color: white; transition: .4s; border-radius: 50%;
  }
  input:checked + .slider { background-color: var(--accent); }
  input:checked + .slider:before { transform: translateX(20px); }
</style>

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
                return 'background: var(--dark2); border: 1px solid var(--border);';
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
                    return 'background: var(--accent); color: white; border: none; font-weight: 800; box-shadow: 0 0 15px rgba(41, 171, 255, 0.3);';
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
