@extends('layouts.app')

@section('title', 'Edukan2 — Ecosistema de Aceleración de Mentes Divergentes')

@section('content')
<div id="section-home" class="page-section visible" style="padding-top: 50px;">
    <div id="hero">
        <div class="hero-grid">
            
            <div>
                <div class="hero-badge" style="background: rgba(0, 82, 255, 0.1); border: 1px solid rgba(0, 82, 255, 0.3); width: fit-content;">
                    <div class="hero-badge-dot" style="background: #00f2fe;"></div>
                    <span style="color: #00f2fe;">Ecosistema de Aceleración 2026</span>
                </div>
                <h1 class="hero-title" style="font-size: clamp(2.5rem, 4.5vw, 4rem); line-height: 1.05;">
                    Escala tus ingresos con <span class="gradient-text">Habilidades</span><br>de <span class="gold-text">Alto Valor.</span>
                </h1>
                <p class="hero-subtitle" style="font-size: 16px; color: var(--text-muted); max-width: 90%; margin-top: 15px; margin-bottom: 25px;">
                    Únete a la plataforma que está transformando mentes divergentes en fundadores rentables. Sin teoría de relleno, 100% ejecución estratégica.
                </p>
                
                <div class="hero-actions" style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <a href="{{ route('courses.index') }}" class="btn btn-primary btn-lg" style="font-size: 15px; text-decoration: none; display: inline-flex; align-items: center;">
                        Comenzar Ahora →
                    </a>
                    
                    <a href="https://t.me/tu_canal_aqui" target="_blank" class="btn btn-outline btn-lg" style="border-color: #00f2fe; color: #00f2fe; background: rgba(0, 242, 254, 0.05); font-size: 15px; text-decoration: none; display: inline-flex; align-items: center;">
                        <span style="font-size: 18px; margin-right: 8px;">✈️</span> Comunidad Gratis
                    </a>
                </div>

                <!-- Proof Social Metrics -->
                <div style="display: flex; gap: 30px; margin-top: 40px; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 24px;">
                    <div>
                        <h4 style="font-family: var(--font-display); font-size: 26px; font-weight: 900; color: white;">{{ $alumnos ?? '1,250+' }}</h4>
                        <p style="font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">Alumnos Activos</p>
                    </div>
                    <div>
                        <h4 style="font-family: var(--font-display); font-size: 26px; font-weight: 900; color: var(--success);">{{ $exito ?? '98%' }}</h4>
                        <p style="font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">Tasa de Éxito</p>
                    </div>
                    <div>
                        <h4 style="font-family: var(--font-display); font-size: 26px; font-weight: 900; color: #00f2fe;">{{ $paises ?? '15+' }}</h4>
                        <p style="font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">Países Alcanzados</p>
                    </div>
                </div>
            </div>

            <!-- Hero Visual Cards -->
            <div class="hero-visual">
                <div style="display: grid; gap: 16px;">
                    
                    <div class="hero-card" style="background: rgba(16, 20, 35, 0.7); backdrop-filter: blur(20px); border: 1px solid rgba(0, 242, 254, 0.2); border-radius: 20px; padding: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.4);">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 12px;">
                            <span style="font-family: var(--font-display); font-size: 11px; color: #00f2fe; font-weight: 800; letter-spacing: 1px;">METODOLOGÍA 100% PRÁCTICA</span>
                            <span style="background: rgba(0, 242, 254, 0.1); color: #00f2fe; padding: 4px 10px; border-radius: 100px; font-size: 10px; font-weight: bold;">⚡ Acción Inmediata</span>
                        </div>
                        <h3 style="color: white; font-size: 18px; margin-bottom: 8px;">Aprende. Ejecuta. Factura.</h3>
                        <p style="color: var(--text-muted); font-size: 13px; line-height: 1.5;">Olvídate de la teoría académica aburrida. Nuestros programas están diseñados con casos de estudio reales para que apliques lo aprendido desde el día uno.</p>
                    </div>
        
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div class="hero-visual-card-sub" style="background: rgba(16, 20, 35, 0.7); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 20px; padding: 20px; transition: var(--transition); cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                            <div style="font-size: 26px; margin-bottom: 10px;">🌍</div>
                            <h4 style="color: white; font-size: 14px; margin-bottom: 4px;">Networking VIP</h4>
                            <p style="color: var(--text-muted); font-size: 11px;">Conecta con fundadores e inversionistas de toda Latinoamérica.</p>
                        </div>
                        <div class="hero-visual-card-sub" style="background: rgba(16, 20, 35, 0.7); backdrop-filter: blur(20px); border: 1px solid rgba(255, 215, 0, 0.2); border-radius: 20px; padding: 20px; transition: var(--transition); cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                            <div style="font-size: 26px; margin-bottom: 10px;">👑</div>
                            <h4 style="color: white; font-size: 14px; margin-bottom: 4px;">Soporte Elite</h4>
                            <p style="color: var(--text-muted); font-size: 11px;">Mentores 24/7 para destrabar tu progreso cuando lo necesites.</p>
                        </div>
                    </div>
                </div>
                
                <div class="float-badge top-right" style="background: rgba(0, 229, 160, 0.1); border-color: rgba(0,229,160,0.3); color: var(--success);"><span class="float-dot" style="background: var(--success);"></span> Clases Verificadas</div>
            </div>
        </div>
    </div>
