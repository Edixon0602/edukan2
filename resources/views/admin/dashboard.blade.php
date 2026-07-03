@extends('layouts.app')

@section('title', 'Panel de Control — Edukan2')

@section('content')
<div id="section-admin" class="page-section visible" style="padding: 40px 20px; max-width: 1200px; margin: 0 auto;" x-data="adminDashboard()">
    
    <h2 style="color: var(--accent); margin-bottom: 24px; font-size: 26px; font-weight: 800; text-transform: uppercase;">🛡️ Panel de Control Edukan2</h2>
    
    <!-- Admin Tabs -->
    <div style="display: flex; gap: 12px; margin-bottom: 32px; border-bottom: 2px solid rgba(255,255,255,0.05); padding-bottom: 12px; flex-wrap: wrap;">
        <button :class="{ 'active': activeTab === 'dashboard' }" class="admin-tab" @click="activeTab = 'dashboard'">📊 Dashboard</button>
        <button :class="{ 'active': activeTab === 'crm' }" class="admin-tab" @click="openCrmTab()">👥 CRM Estudiantes</button>
        <button :class="{ 'active': activeTab === 'cursos' }" class="admin-tab" @click="activeTab = 'cursos'">🛠️ Diseñar Cursos</button>
        <button :class="{ 'active': activeTab === 'membresiasAdmin' }" class="admin-tab" @click="activeTab = 'membresiasAdmin'">💎 Diseñar Membresías</button> 
        <button :class="{ 'active': activeTab === 'pagos' }" class="admin-tab" @click="openPagosTab()">💳 Control de Pagos</button>
        <button :class="{ 'active': activeTab === 'notificaciones' }" class="admin-tab" @click="activeTab = 'notificaciones'">📢 Centro de Alertas</button>
    </div>

    <!-- ================= TAB: DASHBOARD ================= -->
    <div x-show="activeTab === 'dashboard'" style="display:block;">
        <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(0, 242, 254, 0.3); padding: 20px; border-radius: 12px; margin-bottom: 30px;">
            <h3 style="color: white; font-size: 14px; margin-bottom: 15px;">🌍 Editor de Prueba Social (Números de la Página de Inicio)</h3>
            <form action="{{ route('admin.update-hero-stats') }}" method="POST">
                @csrf
                <div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 150px;">
                        <label style="font-size: 11px; color: var(--text-muted); font-weight: bold;">Alumnos Activos</label>
                        <input type="text" name="alumnos" value="{{ $proofSocial['alumnos'] }}" class="form-input" style="background: #161a29; margin-top: 4px;">
                    </div>
                    <div style="flex: 1; min-width: 150px;">
                        <label style="font-size: 11px; color: var(--text-muted); font-weight: bold;">Tasa de Éxito</label>
                        <input type="text" name="exito" value="{{ $proofSocial['exito'] }}" class="form-input" style="background: #161a29; margin-top: 4px;">
                    </div>
                    <div style="flex: 1; min-width: 150px;">
                        <label style="font-size: 11px; color: var(--text-muted); font-weight: bold;">Países Alcanzados</label>
                        <input type="text" name="paises" value="{{ $proofSocial['paises'] }}" class="form-input" style="background: #161a29; margin-top: 4px;">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm" style="height: 40px; margin-top: 18px;">💾 Publicar en la Web</button>
                </div>
            </form>
        </div>

        <!-- Metrics Widgets -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 16px; margin-bottom: 30px;">
            <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 18px; border-radius: 12px; border-left: 4px solid #0052ff;">
                <div style="font-size: 10px; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">👀 Tráfico Total</div>
                <div style="font-size: 24px; font-weight: 800; color: #0052ff;">{{ $visitas }}</div>
            </div>
            <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 18px; border-radius: 12px; border-left: 4px solid #00f2fe;">
                <div style="font-size: 10px; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">🎯 Tasa Conversión</div>
                <div style="font-size: 24px; font-weight: 800; color: #00f2fe;">{{ $tasaConversion }}%</div>
            </div>
            <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 18px; border-radius: 12px; border-left: 4px solid #2ecc71;">
                <div style="font-size: 10px; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Activos (U30D)</div>
                <div style="font-size: 24px; font-weight: 800; color: #2ecc71;">{{ $activeStudents }}</div>
            </div>
            <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 18px; border-radius: 12px; border-left: 4px solid #ff4a4a;">
                <div style="font-size: 10px; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Inactivos (+30D)</div>
                <div style="font-size: 24px; font-weight: 800; color: #ff4a4a;">{{ $inactiveStudents }}</div>
            </div>
            <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 18px; border-radius: 12px; border-left: 4px solid #6f42c1;">
                <div style="font-size: 10px; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Cursos</div>
                <div style="font-size: 24px; font-weight: 800; color: white;">{{ $totalCourses }}</div>
            </div>
            <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 18px; border-radius: 12px; border-left: 4px solid #ffca28;">
                <div style="font-size: 10px; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Ventas</div>
                <div style="font-size: 24px; font-weight: 800; color: #25d366;">${{ number_format($totalSales, 2) }}</div>
            </div>
        </div>

        <!-- Tasa BCV Editor -->
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); padding: 15px; border-radius: 12px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px;">
            <span style="font-size: 13px; color: var(--text-muted);">Tasa Euro/Dólar BCV Actual: <b>{{ $bcvRate }} Bs</b></span>
            <form action="{{ route('admin.update-bcv') }}" method="POST" style="display: flex; gap: 10px;">
                @csrf
                <input type="number" name="euroBCV" step="0.01" value="{{ $bcvRate }}" style="padding: 6px; border-radius: 4px; border: 1px solid var(--border); background: var(--dark2); color: white; width: 100px;">
                <button type="submit" class="btn btn-primary btn-sm">Actualizar Tasa</button>
            </form>
        </div>
    </div>

    <!-- ================= TAB: CRM ESTUDIANTES ================= -->
    <div x-show="activeTab === 'crm'" style="display:none; width: 100%;">
        <div style="background: var(--dark2); border:1px solid var(--border); border-radius:12px; padding:20px; margin-bottom:24px;">
            <h3 style="color:white; font-size:16px; margin-bottom:15px;">🔍 Filtros y Buscador CRM</h3>
            
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:15px; align-items:center;">
                <input type="text" x-model="crmSearch" @input.debounce.300ms="loadCrmStudents()" placeholder="Buscar por Nombre, Email o WhatsApp..." class="form-input" style="background:#161a29;">
                
                <select x-model="crmFilterMembership" @change="loadCrmStudents()" class="form-input" style="background:#161a29;">
                    <option value="todos">Todas las Membresías</option>
                    <option value="regular">Regular</option>
                    <option value="estandar">Estandar</option>
                    <option value="pro">Pro</option>
                    <option value="vip">VIP</option>
                </select>
                
                <select x-model="crmFilterStatus" @change="loadCrmStudents()" class="form-input" style="background:#161a29;">
                    <option value="todos">Todos los Estatus</option>
                    <option value="activo">Activos (Últimos 30 días)</option>
                    <option value="inactivo">Inactivos (+30 días)</option>
                </select>
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap:12px;">
            <template x-for="student in crmStudents" :key="student.id">
                <div style="padding:16px; background: rgba(255,255,255,0.01); border: 1px solid rgba(255,255,255,0.04); border-radius: 12px; display: flex; justify-content: space-between; align-items: center; gap: 15px; flex-wrap: wrap;">
                    <div>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <strong style="color:white; font-size:15px;" x-text="student.name"></strong>
                            <span :style="student.last_login_at ? 'color:#2ecc71;' : 'color:#ff4a4a;'" style="font-size:11px; font-weight:700; background:rgba(255,255,255,0.02); padding:2px 8px; border-radius:100px;" x-text="student.last_login_at ? '● Activo' : '● Inactivo'"></span>
                            <span style="color:var(--accent); font-size:11px; font-weight:bold; text-transform:uppercase;" x-text="'[' + student.membership + ']'"></span>
                        </div>
                        <div style="font-size:12px; color:var(--text-muted); margin-top:4px; display:flex; gap:12px; flex-wrap:wrap;">
                            <span x-text="'📧 Email: ' + student.email"></span>
                            <span x-text="'📱 WhatsApp: ' + (student.phone || 'Sin número')"></span>
                            <span x-text="'📍 País: ' + (student.country || 'No especificado')"></span>
                            <span x-text="'⏱️ Descuento: ' + (student.global_discount || '0') + '%'"></span>
                        </div>
                    </div>
                    <div style="display:flex; gap:8px;">
                        <button class="btn btn-outline btn-sm" @click="auditStudent(student.id, student.name)" style="font-size:11px; padding:6px 12px;">📊 Récord</button>
                        <button class="btn btn-primary btn-sm" @click="openManageModal(student.id, student.name)" style="font-size:11px; padding:6px 12px;">⚙️ Gestionar</button>
                    </div>
                </div>
            </template>
            <p x-show="crmStudents.length === 0" style="color: var(--text-muted); text-align: center; padding: 20px;">No se encontraron estudiantes.</p>
        </div>
    </div>

    <!-- ================= TAB: CONTROL DE PAGOS ================= -->
    <div x-show="activeTab === 'pagos'" style="display: none; width:100%;">
        <div class="admin-card" style="padding: 24px; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px;">
            <h3 style="color: var(--accent); margin-bottom: 16px;">⏳ Verificación de Comprobantes Recibidos</h3>
            
            <div class="table-responsive" style="overflow-x: auto;">
                <table style="width:100%; text-align:left; border-collapse:collapse; color: white; min-width: 800px;">
                    <thead>
                        <tr style="color:var(--text-muted); border-bottom: 2px solid rgba(255,255,255,0.1); font-size: 13px;">
                            <th style="padding: 12px 8px;">Estudiante</th>
                            <th style="padding: 12px 8px;">Curso / Plan</th>
                            <th style="padding: 12px 8px;">Monto</th>
                            <th style="padding: 12px 8px;">Método</th>
                            <th style="padding: 12px 8px;">Fecha</th>
                            <th style="padding: 12px 8px;">Comprobante</th>
                            <th style="padding: 12px 8px; text-align: center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="pago in payments" :key="pago.id">
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.03); font-size:13px;">
                                <td style="padding:14px 8px; font-weight:600; color:white;" x-text="pago.user.name"></td>
                                <td style="padding:14px 8px; color:var(--white80);" x-text="pago.item_name"></td>
                                <td style="padding:14px 8px; color:var(--success); font-weight:bold;" x-text="pago.amount"></td>
                                <td style="padding:14px 8px; color:var(--accent);" x-text="pago.payment_method"></td>
                                <td style="padding:14px 8px; color:var(--text-muted);" x-text="formatDate(pago.created_at)"></td>
                                <td style="padding:14px 8px;">
                                    <template x-if="pago.receipt_path">
                                        <a :href="'/storage/' + pago.receipt_path" target="_blank" style="color:var(--accent); font-weight:600; text-decoration:none;">📄 Ver Recibo</a>
                                    </template>
                                    <template x-if="!pago.receipt_path">
                                        <span style="color:var(--text-muted);">Sin comprobante</span>
                                    </template>
                                </td>
                                <td style="padding:14px 8px; display:flex; gap:6px; justify-content:center;">
                                    <button @click="approvePayment(pago.id)" style="background:var(--success); color:white; border:none; padding:6px 12px; border-radius:4px; font-weight:bold; cursor:pointer; font-size:11px;">✅ Aprobar</button>
                                    <button @click="rejectPayment(pago.id)" style="background:#ff4a4a; color:white; border:none; padding:6px 12px; border-radius:4px; font-weight:bold; cursor:pointer; font-size:11px;">✕ Rechazar</button>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="payments.length === 0">
                            <td colspan="7" style="color:var(--text-muted); font-size:13px; text-align:center; padding: 20px;">No hay pagos pendientes por verificar.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ================= TAB: ALERTA MASIVA / INDIVIDUAL ================= -->
    <div x-show="activeTab === 'notificaciones'" style="display: none; width:100%;">
        <div class="admin-card" style="padding: 24px; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; max-width: 600px;">
            <h3 style="color: var(--accent); margin-bottom: 16px;">📢 Enviar Alerta al Sistema</h3>
            
            <form @submit.prevent="sendNotification()" id="notif-dispatch-form">
                <label style="color: var(--text-muted); font-size: 13px;">Destinatario</label>
                <select name="destinatario" x-model="notifRecipient" style="width: 100%; padding: 12px; background: #1e1e24; border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; color: white; margin-bottom: 16px; cursor:pointer;">
                    <option value="todos">🌍 Envío Masivo (A todos los alumnos)</option>
                    <option value="individual">👤 Envío a Usuario Específico (Requiere ID)</option>
                </select>
                
                <div x-show="notifRecipient === 'individual'">
                    <label style="color: var(--text-muted); font-size: 13px;">ID del Estudiante</label>
                    <input type="text" name="user_id" placeholder="Pega el ID del usuario" class="form-input" style="margin-bottom: 16px;">
                </div>

                <label style="color: var(--text-muted); font-size: 13px;">Tipo de Mensaje</label>
                <select name="type" style="width: 100%; padding: 12px; background: #1e1e24; border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; color: white; margin-bottom: 16px; cursor:pointer;">
                    <option value="info">📘 Información / Noticia</option>
                    <option value="success">🎉 Descuento / Logro</option>
                    <option value="warning">⚠️ Recordatorio de Pago / Urgente</option>
                </select>

                <label style="color: var(--text-muted); font-size: 13px;">Título del Mensaje</label>
                <input type="text" name="title" placeholder="Ej: ¡Actualización de Clase!" class="form-input" style="margin-bottom: 16px;" required>

                <label style="color: var(--text-muted); font-size: 13px;">Contenido</label>
                <textarea name="message" placeholder="Escribe tu mensaje aquí..." style="width: 100%; height: 100px; padding: 12px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; color: white; margin-bottom: 24px; resize: none;" required></textarea>

                <button type="submit" style="width: 100%; padding: 14px; background: var(--accent); color: white; border: none; border-radius: 6px; font-weight: 700; cursor: pointer;">
                    🚀 Enviar Notificación al Sistema
                </button>
            </form>
        </div>
    </div>

    <!-- Modals -->
    <!-- 1. Audit / Record modal -->
    <div class="modal-overlay" id="modal-auditoria-alumno" style="display: none; z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
        <div style="background: #121214; border: 1px solid rgba(255,255,255,0.1); width: 100%; max-width: 600px; border-radius: 16px; padding: 24px; position: relative;">
            <button @click="closeAuditModal()" style="position: absolute; top: 16px; right: 16px; background: none; border: none; color: white; font-size: 20px; cursor: pointer;">✕</button>
            <h2 style="color: white; font-size: 20px; font-weight: 800;" x-text="'Expediente de ' + auditName"></h2>
            
            <div style="display: flex; flex-direction: column; gap: 10px; margin-top:20px; max-height:300px; overflow-y:auto;">
                <template x-for="grade in auditGrades">
                    <div style="background:rgba(255,255,255,0.02); padding:12px; border-radius:8px; border:1px solid rgba(255,255,255,0.05); display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <strong style="color:white; font-size:13px;" x-text="grade.module_name"></strong>
                            <p style="font-size:11px; color:var(--text-muted);" x-text="'Materia: ' + grade.course_title"></p>
                        </div>
                        <span style="font-size:15px; font-weight:900;" :style="grade.grade >= 80 ? 'color:var(--success);' : 'color:white;'" x-text="grade.grade + '%'"></span>
                    </div>
                </template>
                <p x-show="auditGrades.length === 0" style="color:var(--text-muted);">El alumno aún no cuenta con evaluaciones completadas.</p>
            </div>
        </div>
    </div>

    <!-- 2. Manage student modal (Gift course / Assign Discount) -->
    <div class="modal-overlay" id="modal-gestionar-alumno" style="display: none; z-index: 9999; align-items: center; justify-content: center; padding: 20px; backdrop-filter: blur(10px);">
        <div style="background: #121214; border: 1px solid rgba(0, 242, 254, 0.3); width: 100%; max-width: 450px; border-radius: 16px; padding: 24px; position: relative;">
            <button @click="closeManageModal()" style="position: absolute; top: 16px; right: 16px; background: none; border: none; color: white; font-size: 20px; cursor: pointer;">✕</button>
            <h2 style="color: white; font-size: 18px; font-weight: 800; margin-bottom: 5px;">⚙️ Gestionar Estudiante VIP</h2>
            <p style="color: #00f2fe; font-size: 14px; margin-bottom: 20px; font-weight:bold;" x-text="manageName"></p>

            <div style="background: rgba(255,255,255,0.02); padding: 16px; border-radius: 12px; margin-bottom: 16px; border: 1px solid rgba(255,255,255,0.05);">
                <h4 style="color: white; font-size: 13px; margin-bottom: 10px;">🎁 Regalar Acceso a Curso</h4>
                <select x-model="giftCourseId" style="width: 100%; padding: 10px; background: #1a1a24; border: 1px solid var(--border); color: white; border-radius: 6px; margin-bottom: 10px;">
                    <option value="">Selecciona un curso</option>
                    @foreach($courses as $c)
                        <option value="{{ $c->id }}">{{ $c->title }}</option>
                    @endforeach
                </select>
                <button @click="giftAccess()" class="btn btn-primary btn-sm" style="width: 100%;">Conceder Acceso Gratuito</button>
            </div>

            <div style="background: rgba(255,255,255,0.02); padding: 16px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                <h4 style="color: white; font-size: 13px; margin-bottom: 10px;">🏷️ Asignar Descuento Exclusivo (%)</h4>
                <input type="number" x-model="manageDiscount" placeholder="Ej: 50" style="width: 100%; padding: 10px; background: #1a1a24; border: 1px solid var(--border); color: white; border-radius: 6px; margin-bottom: 10px;">
                <button @click="saveDiscount()" class="btn btn-outline btn-sm" style="width: 100%; border-color:#00f2fe; color:#00f2fe;">Activar Descuento Global</button>
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
                        document.getElementById('modal-auditoria-alumno').style.display = 'flex';
                    });
            },

            closeAuditModal() {
                document.getElementById('modal-auditoria-alumno').style.display = 'none';
            },

            openManageModal(uid, name) {
                this.manageStudentId = uid;
                this.manageName = name;
                document.getElementById('modal-gestionar-alumno').style.display = 'flex';
            },

            closeManageModal() {
                document.getElementById('modal-gestionar-alumno').style.display = 'none';
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
