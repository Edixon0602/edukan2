@extends('layouts.app')

@section('title', 'Panel de Control — Edukan2')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-10" x-data="adminDashboard()">
    
    <h2 class="text-brand-accent font-display font-black text-2xl uppercase mb-6 tracking-wide">🛡️ Panel de Control Edukan2</h2>
    
    <!-- Admin Tabs -->
    <div class="flex gap-2 mb-8 border-b border-white/5 pb-1 overflow-x-auto scrollbar-none">
        <button :class="activeTab === 'dashboard' ? 'text-white border-b-2 border-brand-accent font-bold' : 'text-white/50 hover:text-white'" class="pb-3 px-4 text-xs sm:text-sm font-semibold whitespace-nowrap cursor-pointer transition-all" @click="activeTab = 'dashboard'">📊 Dashboard</button>
        <button :class="activeTab === 'crm' ? 'text-white border-b-2 border-brand-accent font-bold' : 'text-white/50 hover:text-white'" class="pb-3 px-4 text-xs sm:text-sm font-semibold whitespace-nowrap cursor-pointer transition-all" @click="openCrmTab()">👥 CRM Estudiantes</button>
        <button :class="activeTab === 'cursos' ? 'text-white border-b-2 border-brand-accent font-bold' : 'text-white/50 hover:text-white'" class="pb-3 px-4 text-xs sm:text-sm font-semibold whitespace-nowrap cursor-pointer transition-all" @click="activeTab = 'cursos'">🛠️ Diseñar Cursos</button>
        <button :class="activeTab === 'membresiasAdmin' ? 'text-white border-b-2 border-brand-accent font-bold' : 'text-white/50 hover:text-white'" class="pb-3 px-4 text-xs sm:text-sm font-semibold whitespace-nowrap cursor-pointer transition-all" @click="activeTab = 'membresiasAdmin'">💎 Diseñar Membresías</button> 
        <button :class="activeTab === 'pagos' ? 'text-white border-b-2 border-brand-accent font-bold' : 'text-white/50 hover:text-white'" class="pb-3 px-4 text-xs sm:text-sm font-semibold whitespace-nowrap cursor-pointer transition-all" @click="openPagosTab()">💳 Control de Pagos</button>
        <button :class="activeTab === 'notificaciones' ? 'text-white border-b-2 border-brand-accent font-bold' : 'text-white/50 hover:text-white'" class="pb-3 px-4 text-xs sm:text-sm font-semibold whitespace-nowrap cursor-pointer transition-all" @click="activeTab = 'notificaciones'">📢 Centro de Alertas</button>
    </div>

    <!-- ================= TAB: DASHBOARD ================= -->
    <div x-show="activeTab === 'dashboard'">
        <div class="bg-brand-dark2/50 border border-[#00f2fe]/30 p-6 rounded-2xl mb-8 shadow-xl">
            <h3 class="text-white font-bold text-sm mb-4">🌍 Editor de Prueba Social (Números de la Página de Inicio)</h3>
            <form action="{{ route('admin.update-hero-stats') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-brand-text-muted">Alumnos Activos</label>
                        <input type="text" name="alumnos" value="{{ $proofSocial['alumnos'] }}" class="w-full bg-brand-dark border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white focus:border-brand-accent focus:outline-none">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-brand-text-muted">Tasa de Éxito</label>
                        <input type="text" name="exito" value="{{ $proofSocial['exito'] }}" class="w-full bg-brand-dark border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white focus:border-brand-accent focus:outline-none">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-brand-text-muted">Países Alcanzados</label>
                        <input type="text" name="paises" value="{{ $proofSocial['paises'] }}" class="w-full bg-brand-dark border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white focus:border-brand-accent focus:outline-none">
                    </div>
                    <button type="submit" class="bg-brand-accent hover:bg-brand-accent/90 text-white font-bold py-2.5 rounded-lg text-sm transition-all shadow-neon-blue cursor-pointer h-[42px]">💾 Publicar en la Web</button>
                </div>
            </form>
        </div>

        <!-- Metrics Widgets -->
        <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-8">
            <div class="bg-brand-dark2 border border-white/5 p-5 rounded-2xl shadow-xl flex flex-col justify-between border-l-4 border-brand-accent">
                <div class="text-[10px] text-brand-text-muted font-bold uppercase tracking-wider">👀 Tráfico Total</div>
                <div class="text-2xl font-black text-brand-accent mt-2 font-display">{{ $visitas }}</div>
            </div>
            <div class="bg-brand-dark2 border border-white/5 p-5 rounded-2xl shadow-xl flex flex-col justify-between border-l-4 border-[#00f2fe]">
                <div class="text-[10px] text-brand-text-muted font-bold uppercase tracking-wider">🎯 Conversión</div>
                <div class="text-2xl font-black text-[#00f2fe] mt-2 font-display">{{ $tasaConversion }}%</div>
            </div>
            <div class="bg-brand-dark2 border border-white/5 p-5 rounded-2xl shadow-xl flex flex-col justify-between border-l-4 border-brand-success">
                <div class="text-[10px] text-brand-text-muted font-bold uppercase tracking-wider">Activos (U30D)</div>
                <div class="text-2xl font-black text-brand-success mt-2 font-display">{{ $activeStudents }}</div>
            </div>
            <div class="bg-brand-dark2 border border-white/5 p-5 rounded-2xl shadow-xl flex flex-col justify-between border-l-4 border-red-500">
                <div class="text-[10px] text-brand-text-muted font-bold uppercase tracking-wider">Inactivos (+30D)</div>
                <div class="text-2xl font-black text-red-500 mt-2 font-display">{{ $inactiveStudents }}</div>
            </div>
            <div class="bg-brand-dark2 border border-white/5 p-5 rounded-2xl shadow-xl flex flex-col justify-between border-l-4 border-purple-500">
                <div class="text-[10px] text-brand-text-muted font-bold uppercase tracking-wider">Cursos</div>
                <div class="text-2xl font-black text-white mt-2 font-display">{{ $totalCourses }}</div>
            </div>
            <div class="bg-brand-dark2 border border-white/5 p-5 rounded-2xl shadow-xl flex flex-col justify-between border-l-4 border-brand-gold">
                <div class="text-[10px] text-brand-text-muted font-bold uppercase tracking-wider">Ventas</div>
                <div class="text-2xl font-black text-brand-success mt-2 font-display">${{ number_format($totalSales, 2) }}</div>
            </div>
        </div>

        <!-- Tasa BCV Editor -->
        <div class="bg-brand-dark2 border border-white/5 p-5 rounded-2xl flex flex-col sm:flex-row justify-between items-center gap-4 mb-8 shadow-md">
            <span class="text-sm text-brand-text-muted">Tasa Euro/Dólar BCV Actual: <b class="text-white">{{ $bcvRate }} Bs</b></span>
            <form action="{{ route('admin.update-bcv') }}" method="POST" class="flex gap-2">
                @csrf
                <input type="number" name="euroBCV" step="0.01" value="{{ $bcvRate }}" class="bg-brand-dark border border-white/10 rounded-lg px-3 py-1.5 text-sm text-white focus:border-brand-accent focus:outline-none w-24">
                <button type="submit" class="bg-brand-accent hover:bg-brand-accent/90 text-white font-bold px-4 py-1.5 rounded-lg text-xs transition-all shadow-neon-blue cursor-pointer">Actualizar Tasa</button>
            </form>
        </div>
    </div>

    <!-- ================= TAB: CRM ESTUDIANTES ================= -->
    <div x-show="activeTab === 'crm'" class="hidden" :class="activeTab === 'crm' ? '!block' : ''">
        <div class="bg-brand-dark2 border border-white/5 p-6 rounded-2xl mb-6 shadow-xl">
            <h3 class="text-white font-bold text-sm mb-4">🔍 Filtros y Buscador CRM</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <input type="text" x-model="crmSearch" @input.debounce.300ms="loadCrmStudents()" placeholder="Buscar por Nombre, Email o WhatsApp..." class="w-full bg-brand-dark border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white focus:border-brand-accent focus:outline-none">
                
                <select x-model="crmFilterMembership" @change="loadCrmStudents()" class="w-full bg-brand-dark border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white focus:border-brand-accent focus:outline-none cursor-pointer">
                    <option value="todos">Todas las Membresías</option>
                    <option value="regular">Regular</option>
                    <option value="estandar">Estandar</option>
                    <option value="pro">Pro</option>
                    <option value="vip">VIP</option>
                </select>
                
                <select x-model="crmFilterStatus" @change="loadCrmStudents()" class="w-full bg-brand-dark border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white focus:border-brand-accent focus:outline-none cursor-pointer">
                    <option value="todos">Todos los Estatus</option>
                    <option value="activo">Activos (Últimos 30 días)</option>
                    <option value="inactivo">Inactivos (+30 días)</option>
                </select>
            </div>
        </div>

        <div class="flex flex-col gap-4">
            <template x-for="student in crmStudents" :key="student.id">
                <div class="p-5 bg-white/[0.01] border border-white/5 rounded-2xl flex justify-between items-center gap-4 flex-wrap hover:bg-white/[0.02] transition-all">
                    <div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <strong class="text-white text-base font-bold" x-text="student.name"></strong>
                            <span :class="student.last_login_at ? 'text-brand-success' : 'text-red-500'" class="text-[10px] font-bold bg-white/5 px-2.5 py-0.5 rounded-full" x-text="student.last_login_at ? '● Activo' : '● Inactivo'"></span>
                            <span class="text-brand-accent text-[10px] font-bold uppercase tracking-wider" x-text="'[' + student.membership + ']'"></span>
                        </div>
                        <div class="text-xs text-brand-text-muted mt-2 flex gap-4 flex-wrap font-semibold">
                            <span x-text="'📧 Email: ' + student.email"></span>
                            <span x-text="'📱 WhatsApp: ' + (student.phone || 'Sin número')"></span>
                            <span x-text="'📍 País: ' + (student.country || 'No especificado')"></span>
                            <span x-text="'⏱️ Descuento: ' + (student.global_discount || '0') + '%'"></span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button class="border border-white/10 hover:bg-white/5 text-white text-xs font-bold px-3 py-2 rounded-lg cursor-pointer transition-all" @click="auditStudent(student.id, student.name)">📊 Récord</button>
                        <button class="bg-brand-accent hover:bg-brand-accent/90 text-white text-xs font-bold px-3 py-2 rounded-lg cursor-pointer transition-all shadow-neon-blue" @click="openManageModal(student.id, student.name)">⚙️ Gestionar</button>
                    </div>
                </div>
            </template>
            <p x-show="crmStudents.length === 0" class="text-brand-text-muted text-center py-8 text-sm">No se encontraron estudiantes.</p>
        </div>
    </div>

    <!-- ================= TAB: CONTROL DE PAGOS ================= -->
    <div x-show="activeTab === 'pagos'" class="hidden" :class="activeTab === 'pagos' ? '!block' : ''">
        <div class="bg-brand-dark2 border border-white/5 p-6 rounded-2xl shadow-xl">
            <h3 class="text-brand-accent font-display font-black text-sm uppercase tracking-wider mb-4">⏳ Verificación de Comprobantes Recibidos</h3>
            
            <div class="overflow-x-auto scrollbar-none">
                <table class="w-full text-left border-collapse text-white min-w-[800px] text-xs sm:text-sm">
                    <thead>
                        <tr class="text-brand-text-muted border-b border-white/10 font-bold uppercase text-[10px] tracking-wider">
                            <th class="py-3 px-2">Estudiante</th>
                            <th class="py-3 px-2">Curso / Plan</th>
                            <th class="py-3 px-2">Monto</th>
                            <th class="py-3 px-2">Método</th>
                            <th class="py-3 px-2">Fecha</th>
                            <th class="py-3 px-2">Comprobante</th>
                            <th class="py-3 px-2 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="pago in payments" :key="pago.id">
                            <tr class="border-b border-white/[0.02] hover:bg-white/[0.01]">
                                <td class="py-4 px-2 font-bold text-white" x-text="pago.user.name"></td>
                                <td class="py-4 px-2 text-white/80" x-text="pago.item_name"></td>
                                <td class="py-4 px-2 text-brand-success font-bold" x-text="pago.amount"></td>
                                <td class="py-4 px-2 text-brand-accent" x-text="pago.payment_method"></td>
                                <td class="py-4 px-2 text-brand-text-muted" x-text="formatDate(pago.created_at)"></td>
                                <td class="py-4 px-2">
                                    <template x-if="pago.receipt_path">
                                        <a :href="'/storage/' + pago.receipt_path" target="_blank" class="text-brand-accent font-bold hover:underline no-underline">📄 Ver Recibo</a>
                                    </template>
                                    <template x-if="!pago.receipt_path">
                                        <span class="text-brand-text-muted">Sin comprobante</span>
                                    </template>
                                </td>
                                <td class="py-4 px-2 flex gap-2 justify-center">
                                    <button @click="approvePayment(pago.id)" class="bg-brand-success hover:bg-brand-success/90 text-white px-3 py-1.5 rounded-lg font-bold text-xs cursor-pointer shadow-md">✅ Aprobar</button>
                                    <button @click="rejectPayment(pago.id)" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg font-bold text-xs cursor-pointer shadow-md">✕ Rechazar</button>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="payments.length === 0">
                            <td colspan="7" class="text-brand-text-muted text-center py-10 text-sm">No hay pagos pendientes por verificar.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ================= TAB: ALERTA MASIVA / INDIVIDUAL ================= -->
    <div x-show="activeTab === 'notificaciones'" class="hidden" :class="activeTab === 'notificaciones' ? '!block' : ''">
        <div class="bg-brand-dark2 border border-white/5 p-6 rounded-2xl max-w-lg shadow-xl">
            <h3 class="text-brand-accent font-display font-black text-sm uppercase tracking-wider mb-6">📢 Enviar Alerta al Sistema</h3>
            
            <form @submit.prevent="sendNotification()" id="notif-dispatch-form" class="flex flex-col gap-4">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-brand-text-muted">Destinatario</label>
                    <select name="destinatario" x-model="notifRecipient" class="w-full bg-[#1e1e24] border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white focus:border-brand-accent focus:outline-none cursor-pointer">
                        <option value="todos">🌍 Envío Masivo (A todos los alumnos)</option>
                        <option value="individual">👤 Envío a Usuario Específico (Requiere ID)</option>
                    </select>
                </div>
                
                <div x-show="notifRecipient === 'individual'" class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-brand-text-muted">ID del Estudiante</label>
                    <input type="text" name="user_id" placeholder="Pega el ID del usuario" class="w-full bg-brand-dark border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white focus:border-brand-accent focus:outline-none">
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-brand-text-muted">Tipo de Mensaje</label>
                    <select name="type" class="w-full bg-[#1e1e24] border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white focus:border-brand-accent focus:outline-none cursor-pointer">
                        <option value="info">📘 Información / Noticia</option>
                        <option value="success">🎉 Descuento / Logro</option>
                        <option value="warning">⚠️ Recordatorio de Pago / Urgente</option>
                    </select>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-brand-text-muted">Título del Mensaje</label>
                    <input type="text" name="title" placeholder="Ej: ¡Actualización de Clase!" class="w-full bg-brand-dark border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white focus:border-brand-accent focus:outline-none" required>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-brand-text-muted">Contenido</label>
                    <textarea name="message" placeholder="Escribe tu mensaje aquí..." class="w-full h-24 bg-white/5 border border-white/10 rounded-lg p-3 text-white text-xs sm:text-sm focus:border-brand-accent focus:outline-none resize-none font-body" required></textarea>
                </div>

                <button type="submit" class="w-full bg-brand-accent hover:bg-brand-accent/90 text-white font-bold py-3 rounded-lg text-sm transition-all shadow-neon-blue mt-2 cursor-pointer">
                    🚀 Enviar Notificación al Sistema
                </button>
            </form>
        </div>
    </div>

    <!-- Modals -->
    <!-- 1. Audit / Record modal -->
    <div class="fixed inset-0 bg-brand-dark/85 backdrop-blur-md flex items-center justify-center z-[99999] transition-all duration-300" x-show="auditModalOpen" x-transition class="hidden" :class="auditModalOpen ? '!flex' : 'hidden'">
        <div class="bg-brand-dark2 border border-white/10 w-full max-w-lg rounded-2xl p-6 sm:p-8 relative shadow-2xl mx-6" @click.outside="auditModalOpen = false">
            <button @click="auditModalOpen = false" class="absolute top-4 right-4 text-white/50 hover:text-white text-lg cursor-pointer">✕</button>
            <h2 class="text-white font-display font-black text-lg mb-6" x-text="'Expediente de ' + auditName"></h2>
            
            <div class="flex flex-col gap-3 max-h-72 overflow-y-auto">
                <template x-for="grade in auditGrades">
                    <div class="bg-white/[0.01] p-4 rounded-xl border border-white/5 flex justify-between items-center">
                        <div>
                            <strong class="text-white text-xs sm:text-sm font-bold" x-text="grade.module_name"></strong>
                            <p class="text-[10px] text-brand-text-muted mt-1 font-semibold" x-text="'Materia: ' + grade.course_title"></p>
                        </div>
                        <span class="font-display font-black text-base" :class="grade.grade >= 80 ? 'text-brand-success' : 'text-white'" x-text="grade.grade + '%'"></span>
                    </div>
                </template>
                <p x-show="auditGrades.length === 0" class="text-brand-text-muted text-xs text-center py-4">El alumno aún no cuenta con evaluaciones completadas.</p>
            </div>
        </div>
    </div>

    <!-- 2. Manage student modal -->
    <div class="fixed inset-0 bg-brand-dark/85 backdrop-blur-md flex items-center justify-center z-[99999] transition-all duration-300" x-show="manageModalOpen" x-transition class="hidden" :class="manageModalOpen ? '!flex' : 'hidden'">
        <div class="bg-brand-dark2 border border-[#00f2fe]/30 w-full max-w-md rounded-2xl p-6 sm:p-8 relative shadow-2xl mx-6" @click.outside="manageModalOpen = false">
            <button @click="manageModalOpen = false" class="absolute top-4 right-4 text-white/50 hover:text-white text-lg cursor-pointer">✕</button>
            <h2 class="text-white font-display font-black text-lg">⚙️ Gestionar Estudiante VIP</h2>
            <p class="text-[#00f2fe] text-xs font-bold tracking-wider mb-6 mt-1" x-text="manageName"></p>

            <div class="flex flex-col gap-4">
                <div class="bg-white/[0.01] p-4 border border-white/5 rounded-xl">
                    <h4 class="text-white text-xs font-bold mb-2">🎁 Regalar Acceso a Curso</h4>
                    <select x-model="giftCourseId" class="w-full bg-[#1a1a24] border border-white/10 rounded-lg p-2 text-xs text-white focus:border-brand-accent focus:outline-none cursor-pointer mb-3">
                        <option value="">Selecciona un curso</option>
                        @foreach($courses as $c)
                            <option value="{{ $c->id }}">{{ $c->title }}</option>
                        @endforeach
                    </select>
                    <button @click="giftAccess()" class="w-full bg-brand-accent hover:bg-brand-accent/90 text-white font-bold py-2 rounded-lg text-xs transition-all shadow-neon-blue cursor-pointer">Conceder Acceso Gratuito</button>
                </div>

                <div class="bg-white/[0.01] p-4 border border-white/5 rounded-xl">
                    <h4 class="text-white text-xs font-bold mb-2">🏷️ Asignar Descuento Exclusivo (%)</h4>
                    <input type="number" x-model="manageDiscount" placeholder="Ej: 50" class="w-full bg-[#1a1a24] border border-white/10 rounded-lg p-2 text-xs text-white focus:border-brand-accent focus:outline-none mb-3">
                    <button @click="saveDiscount()" class="w-full border border-[#00f2fe] text-[#00f2fe] hover:bg-[#00f2fe]/10 font-bold py-2 rounded-lg text-xs transition-all cursor-pointer">Activar Descuento Global</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function adminDashboard() {
        return {
            activeTab: 'dashboard',
            
            // CRM States
            crmStudents: [],
            crmSearch: '',
            crmFilterMembership: 'todos',
            crmFilterStatus: 'todos',
            
            // Modals toggle
            auditModalOpen: false,
            manageModalOpen: false,
            
            // Audit modal
            auditName: '',
            auditGrades: [],
            
            // Manage student
            manageStudentId: null,
            manageName: '',
            giftCourseId: '',
            manageDiscount: 0,
            
            // Payments
            payments: [],

            // Notifications
            notifRecipient: 'todos',

            openCrmTab() {
                this.activeTab = 'crm';
                this.loadCrmStudents();
            },

            loadCrmStudents() {
                const params = new URLSearchParams({
                    search: this.crmSearch,
                    membership: this.crmFilterMembership,
                    status: this.crmFilterStatus
                });

                fetch(`/api/admin/students?${params}`)
                    .then(res => res.json())
                    .then(data => {
                        this.crmStudents = data.data || [];
                    });
            },

            auditStudent(uid, name) {
                this.auditName = name;
                fetch(`/api/admin/students/${uid}/audit`)
                    .then(res => res.json())
                    .then(data => {
                        this.auditGrades = data;
                        this.auditModalOpen = true;
                    });
            },

            closeAuditModal() {
                this.auditModalOpen = false;
            },

            openManageModal(uid, name) {
                this.manageStudentId = uid;
                this.manageName = name;
                this.manageModalOpen = true;
            },

            closeManageModal() {
                this.manageModalOpen = false;
            },

            giftAccess() {
                if (!this.giftCourseId) return;

                fetch(`/api/admin/students/${this.manageStudentId}/gift`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.csrfToken
                    },
                    body: JSON.stringify({ course_id: this.giftCourseId })
                })
                .then(res => res.json())
                .then(data => {
                    showGlobalNotification(data.message);
                    this.closeManageModal();
                });
            },

            saveDiscount() {
                fetch(`/api/admin/students/${this.manageStudentId}/discount`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.csrfToken
                    },
                    body: JSON.stringify({ discount: this.manageDiscount })
                })
                .then(res => res.json())
                .then(data => {
                    showGlobalNotification(data.message);
                    this.closeManageModal();
                    this.loadCrmStudents();
                });
            },

            openPagosTab() {
                this.activeTab = 'pagos';
                this.loadPendingPayments();
            },

            loadPendingPayments() {
                fetch('/api/admin/payments')
                    .then(res => res.json())
                    .then(data => {
                        this.payments = data;
                    });
            },

            approvePayment(paymentId) {
                if(!confirm('¿Confirmas que recibiste este pago?')) return;

                fetch(`/api/admin/payments/${paymentId}/approve`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': window.csrfToken
                    }
                })
                .then(res => res.json())
                .then(data => {
                    showGlobalNotification(data.message);
                    this.loadPendingPayments();
                });
            },

            rejectPayment(paymentId) {
                if(!confirm('¿Deseas rechazar este recibo?')) return;

                fetch(`/api/admin/payments/${paymentId}/reject`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': window.csrfToken
                    }
                })
                .then(res => res.json())
                .then(data => {
                    showGlobalNotification(data.message);
                    this.loadPendingPayments();
                });
            },

            sendNotification() {
                const form = document.getElementById('notif-dispatch-form');
                const formData = new FormData(form);
                
                const data = {};
                formData.forEach((value, key) => { data[key] = value; });

                fetch('/api/admin/notifications/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.csrfToken
                    },
                    body: JSON.stringify(data)
                })
                .then(res => res.json())
                .then(result => {
                    showGlobalNotification(result.message);
                    form.reset();
                });
            },

            formatDate(str) {
                const date = new Date(str);
                return date.toLocaleDateString();
            }
        }
    }
</script>
@endsection
