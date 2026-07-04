<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Edukan2 — La Universidad de las Mentes Divergentes')</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700;900&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,700;1,300&display=swap" rel="stylesheet">
    
    <script>
        if (localStorage.getItem('theme') !== 'light') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @vite(['resources/css/app.css'])
    
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#090b11">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Edukan2">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen flex flex-col justify-between transition-colors duration-200 bg-gray-50 text-gray-900 dark:bg-brand-dark dark:text-white" x-data="{ isDark: localStorage.getItem('theme') !== 'light' }">

    <!-- Notification Toast -->
    <div id="global-notification" class="fixed top-24 right-6 transform translate-x-80 opacity-0 bg-brand-dark2 border border-gray-800 px-5 py-4 rounded-xl flex items-center gap-3 shadow-2xl transition-all duration-300 z-[999999]">
        <span id="global-notif-text" class="text-sm font-semibold text-white">Acción completada exitosamente</span>
    </div>

    <!-- Navigation Header -->
    @include('layouts.partials.header')

    <!-- Main Content Area -->
    <main class="flex-grow pt-[70px] pb-[60px] md:pb-0 relative z-10">
        <!-- Skeleton Loader (displayed during page swap) -->
        @include('layouts.partials.skeleton')

        <!-- Dynamic Content wrapper -->
        <div id="main-content-wrapper" class="w-full">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    @include('layouts.partials.footer')

    <!-- Auth Modal (Login / Register) -->
    <div class="fixed inset-0 bg-brand-dark/90 flex items-center justify-center z-[99999] opacity-0 pointer-events-none transition-all duration-300" id="auth-modal">
        <div class="bg-brand-dark2 border border-gray-800 p-8 rounded-2xl w-full max-w-md shadow-2xl relative">
            <button class="absolute top-4 right-4 text-white/50 hover:text-white text-lg cursor-pointer" onclick="closeAuthModal()">✕</button>
            <h3 class="font-display font-black text-xl text-white mb-2" id="auth-modal-title">Bienvenido de Vuelta</h3>
            <p class="text-brand-text-muted text-xs mb-6" id="auth-modal-sub">Accede a tu cuenta y continúa aprendiendo</p>
        
            <div class="flex bg-white/5 p-1 rounded-full border border-white/5 mb-6 text-sm font-semibold text-center cursor-pointer">
                <div class="flex-1 py-2 rounded-full transition-all text-white bg-brand-accent" id="tab-btn-login" onclick="switchAuthTab('login')">Iniciar Sesión</div>
                <div class="flex-1 py-2 rounded-full transition-all text-white/50" id="tab-btn-register" onclick="switchAuthTab('register')">Crear Cuenta</div>
            </div>
        
            <!-- Formulario de Iniciar Sesión -->
            <div id="login-form-wrapper">
                <form id="ajax-login-form" onsubmit="submitLogin(event)" class="flex flex-col gap-4">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-white/70">Correo Electrónico</label>
                        <input type="email" name="email" id="login-email" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white placeholder-white/30 focus:border-brand-accent focus:outline-none" required placeholder="tu@email.com">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-white/70">Contraseña</label>
                        <input type="password" name="password" id="login-pass" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white placeholder-white/30 focus:border-brand-accent focus:outline-none" required placeholder="••••••••">
                    </div>
                    <button type="submit" class="w-full bg-brand-accent hover:bg-brand-accent/90 text-white font-bold py-3 rounded-lg text-sm transition-all mt-2 cursor-pointer">
                        Entrar a la Academia
                    </button>
                    <p class="text-center text-xs text-brand-text-muted mt-2">
                        ¿Olvidaste tu contraseña? <a href="#" class="text-brand-accent font-semibold hover:underline" onclick="alert('Funcionalidad de recuperación en desarrollo')">Recupérala aquí</a>
                    </p>
                </form>
            </div>
        
            <!-- Formulario de Registro -->
            <div id="register-form-wrapper" class="hidden">
                <form id="ajax-register-form" onsubmit="submitRegister(event)" class="flex flex-col gap-3">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-white/70">Nombre Completo</label>
                            <input type="text" name="name" id="reg-nombre" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-sm text-white placeholder-white/30 focus:border-brand-accent focus:outline-none" required placeholder="Tu nombre">
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-white/70">País</label>
                            <select name="country" id="reg-pais" class="w-full bg-[#111423] border border-white/10 rounded-lg px-4 py-2 text-sm text-white focus:border-brand-accent focus:outline-none cursor-pointer">
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
                    
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-white/70">Número Telefónico (WhatsApp)</label>
                        <div class="grid grid-cols-[100px_1fr] gap-2">
                            <select id="reg-codigo-pais" class="w-full bg-[#111423] border border-white/10 rounded-lg px-2 py-2 text-xs text-white focus:border-brand-accent focus:outline-none cursor-pointer">
                                <option value="+58" selected>VE (+58)</option>
                                <option value="+57">CO (+57)</option>
                                <option value="+1">US (+1)</option>
                                <option value="+34">ES (+34)</option>
                                <option value="+56">CL (+56)</option>
                                <option value="+51">PE (+51)</option>
                                <option value="+54">AR (+54)</option>
                                <option value="+52">MX (+52)</option>
                            </select>
                            <input type="tel" name="phone_number" id="reg-telefono" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-sm text-white placeholder-white/30 focus:border-brand-accent focus:outline-none" required placeholder="4241234567">
                        </div>
                    </div>
                    
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-white/70">Correo Electrónico</label>
                        <input type="email" name="email" id="reg-email" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-sm text-white placeholder-white/30 focus:border-brand-accent focus:outline-none" required placeholder="tu@email.com">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-white/70">Contraseña</label>
                            <input type="password" name="password" id="reg-pass" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-sm text-white placeholder-white/30 focus:border-brand-accent focus:outline-none" required placeholder="Mínimo 6 carac.">
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-white/70">Repetir</label>
                            <input type="password" name="password_confirmation" id="reg-pass-confirm" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-sm text-white placeholder-white/30 focus:border-brand-accent focus:outline-none" required placeholder="Repite clave">
                        </div>
                    </div>
                    
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-white/70">¿Cómo nos encontraste?</label>
                        <select name="origin" id="reg-origen" class="w-full bg-[#111423] border border-white/10 rounded-lg px-4 py-2 text-sm text-white focus:border-brand-accent focus:outline-none cursor-pointer">
                            <option value="">Selecciona una opción</option>
                            <option value="Instagram">Instagram</option>
                            <option value="YouTube">YouTube</option>
                            <option value="Referido">Referido de un amigo</option>
                            <option value="Google">Google</option>
                            <option value="TikTok">TikTok</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="w-full bg-[#111423] border border-gray-800 hover:border-brand-accent text-white font-bold py-2.5 rounded-lg text-sm transition-all mt-2 cursor-pointer">
                        Crear Mi Cuenta
                    </button>
                </form>
            </div>
            
            <div class="mt-4 border-t border-white/5 pt-4">
                <a href="{{ route('google.login') }}" class="w-full bg-[#161a29] text-white border border-white/10 py-3 rounded-lg font-bold text-sm flex items-center justify-center gap-3 no-underline shadow-lg hover:bg-white/5 transition-all">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="w-5 h-5">
                        <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path>
                        <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path>
                        <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path>
                        <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path>
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



        // 🌓 Vanilla Dark/Light Mode toggle handler (delegated)
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('#theme-toggle-btn');
            if (!btn) return;
            
            const isCurrentlyDark = document.documentElement.classList.contains('dark');
            const nextDark = !isCurrentlyDark;
            
            if (nextDark) {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            }
            
            showGlobalNotification(nextDark ? 'Modo Oscuro Activado' : 'Modo Claro Activado');
            
            // Sync with Alpine state on the body if it exists
            if (window.Alpine) {
                const bodyData = Alpine.$data(document.body);
                if (bodyData) {
                    bodyData.isDark = nextDark;
                }
            }
        });

        // 🚀 Custom SPA Router & Page transitions
        document.addEventListener('click', async (e) => {
            const link = e.target.closest('a');
            if (!link) return;
            
            // Check if it's an internal link
            const url = new URL(link.href, window.location.href);
            if (url.origin !== window.location.origin) return;
            
            // Skip target="_blank" or download links
            if (link.getAttribute('target') === '_blank' || link.hasAttribute('download')) return;
            
            // Skip standard hash links on the same page
            if (url.pathname === window.location.pathname && url.hash) return;
            
            e.preventDefault();
            await navigateTo(url.href);
        });

        async function navigateTo(url, addToHistory = true) {
            const content = document.getElementById('main-content-wrapper');
            const skeleton = document.getElementById('global-skeleton-loader');
            
            if (content && skeleton) {
                content.classList.add('hidden');
                skeleton.classList.remove('hidden');
            }
            
            try {
                const response = await fetch(url);
                if (!response.ok) throw new Error('Response error');
                
                const htmlText = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(htmlText, 'text/html');
                
                // Update Title
                document.title = doc.title;
                
                // Update Content
                const newContent = doc.getElementById('main-content-wrapper');
                if (newContent && content) {
                    content.innerHTML = newContent.innerHTML;
                }
                
                // Update URL History
                if (addToHistory) {
                    history.pushState(null, '', url);
                }
                
                // Scroll to top
                window.scrollTo(0, 0);
                
                // Re-execute scripts inside the new content
                const scripts = content.querySelectorAll('script');
                scripts.forEach(oldScript => {
                    const newScript = document.createElement('script');
                    Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                    newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                    oldScript.parentNode.replaceChild(newScript, oldScript);
                });
                
                // Re-bind CSRF token
                window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                closeAuthModal();
                
                // Re-initialize Alpine elements
                if (window.Alpine) {
                    window.Alpine.initTree(content);
                }
                
            } catch (error) {
                if (!addToHistory) {
                    window.location.href = url;
                }
            } finally {
                if (content && skeleton) {
                    skeleton.classList.add('hidden');
                    content.classList.remove('hidden');
                }
            }
        }

        window.addEventListener('popstate', () => {
            navigateTo(window.location.href, false);
        });

        // Notifications Helper
        function showGlobalNotification(text, isError = false) {
            const toast = document.getElementById('global-notification');
            const txt = document.getElementById('global-notif-text');
            if (toast) {
                txt.textContent = text;
                if (isError) {
                    toast.classList.remove('border-gray-800');
                    toast.classList.add('border-red-900');
                } else {
                    toast.classList.remove('border-red-900');
                    toast.classList.add('border-gray-800');
                }
                toast.classList.remove('translate-x-80', 'opacity-0');
                toast.classList.add('translate-x-0', 'opacity-100');
                setTimeout(() => {
                    toast.classList.remove('translate-x-0', 'opacity-100');
                    toast.classList.add('translate-x-80', 'opacity-0');
                }, 4000);
            }
        }

        // Modal Helpers
        function openAuthModal(tab = 'login') {
            const modal = document.getElementById('auth-modal');
            if(modal) {
                modal.classList.remove('opacity-0', 'pointer-events-none');
                modal.classList.add('opacity-100');
                switchAuthTab(tab);
            }
        }

        function closeAuthModal() {
            const modal = document.getElementById('auth-modal');
            if(modal) {
                modal.classList.remove('opacity-100');
                modal.classList.add('opacity-0', 'pointer-events-none');
            }
        }

        function switchAuthTab(type) {
            const loginBtn = document.getElementById('tab-btn-login');
            const regBtn = document.getElementById('tab-btn-register');
            const loginForm = document.getElementById('login-form-wrapper');
            const regForm = document.getElementById('register-form-wrapper');
            const title = document.getElementById('auth-modal-title');
            const sub = document.getElementById('auth-modal-sub');

            if (type === 'login') {
                loginBtn.className = "flex-1 py-2 rounded-full transition-all text-white bg-brand-accent";
                regBtn.className = "flex-1 py-2 rounded-full transition-all text-white/50";
                loginForm.classList.remove('hidden');
                regForm.classList.add('hidden');
                title.textContent = "Bienvenido de Vuelta";
                sub.textContent = "Accede a tu cuenta y continúa aprendiendo";
            } else {
                loginBtn.className = "flex-1 py-2 rounded-full transition-all text-white/50";
                regBtn.className = "flex-1 py-2 rounded-full transition-all text-white bg-brand-accent";
                loginForm.classList.add('hidden');
                regForm.classList.remove('hidden');
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
