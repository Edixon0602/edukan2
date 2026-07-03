<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Edukan2 — La Universidad de las Mentes Divergentes')</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700;900&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,700;1,300&display=swap" rel="stylesheet">
    
    @vite(['resources/css/style.css'])
    
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#121214">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Edukan2">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>

    <!-- Notification Toast -->
    <div class="notification" id="global-notification" style="display: none; z-index: 99999;">
        <span class="notif-icon" id="global-notif-icon">✅</span>
        <span id="global-notif-text">Acción completada exitosamente</span>
    </div>

    <!-- Navigation Header -->
    <nav>
        <a class="nav-logo" href="{{ route('home') }}">
            <div class="logo" style="display:flex; align-items:center; gap:10px;">
                <svg width="32" height="32" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="logoGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#0052ff; stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#00f2fe; stop-opacity:1" />
                        </linearGradient>
                    </defs>
                    <rect width="100" height="100" rx="25" fill="rgba(255,255,255,0.05)" stroke="url(#logoGrad)" stroke-width="4"/>
                    <path d="M 70 30 L 35 30 L 35 50 L 60 50 L 60 65 L 35 65 L 35 85 L 75 85" fill="none" stroke="url(#logoGrad)" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M 60 30 L 75 15" fill="none" stroke="#00f2fe" stroke-width="8" stroke-linecap="round"/>
                </svg>
                <span style="font-family: 'Orbitron', sans-serif; font-weight: 900; font-size: 20px; letter-spacing: 1px; color: white;">
                    Edukan<span style="color: #00f2fe;">2</span>
                </span>
            </div>
        </a>
        
        <ul class="nav-links">
            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Inicio</a></li>
            <li><a href="{{ route('courses.index') }}" class="{{ request()->routeIs('courses.*') ? 'active' : '' }}">Cursos</a></li>
            <li><a href="{{ route('memberships.index') }}" class="{{ request()->routeIs('memberships.*') ? 'active' : '' }}">Membresías</a></li>
            @auth
                <li><a href="{{ route('profile.index') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">Mi Perfil</a></li>
                @if(auth()->user()->isAdmin())
                    <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.*') ? 'active' : '' }}" style="color: var(--gold);">⚙️ Admin</a></li>
                @endif
            @endauth
        </ul>
        
        <div class="nav-actions">
            <!-- Theme Switcher -->
            <label class="theme-switch" title="Cambiar a Modo Claro/Oscuro">
                <input type="checkbox" id="theme-toggle-chk" @change="toggleTheme()">
                <span class="theme-slider"><span title="Modo Día">☀️</span><span title="Modo Noche">🌙</span></span>
            </label>

            @auth
                <button class="btn btn-ghost btn-sm" onclick="window.location.href='{{ route('profile.index') }}#notif'" style="position:relative; padding: 6px 8px;">
                    🔔
                    @if(auth()->user()->systemNotifications()->where('is_read', false)->count() > 0)
                        <span style="position:absolute; top:-4px; right:-4px; background:var(--danger); width:16px; height:16px; border-radius:50%; font-size:10px; font-weight:bold; color:white; display:flex; align-items:center; justify-content:center;">
                            {{ auth()->user()->systemNotifications()->where('is_read', false)->count() }}
                        </span>
                    @endif
                </button>
                <span class="nav-user-email" style="color:var(--white80); font-size:13px; font-weight:600; margin:0 10px;">👤 {{ explode('@', auth()->user()->email)[0] }}</span>
                <form action="{{ route('logout.submit') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline btn-sm" style="padding: 6px 12px;">
                        <span class="hide-mobile">🔒 Salir</span>
                        <span class="show-mobile">🔒</span>
                    </button>
                </form>
            @else
                <button class="btn btn-ghost btn-sm" onclick="openAuthModal('login')">Iniciar Sesión</button>
                <button class="btn btn-primary btn-sm" onclick="openAuthModal('register')">Unirme →</button>
            @endauth
            
            <button id="btn-instalar-app" style="display:none; background: linear-gradient(90deg, #0052ff, #00f2fe); border:none; margin-left: 10px;" class="btn btn-primary btn-sm">
                📱 Instalar App
            </button>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main style="min-height: calc(100vh - 70px); padding-top: 70px; position: relative; z-index: 1;">
        @yield('content')
    </main>

    <!-- Mobile Navigation Bottom Bar -->
    <div class="mobile-bottom-nav">
        <a href="{{ route('home') }}" class="mobile-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <span class="icon">🏠</span><span class="text">Inicio</span>
        </a>
        <a href="{{ route('courses.index') }}" class="mobile-nav-item {{ request()->routeIs('courses.*') ? 'active' : '' }}">
            <span class="icon">📚</span><span class="text">Cursos</span>
        </a>
        <a href="{{ route('memberships.index') }}" class="mobile-nav-item {{ request()->routeIs('memberships.*') ? 'active' : '' }}">
            <span class="icon">💎</span><span class="text">Planes</span>
        </a>
        @auth
            <a href="{{ route('profile.index') }}" class="mobile-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <span class="icon">👤</span><span class="text">Perfil</span>
            </a>
        @endauth
    </div>

    <!-- Auth Modal (Login / Register) -->
    <div class="modal-overlay" id="auth-modal" style="display: none; z-index: 9999;">
        <div class="modal-box" style="background: rgba(16, 20, 35, 0.65) !important; backdrop-filter: blur(20px) !important; border: 1px solid rgba(0, 82, 255, 0.15) !important; box-shadow: 0 20px 50px rgba(0,0,0,0.3) !important;">
            <button class="modal-close" onclick="closeAuthModal()">✕</button>
            <div class="modal-title glow-text" id="auth-modal-title">Bienvenido de Vuelta</div>
            <div class="modal-subtitle" id="auth-modal-sub">Accede a tu cuenta y continúa aprendiendo</div>
        
            <div class="auth-tabs" style="background: rgba(255,255,255,0.02); padding: 4px; border-radius: 100px; border: 1px solid rgba(255,255,255,0.05); margin-bottom: 24px;">
                <div class="auth-tab active" id="tab-btn-login" onclick="switchAuthTab('login')">Iniciar Sesión</div>
                <div class="auth-tab" id="tab-btn-register" onclick="switchAuthTab('register')">Crear Cuenta</div>
            </div>
        
            <!-- Formulario de Iniciar Sesión -->
            <div id="login-form-wrapper">
                <form id="ajax-login-form" onsubmit="submitLogin(event)">
                    <div class="form-group">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" name="email" id="login-email" class="form-input" required placeholder="tu@email.com" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); color: white;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" id="login-pass" class="form-input" required placeholder="••••••••" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); color: white;">
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg" style="width:100%; justify-content:center; margin-top:8px; background: rgba(0, 82, 255, 0.2); border: 1px solid rgba(0, 82, 255, 0.4); color: #0052ff; font-weight: 700;">
                        Entrar a la Academia →
                    </button>
                    <p style="text-align:center; margin-top:16px; font-size:13px; color:var(--text-muted);">
                        ¿Olvidaste tu contraseña? <a href="#" style="color:var(--accent); font-weight:600;" onclick="alert('Funcionalidad de recuperación en desarrollo')">Recupérala aquí</a>
                    </p>
                </form>
            </div>
        
            <!-- Formulario de Registro -->
            <div id="register-form-wrapper" style="display:none;">
                <form id="ajax-register-form" onsubmit="submitRegister(event)">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        <div class="form-group">
                            <label class="form-label">Nombre Completo</label>
                            <input type="text" name="name" id="reg-nombre" class="form-input" required placeholder="Tu nombre" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); color: white;">
                        </div>
                        <div class="form-group">
                            <label class="form-label">País</label>
                            <select name="country" id="reg-pais" class="form-input" style="cursor:pointer; background: #111423; color: white; border: 1px solid rgba(255,255,255,0.08);">
                                <option value="Venezuela" selected>Venezuela</option>
                                <option value="Colombia">Colombia</option>
                                <option value="España">España</option>
                                <option value="Estados Unidos">Estados Unidos</option>
                                <option value="Chile">Chile</option>
                                <option value="Perú">Perú</option>
                                <option value="Argentina">Argentina</option>
                                <option value="México">México</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Número Telefónico (WhatsApp)</label>
                        <div style="display: grid; grid-template-columns: 110px 1fr; gap: 10px; width: 100%;">
                            <select id="reg-codigo-pais" class="form-input" style="cursor:pointer; background: #111423; color: white; border: 1px solid rgba(255,255,255,0.08); font-size: 12px;">
                                <option value="+58" selected>VE (+58)</option>
                                <option value="+57">CO (+57)</option>
                                <option value="+1">US (+1)</option>
                                <option value="+34">ES (+34)</option>
                                <option value="+56">CL (+56)</option>
                                <option value="+51">PE (+51)</option>
                                <option value="+54">AR (+54)</option>
                                <option value="+52">MX (+52)</option>
                            </select>
                            <input type="tel" name="phone_number" id="reg-telefono" class="form-input" required placeholder="4241234567" style="width: 100%; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); color: white;">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" name="email" id="reg-email" class="form-input" required placeholder="tu@email.com" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); color: white;">
                    </div>
                    
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        <div class="form-group">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="password" id="reg-pass" class="form-input" required placeholder="Mínimo 6 carac." style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); color: white;">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Repetir Contraseña</label>
                            <input type="password" name="password_confirmation" id="reg-pass-confirm" class="form-input" required placeholder="Repite tu clave" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); color: white;">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">¿Cómo nos encontraste?</label>
                        <select name="origin" id="reg-origen" class="form-input" style="cursor:pointer; background: #111423; color: white; border: 1px solid rgba(255,255,255,0.08);">
                            <option value="">Selecciona una opción</option>
                            <option value="Instagram">Instagram</option>
                            <option value="YouTube">YouTube</option>
                            <option value="Referido">Referido de un amigo</option>
                            <option value="Google">Google</option>
                            <option value="TikTok">TikTok</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg" style="width:100%; justify-content:center; margin-top:8px; background: rgba(0, 82, 255, 0.2); border: 1px solid rgba(0, 82, 255, 0.4); color: #0052ff; font-weight: 700;">
                        Crear Mi Cuenta →
                    </button>
                </form>
            </div>
            
            <div style="margin-top: 15px; text-align: center;">
                <a href="{{ route('google.login') }}" style="width: 100%; background: #161a29 !important; color: white !important; border: 1px solid rgba(255,255,255,0.1) !important; padding: 12px !important; border-radius: 8px !important; font-weight: bold !important; font-size: 14px !important; cursor: pointer !important; display: flex !important; align-items: center !important; justify-content: center !important; gap: 12px !important; text-decoration: none; box-shadow: 0 4px 10px rgba(0,0,0,0.2) !important;">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" style="width: 20px; height: 20px;">
                        <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path>
                        <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path>
                        <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path>
                        <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path>
                        <path fill="none" d="M0 0h48v48H0z"></path>
                    </svg>
                    Continuar con Google
                </a>
            </div>
        </div>
    </div>

    <!-- Global Javascript Logic -->
    <script>
        // CSRF Token Setup for Axios / Fetch
        window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Theme Toggle Logic
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const targetTheme = currentTheme === 'light' ? 'dark' : 'light';
            html.setAttribute('data-theme', targetTheme);
            showGlobalNotification(targetTheme === 'dark' ? '🌙 Modo Noche Activado' : '☀️ Modo Día Activado');
            localStorage.setItem('theme', targetTheme);
        }

        // Apply saved theme on load
        const savedTheme = localStorage.getItem('theme') || 'dark';
        document.documentElement.setAttribute('data-theme', savedTheme);
        document.addEventListener('DOMContentLoaded', () => {
            const chk = document.getElementById('theme-toggle-chk');
            if(chk) chk.checked = savedTheme === 'light';
        });

        // Notifications Helper
        function showGlobalNotification(text, isError = false) {
            const toast = document.getElementById('global-notification');
            const icon = document.getElementById('global-notif-icon');
            const txt = document.getElementById('global-notif-text');
            if (toast) {
                icon.textContent = isError ? '⚠️' : '✅';
                txt.textContent = text;
                toast.style.display = 'flex';
                toast.classList.add('open');
                setTimeout(() => {
                    toast.classList.remove('open');
                    setTimeout(() => { toast.style.display = 'none'; }, 300);
                }, 4000);
            }
        }

        // Modal Helpers
        function openAuthModal(tab = 'login') {
            const modal = document.getElementById('auth-modal');
            modal.style.display = 'flex';
            modal.classList.add('open');
            document.body.style.overflow = 'hidden';
            switchAuthTab(tab);
        }

        function closeAuthModal() {
            const modal = document.getElementById('auth-modal');
            modal.style.display = 'none';
            modal.classList.remove('open');
            document.body.style.overflow = 'auto';
        }

        function switchAuthTab(type) {
            const loginBtn = document.getElementById('tab-btn-login');
            const regBtn = document.getElementById('tab-btn-register');
            const loginForm = document.getElementById('login-form-wrapper');
            const regForm = document.getElementById('register-form-wrapper');
            const title = document.getElementById('auth-modal-title');
            const sub = document.getElementById('auth-modal-sub');

            if (type === 'login') {
                loginBtn.classList.add('active');
                regBtn.classList.remove('active');
                loginForm.style.display = 'block';
                regForm.style.display = 'none';
                title.textContent = "Bienvenido de Vuelta";
                sub.textContent = "Accede a tu cuenta y continúa aprendiendo";
            } else {
                loginBtn.classList.remove('active');
                regBtn.classList.add('active');
                loginForm.style.display = 'none';
                regForm.style.display = 'block';
                title.textContent = "Únete a la Élite";
                sub.textContent = "Crea tu cuenta premium y empieza a escalar hoy mismo";
            }
        }

        // AJAX Authentication Submission
        async function submitLogin(e) {
            e.preventDefault();
            const form = e.target;
            const data = {
                email: form.email.value,
                password: form.password.value
            };

            try {
                const response = await fetch("{{ route('login.submit') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.csrfToken
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                if (response.ok) {
                    showGlobalNotification(result.message);
                    closeAuthModal();
                    window.location.href = result.redirect;
                } else {
                    showGlobalNotification(result.message || 'Error al iniciar sesión', true);
                }
            } catch (err) {
                showGlobalNotification('Error en la comunicación con el servidor.', true);
            }
        }

        async function submitRegister(e) {
            e.preventDefault();
            const form = e.target;
            
            const area = document.getElementById('reg-codigo-pais').value;
            const rawPhone = document.getElementById('reg-telefono').value;
            const fullPhone = area + rawPhone;

            const data = {
                name: form.name.value,
                email: form.email.value,
                phone: fullPhone,
                country: form.country.value,
                password: form.password.value,
                password_confirmation: form.password_confirmation.value,
                origin: form.origin.value
            };

            try {
                const response = await fetch("{{ route('register.submit') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.csrfToken
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                if (response.ok) {
                    showGlobalNotification(result.message);
                    closeAuthModal();
                    window.location.href = result.redirect;
                } else {
                    showGlobalNotification(result.message || 'Error al registrarse', true);
                }
            } catch (err) {
                showGlobalNotification('Error en el servidor.', true);
            }
        }
    </script>
</body>
</html>
