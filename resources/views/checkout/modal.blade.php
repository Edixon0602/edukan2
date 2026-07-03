<!-- Checkout Modal -->
<div class="modal-overlay" id="checkout-modal" style="display: none; z-index: 9999;">
    <div class="modal-box" style="max-width:600px; background: rgba(16, 20, 35, 0.95); border: 1px solid var(--border); box-shadow: 0 20px 40px rgba(0,0,0,0.5);" x-data="checkoutEngine()">
        <button class="modal-close" onclick="closeCheckoutModal()">✕</button>
        <div class="modal-title">Completar Inscripción</div>
        <div class="modal-subtitle" id="checkout-course-name" x-text="itemName">Cargando...</div>
    
        <!-- Price display -->
        <div style="background:var(--white10); border:1px solid var(--border); border-radius:14px; padding:16px; margin-bottom:20px; display:flex; align-items:center; justify-content:space-between;">
            <div>
                <div style="font-size:12px; color:var(--text-muted);">Total a pagar</div>
                <div style="font-family:var(--font-display); font-size:28px; font-weight:700; color:var(--accent);" x-text="'$' + finalPrice"></div>
            </div>
            <div class="discount-tag" x-show="discountApplied" x-text="'🏷️ ' + discountPercentage + '% Dto.'"></div>
            <div class="discount-tag" x-show="!discountApplied">🏷️ Acceso Vitalicio</div>
        </div>
    
        <!-- Coupon Form -->
        <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 12px 16px; border-radius: 100px; display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; gap: 10px;">
            <div style="display: flex; align-items: center; gap: 8px; flex: 1;">
                <span style="font-size: 16px;">🎟️</span>
                <input type="text" x-model="couponCode" placeholder="¿Tienes un código de descuento?" style="background: transparent; border: none; color: white; font-size: 13px; width: 100%; outline: none; text-transform: uppercase;">
            </div>
            <button @click="applyCoupon()" class="btn btn-primary btn-sm" style="padding: 6px 14px; font-size: 11px; font-weight: bold; border-radius: 100px; background: #6f42c1; border: none;">Aplicar</button>
        </div>
    
        <p style="font-size:13px; font-weight:600; color:var(--white80); margin-bottom:16px;">Selecciona tu método de pago:</p>
 
        <!-- Payment Methods Tabs -->
        <div class="payment-methods">
            <div :class="{ 'selected': method === 'card' }" class="pay-method" @click="selectMethod('card')">
                <div class="pay-method-icon" style="background:rgba(30,111,255,0.15);">💳</div>
                <span>Tarjeta</span>
            </div>
            <div :class="{ 'selected': method === 'mobile' }" class="pay-method" @click="selectMethod('mobile')">
                <div class="pay-method-icon" style="background:rgba(0,229,160,0.15);">📱</div>
                <span>Pago Móvil</span>
            </div>
            <div :class="{ 'selected': method === 'binance' }" class="pay-method" @click="selectMethod('binance')">
                <div class="pay-method-icon" style="background:rgba(243, 186, 47, 0.15); color: #F3BA2F;">🟡</div>
                <span>Binance</span>
            </div>
        </div>
 
        <!-- 1. CARD FORM -->
        <div x-show="method === 'card'" style="display:block;">
            <div class="form-group">
                <label class="form-label">Número de tarjeta</label>
                <input type="text" class="form-input" placeholder="1234 5678 9012 3456" maxlength="19">
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                <div class="form-group"><label class="form-label">Vencimiento</label><input type="text" class="form-input" placeholder="MM/AA"></div>
                <div class="form-group"><label class="form-label">CVV</label><input type="text" class="form-input" placeholder="123" maxlength="4"></div>
            </div>
            <button class="btn btn-primary btn-lg" style="width:100%; justify-content:center;" onclick="alert('Sistema de tarjetas en mantenimiento. Usa Pago Móvil o Binance.')">🔒 Pagar Ahora →</button>
        </div>
 
        <!-- 2. PAGO MÓVIL FORM -->
        <div x-show="method === 'mobile'" style="display:none;">
            <div class="mobile-payment-box">
                <div style="font-size:13px; font-weight:700; color:var(--accent); margin-bottom:16px; text-transform:uppercase;">📱 Datos para Pago Móvil</div>
                <div class="bank-detail"><span class="label">Banco</span><span class="value accent">Banco Nacional</span></div>
                <div class="bank-detail"><span class="label">Teléfono</span><span class="value">0412-555-0123</span></div>
                <div class="bank-detail"><span class="label">RIF/Cédula</span><span class="value">V-12345678</span></div>
                <div class="bank-detail">
                    <span class="label">Monto</span>
                    <span class="value accent" x-html="bsTotalText">Cargando...</span>
                </div>
            </div>
            
            <form @submit.prevent="submitReceipt('mobile')" id="form-pago-movil">
                <div class="upload-zone" @click="clickInput('comprobante-input')">
                    <div class="upload-icon">📤</div>
                    <p x-text="receiptName || 'Haz clic para subir el comprobante'"></p>
                    <input type="file" id="comprobante-input" name="comprobante" style="display:none;" accept="image/*" @change="fileSelected($event)">
                </div>
                <button type="submit" class="btn btn-success btn-lg" style="width:100%; justify-content:center; margin-top:16px;">
                    ✅ Enviar Comprobante
                </button>
            </form>
        </div>
 
        <!-- 3. BINANCE FORM -->
        <div x-show="method === 'binance'" style="display:none;">
            <div class="mobile-payment-box" style="background: linear-gradient(135deg, rgba(243, 186, 47, 0.08), rgba(200, 150, 20, 0.08)); border-color: rgba(243, 186, 47, 0.25);">
                <div style="font-size:13px; font-weight:700; color:#F3BA2F; margin-bottom:16px; text-transform:uppercase;">🟡 Datos Binance Pay</div>
                <div class="bank-detail"><span class="label">Pay ID</span><span class="value accent" style="color:#F3BA2F;">123456789</span></div>
                <div class="bank-detail"><span class="label">Correo Binance</span><span class="value">pagos@tuacademia.com</span></div>
                <div class="bank-detail">
                    <span class="label">A Transferir</span>
                    <span class="value accent" style="color:#F3BA2F; font-size:18px;" x-text="finalPrice + ' USDT'"></span>
                </div>
            </div>
            
            <form @submit.prevent="submitReceipt('binance')" id="form-binance">
                <div class="upload-zone" @click="clickInput('comprobante-binance')" style="border-color: rgba(243, 186, 47, 0.3);">
                    <div class="upload-icon">📤</div>
                    <p x-text="receiptNameBinance || 'Haz clic para subir captura de Binance'"></p>
                    <input type="file" id="comprobante-binance" name="comprobante" style="display:none;" accept="image/*" @change="fileSelectedBinance($event)">
                </div>
                <button type="submit" class="btn btn-success btn-lg" style="width:100%; justify-content:center; margin-top:16px; background:#F3BA2F; color:#121214; border:none;">
                    ✅ Enviar Comprobante USDT
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function checkoutEngine() {
        return {
            itemName: '',
            basePrice: 0.0,
            finalPrice: 0.0,
            couponCode: '',
            discountApplied: false,
            discountPercentage: 0,
            method: 'card',
            bcvRate: 36.50,
            bsTotalText: '',
            receiptName: '',
            receiptFile: null,
            receiptNameBinance: '',
            receiptFileBinance: null,
            courseId: null,

            init() {
                // Fetch BCV Rate on load
                fetch('/api/checkout/bcv-rate')
                    .then(res => res.json())
                    .then(data => {
                        this.bcvRate = data.euroBCV || 36.50;
                    });
            },

            loadItem(name, price, id = null) {
                this.itemName = name;
                this.basePrice = parseFloat(price);
                this.courseId = id;
                this.calculateFinalPrice();
                this.receiptName = '';
                this.receiptFile = null;
                this.receiptNameBinance = '';
                this.receiptFileBinance = null;
            },

            calculateFinalPrice() {
                // Apply CRM discounts if any
                const crmDiscount = {{ auth()->check() ? auth()->user()->global_discount : 0 }};
                const totalDiscount = Math.max(this.discountPercentage, crmDiscount);
                
                this.finalPrice = (this.basePrice * (1 - totalDiscount / 100)).toFixed(2);
                this.updateBsTotal();
            },

            applyCoupon() {
                if (this.couponCode.trim() === '') return;
                
                fetch('/api/checkout/validate-coupon', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.csrfToken
                    },
                    body: JSON.stringify({ code: this.couponCode })
                })
                .then(res => {
                    if (!res.ok) throw new Error('Invalido');
                    return res.json();
                })
                .then(data => {
                    this.discountApplied = true;
                    this.discountPercentage = data.discount_percentage;
                    this.calculateFinalPrice();
                    showGlobalNotification('🎫 Cupón aplicado correctamente');
                })
                .catch(() => {
                    showGlobalNotification('⚠️ Cupón inválido', true);
                });
            },

            selectMethod(m) {
                this.method = m;
                this.updateBsTotal();
            },

            updateBsTotal() {
                const totalBs = (parseFloat(this.finalPrice) * this.bcvRate).toFixed(2);
                this.bsTotalText = `
                    <div style="background: rgba(0, 229, 160, 0.08); border: 1px solid var(--success); padding: 12px; border-radius: 8px; margin-top: 8px; text-align: right;">
                        <span style="display:block; color:white; font-size:13px;">Base: <b>$${this.finalPrice}</b></span>
                        <span style="display:block; color:var(--text-muted); font-size:11px; margin-bottom: 6px;">Tasa Oficial: <b>${this.bcvRate} Bs/$</b></span>
                        <span style="display:block; color:var(--success); font-size:18px; font-weight:900;">Total: ${totalBs} Bs</span>
                    </div>`;
            },

            clickInput(id) {
                document.getElementById(id).click();
            },

            fileSelected(e) {
                if (e.target.files.length > 0) {
                    this.receiptName = e.target.files[0].name;
                    this.receiptFile = e.target.files[0];
                }
            },

            fileSelectedBinance(e) {
                if (e.target.files.length > 0) {
                    this.receiptNameBinance = e.target.files[0].name;
                    this.receiptFileBinance = e.target.files[0];
                }
            },

            submitReceipt(type) {
                const file = type === 'mobile' ? this.receiptFile : this.receiptFileBinance;
                if (!file) {
                    showGlobalNotification('⚠️ Por favor sube tu comprobante de pago', true);
                    return;
                }

                showGlobalNotification('⏳ Subiendo e informando al administrador...');
                
                const formData = new FormData();
                formData.append('item_name', this.itemName);
                formData.append('amount', '$' + this.finalPrice);
                formData.append('payment_method', type);
                formData.append('comprobante', file);

                fetch('/api/checkout/submit-payment', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': window.csrfToken
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    showGlobalNotification(data.message);
                    closeCheckoutModal();
                })
                .catch(() => {
                    showGlobalNotification('⚠️ Ocurrió un error al cargar el pago.', true);
                });
            }
        }
    }

    // Modal Global Trigger Functions
    function openCheckoutModal(name, price, id = null) {
        const modal = document.getElementById('checkout-modal');
        if (modal) {
            modal.style.display = 'flex';
            modal.classList.add('open');
            document.body.style.overflow = 'hidden';
            
            // Access Alpine context
            const alpineData = Alpine.$data(modal.querySelector('[x-data]'));
            if(alpineData) alpineData.loadItem(name, price, id);
        }
    }

    function closeCheckoutModal() {
        const modal = document.getElementById('checkout-modal');
        if (modal) {
            modal.style.display = 'none';
            modal.classList.remove('open');
            document.body.style.overflow = 'auto';
        }
    }
</script>