</div>

<!-- Premium Footer -->
<footer style="background: #0b0d17; border-top: 1px solid rgba(255,255,255,0.05); padding: 60px 20px 20px 20px; margin-top: 80px; font-family: 'DM Sans', sans-serif;">
    <div style="max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px; margin-bottom: 40px;">
        <div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px; text-decoration: none;">
                <div style="background: var(--primary); color: white; font-family: 'Orbitron', sans-serif; font-weight: 900; padding: 6px 12px; border-radius: 8px; font-size: 16px; box-shadow: 0 0 15px rgba(0,82,255,0.3);">E2</div>
                <span style="font-family: 'Orbitron', sans-serif; font-weight: 900; font-size: 20px; color: white; letter-spacing: -0.5px;">Edukan<span style="color: var(--accent);">2</span></span>
            </div>
            <p style="color: var(--text-muted); font-size: 13px; line-height: 1.6; max-width: 300px;">La central de aceleración de habilidades de alto valor para las Mentes Divergentes del ecosistema global.</p>
        </div>
        <div>
            <h4 style="color: white; font-family: 'Orbitron', sans-serif; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px;">🛣️ Rutas de Estudio</h4>
            <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 10px; font-size: 13px;">
                <li><a href="{{ route('courses.index') }}" style="color: var(--text-muted); text-decoration: none;">Automatización con IA</a></li>
                <li><a href="{{ route('courses.index') }}" style="color: var(--text-muted); text-decoration: none;">Importación Internacional</a></li>
                <li><a href="{{ route('courses.index') }}" style="color: var(--text-muted); text-decoration: none;">Negocios de Alta Rotación</a></li>
            </ul>
        </div>
        <div>
            <h4 style="color: white; font-family: 'Orbitron', sans-serif; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px;">🔒 Soporte Especializado</h4>
            <p style="color: var(--text-muted); font-size: 13px; line-height: 1.6; margin-bottom: 12px;">¿Tienes dudas o inconvenientes con tus accesos? Escríbenos directamente de forma inmediata.</p>
            <a href="https://wa.me/584245318103" target="_blank" class="btn btn-success btn-sm" style="background:#25d366; border:none; padding: 8px 16px; font-size: 12px; font-weight: bold; border-radius: 6px; text-decoration: none; display: inline-block;">🟢 Soporte Directo WhatsApp</a>
        </div>
    </div>
    <div style="max-width: 1200px; margin: 0 auto; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; font-size: 12px; color: var(--text-muted);">
        <span>© 2026 Edukan2 · La Universidad de las Mentes Divergentes. Todos los derechos reservados.</span>
        <div style="display: flex; gap: 20px;">
            <span style="color: rgba(255,255,255,0.2);">v1.4.0 Live</span>
        </div>
    </div>
</footer>
@endsection
