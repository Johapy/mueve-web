// Variable global para la tasa (se leerá dinámicamente desde el input oculto cuando sea necesario)
let currentRate = 0;

// 1. Manejo de Pestañas (Comprar / Vender)
function setTransactionType(type) {
    // Actualizar input oculto
    const inputType = document.getElementById('inputType');
    if (!inputType) return;
    inputType.value = type;

    // Actualizar estilos de pestañas (Buscando los IDs específicos del nuevo diseño)
    const btnComprar = document.getElementById('tab-comprar');
    const btnVender = document.getElementById('tab-vender');
    
    if (btnComprar && btnVender) {
        if (type === 'Comprar') {
            btnComprar.className = "flex-1 py-3 px-6 rounded-full font-headline font-bold text-sm transition-all duration-300 bg-primary text-on-primary-container shadow-lg";
            btnVender.className = "flex-1 py-3 px-6 rounded-full font-headline font-bold text-sm transition-all duration-300 text-on-surface-variant hover:text-on-surface";
        } else {
            btnVender.className = "flex-1 py-3 px-6 rounded-full font-headline font-bold text-sm transition-all duration-300 bg-primary text-on-primary-container shadow-lg";
            btnComprar.className = "flex-1 py-3 px-6 rounded-full font-headline font-bold text-sm transition-all duration-300 text-on-surface-variant hover:text-on-surface";
        }
    }

    // Repoblar selector de métodos para reflejar el nuevo tipo (Comprar/Vender)
    if(typeof populateBankSelectFromInjectedData === 'function') populateBankSelectFromInjectedData();
    // Recalcular montos y labels
    if(typeof calculateAndDisplay === 'function') calculateAndDisplay();
}

// 2. Cálculo Automático (delegado a calculateAndDisplay)
const amountUsdInput = document.getElementById('amountUsd');
const amountBsInput = document.getElementById('amountBs');

if(amountUsdInput){
    amountUsdInput.addEventListener('input', function (){
        calculateAndDisplay();
    });
}

// 3. Sistema de Pasos (Wizard)
function goToStep(stepNumber) {
    const amountUsdInput = document.getElementById('amountUsd');
    
    // Validar paso 1 antes de avanzar
    if (stepNumber === 2) {
        if (!amountUsdInput.value || amountUsdInput.value <= 0) {
            showNotification('error', "Por favor ingresa un monto válido");
            return;
        }
        // comprobar que existe un método de pago seleccionado
        const bankSelect = document.getElementById('bankSelect');
        if (bankSelect) {
            const val = bankSelect.value;
            if (!val || val === "") {
                showNotification('error', "Necesitas seleccionar o crear un método de pago antes de continuar.");
                return;
            }
        }
    }

    // Navegación de pasos (usando clases de Tailwind 'hidden')
    const s1 = document.getElementById('step1');
    const s2 = document.getElementById('step2');
    
    if (stepNumber === 1) {
        if (s1) s1.classList.remove('hidden');
        if (s2) s2.classList.add('hidden');
    } else {
        if (s1) s1.classList.add('hidden');
        if (s2) s2.classList.remove('hidden');
        // Si entramos al paso 2, renderizar la información de pago dinámica
        if(typeof renderPaymentInfo === 'function') renderPaymentInfo();
    }
}

// 4. Envío del Formulario (Submit)
async function submitTransaction(e) {
    e.preventDefault();

    // Protección del botón: deshabilitar mientras se procesa
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn ? submitBtn.innerHTML : '';
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Procesando...';
    }

    // Recolectar datos
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());

    // Añadir conversión numérica si es necesario para tu API Node
    data.amount_usd = parseFloat(data.amount_usd);
    data.rate_bs = parseFloat(data.rate_bs);

    // Aquí deberíamos llamar a tu backend PHP
    // Vamos a crear una ruta en PHP que haga de puente
    try {
        const response = await fetch('/api/transactions/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': window.CSRF_TOKEN || ''
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.ok) {
            showNotification('success', "¡Transacción Exitosa! ID: " + result.transaction.id);
            // Reiniciar formulario o redirigir a historial
            window.location.reload();
        } else {
            showNotification('error', "Error: " + (result.message || "Algo salió mal"));
            console.log("errorrrrr: " + result.message);
        }

    } catch (error) {
        console.error(error);
        showNotification('error', "Error de conexión");
    } finally {
        // Re-habilitar botón siempre que no se haya redirigido
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    }
}

// ---------------------------------------------------------------------------------
// Código para poblar selector de métodos de pago usando los datos inyectados por PHP
// - No realiza fetch desde JS, usa window.PAYMENT_METHODS que fue generado por PHP
// - Al seleccionar un método, se crean/actualizan campos ocultos en el formulario,
//   incluido `recipient_account` (mail para Zinli/USDT o cadena con datos para PagoMovil)
// ---------------------------------------------------------------------------------

function ensureHiddenFields(){
    const form = document.getElementById('transactionForm');
    if(!form) return;
    const names = ['payment_method_id','payment_owner_name','payment_bank','payment_phone','payment_ci','payment_mail','recipient_account'];
    names.forEach(name => {
        if(!form.querySelector('[name="'+name+'"]')){
            const inp = document.createElement('input'); inp.type='hidden'; inp.name=name; form.appendChild(inp);
        }
    });
}

function buildRecipientAccount(method){
    // Si existe mail_pay -> usarlo (Zinli / USDT)
    if(method.mail_pay && method.mail_pay.length > 0) return method.mail_pay;
    // Si es PagoMovil o tiene phone/bank/ci -> concatenar valores claros
    const parts = [];
    if(method.bank) parts.push('bank=' + method.bank);
    if(method.ci) parts.push('ci=' + method.ci);
    if(method.phone) parts.push('phone=' + method.phone);
    if(method.owner_name) parts.push('owner=' + method.owner_name);
    return parts.join('; ');
}

function updateHiddenFieldsFromMethod(method){
    ensureHiddenFields();
    const map = {
        payment_method_id: method.id || '',
        payment_owner_name: method.owner_name || '',
        payment_bank: method.bank || '',
        payment_phone: method.phone || '',
        payment_ci: method.ci || '',
        payment_mail: method.mail_pay || ''
    };
    map.recipient_account = buildRecipientAccount(method);

    Object.keys(map).forEach(k => {
        const el = document.querySelector('[name="'+k+'"]'); if(el) el.value = map[k];
    });
}

function populateBankSelectFromInjectedData(){
    const all = window.PAYMENT_METHODS || [];
    const walletSelect = document.getElementById('walletSelect');
    const bankSelect = document.getElementById('bankSelect');
    const typeInput = document.getElementById('inputType');
    if(!bankSelect) return;

    const wallet = (walletSelect && walletSelect.value) ? walletSelect.value.toLowerCase() : 'zinli';
    const transType = (typeInput && typeInput.value) ? typeInput.value : 'Comprar';

    let filtered = all.slice();
    if(transType === 'Vender'){
        // Solo PagoMovil
        filtered = filtered.filter(i => (i.type||'').toLowerCase() === 'pagomovil');
    } else {
        // Mostrar únicamente métodos cuyo campo `type` coincide con la wallet seleccionada
        if(wallet === 'zinli') filtered = filtered.filter(i => (i.type||'').toLowerCase() === 'zinli');
        else if(wallet === 'wally') filtered = filtered.filter(i => (i.type||'').toLowerCase() === 'wally');
        else if(wallet === 'usdt') filtered = filtered.filter(i => (i.type||'').toLowerCase() === 'usdt');
    }

    bankSelect.innerHTML = '';
    if(filtered.length === 0){
        const opt = document.createElement('option'); opt.value=''; opt.textContent='No hay métodos guardados'; bankSelect.appendChild(opt);
        updateHiddenFieldsFromMethod({});
        return;
    }

    filtered.forEach(m => {
        const opt = document.createElement('option');
        opt.value = m.id;
        opt.textContent = m.owner_name || m.bank || 'Método';
        opt.dataset.method = JSON.stringify(m);
        bankSelect.appendChild(opt);
    });

    // seleccionar primero y propagar
    bankSelect.selectedIndex = 0;
    const first = bankSelect.options[bankSelect.selectedIndex];
    if(first){
        const method = JSON.parse(first.dataset.method);
        updateHiddenFieldsFromMethod(method);
    }

    bankSelect.addEventListener('change', function(){
        const s = bankSelect.options[bankSelect.selectedIndex];
        if(!s) return;
        const method = JSON.parse(s.dataset.method);
        updateHiddenFieldsFromMethod(method);
        if(typeof renderPaymentInfo === 'function') renderPaymentInfo();
    });

    if(walletSelect) walletSelect.addEventListener('change', populateBankSelectFromInjectedData);
}

// Inicializar si PHP inyectó PAYMENT_METHODS
document.addEventListener('DOMContentLoaded', function(){
    if(window.PAYMENT_METHODS && window.PAYMENT_METHODS.length > 0) populateBankSelectFromInjectedData();
});

// -----------------------------
// Render dinámico para Step 2
// -----------------------------
function renderPaymentInfo(){
    const container = document.getElementById('paymentInfo');
    const cfg = window.MUEVE_PAYMENT_CONFIG || {};
    const owner = cfg.owner || '';
    const phone = cfg.phone || '';
    const bank = cfg.bank || '';
    const ci = cfg.ci || '';
    const mueveEmail = cfg.email || '';

    if(!container) return;

    const typeInput = document.getElementById('inputType');
    const walletSelect = document.getElementById('walletSelect');
    const transType = (typeInput && typeInput.value) ? typeInput.value : 'Comprar';
    const wallet = (walletSelect && walletSelect.value) ? walletSelect.value.toLowerCase() : '';

    container.innerHTML = '';

    if(transType === 'Vender'){
        // Si vende (está enviando sus fondos a Mueve)
        container.innerHTML = `
            <div class="space-y-4">
                <div class="flex items-center gap-2 text-primary">
                    <span class="material-symbols-outlined">info</span>
                    <span class="font-bold text-sm uppercase tracking-wider">Transferir Fondos</span>
                </div>
                <div class="bg-surface-container rounded-2xl p-5 border border-outline-variant">
                    <p class="text-xs text-on-surface-variant mb-1">Envía tus ${wallet.toUpperCase()} a este correo:</p>
                    <div class="flex items-center justify-between gap-4">
                        <span class="font-black text-on-surface text-lg truncate">${mueveEmail}</span>
                        <button onclick="navigator.clipboard.writeText('${mueveEmail}'); showNotification('success', 'Copiado')" class="p-2 rounded-xl bg-primary/10 text-primary hover:bg-primary/20 transition-all">
                            <span class="material-symbols-outlined text-sm">content_copy</span>
                        </button>
                    </div>
                </div>
                <p class="text-[11px] text-on-surface-variant italic">Una vez realizado el envío, introduce el número de referencia abajo para confirmar.</p>
            </div>
        `;
    } else {
        // Si compra (envía Pago Móvil a Mueve)
        container.innerHTML = `
            <div class="space-y-4">
                <div class="flex items-center gap-2 text-primary">
                    <span class="material-symbols-outlined">payments</span>
                    <span class="font-bold text-sm uppercase tracking-wider">Datos para Pago Móvil</span>
                </div>
                <div class="bg-surface-container rounded-2xl p-5 border border-outline-variant space-y-3">
                    <div class="flex justify-between items-center border-b border-outline-variant/30 pb-2">
                        <span class="text-xs text-on-surface-variant">Banco:</span>
                        <span class="text-sm font-bold text-on-surface">${bank}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-outline-variant/30 pb-2">
                        <span class="text-xs text-on-surface-variant">Teléfono:</span>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold text-on-surface">${phone}</span>
                            <button onclick="navigator.clipboard.writeText('${phone}'); showNotification('success', 'Copiado')" class="text-primary hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-xs">content_copy</span>
                            </button>
                        </div>
                    </div>
                    <div class="flex justify-between items-center border-b border-outline-variant/30 pb-2">
                        <span class="text-xs text-on-surface-variant">CI/RIF:</span>
                        <span class="text-sm font-bold text-on-surface">${ci}</span>
                    </div>
                    <div class="flex justify-between items-center pt-1">
                        <span class="text-xs text-on-surface-variant">Titular:</span>
                        <span class="text-sm font-bold text-on-surface">${owner}</span>
                    </div>
                </div>
                <p class="text-[11px] text-on-surface-variant italic">Realiza el pago móvil y coloca el número de referencia bancaria abajo.</p>
            </div>
        `;
    }
}

// Llamar renderPaymentInfo cuando cambian selectores relevantes
document.addEventListener('DOMContentLoaded', function(){
    const wallet = document.getElementById('walletSelect');
    const bank = document.getElementById('bankSelect');
    const typeInput = document.getElementById('inputType');

    if(wallet) wallet.addEventListener('change', function(){ if(typeof populateBankSelectFromInjectedData === 'function') populateBankSelectFromInjectedData(); if(typeof renderPaymentInfo === 'function') renderPaymentInfo(); });
    if(bank) bank.addEventListener('change', function(){ if(typeof renderPaymentInfo === 'function') renderPaymentInfo(); });
    if(typeInput) typeInput.addEventListener('change', function(){ if(typeof renderPaymentInfo === 'function') renderPaymentInfo(); });
});

// -----------------------------
// Cálculo de comisiones avanzado (por tramos)
// -----------------------------
function round2(n){ return Math.round(n * 100) / 100; }

// simple toast-like notification helper
function showNotification(type, message, duration = 3000) {
    const container = document.getElementById('notification-container');
    if (!container) return;
    const notif = document.createElement('div');
    notif.className = 'notification ' + type;
    notif.textContent = message;
    container.appendChild(notif);
    setTimeout(() => {
        notif.style.animation = 'slideOut 0.3s forwards';
        notif.addEventListener('animationend', () => notif.remove());
    }, duration);
}
function formatBs(n){ return new Intl.NumberFormat('es-VE').format(round2(n).toFixed(2)); }
function formatUsd(n){ return round2(n).toFixed(2) + ' USD'; }

// Comisión por tramos (reglas solicitadas):
// 1 <= A < 10  -> 0.8 USD
// 10 <= A < 15 -> 1.0 USD
// 15 <= A <=25 -> 1.4 USD
// A > 25       -> 1.4 USD + 8% sobre el monto que exceda 25
function getCommissionUsd(amount){
    const A = parseFloat(amount) || 0;
    if (A >= 1 && A < 10) return 0.8;
    if (A >= 10 && A < 15) return 1.0;
    if (A >= 15 && A <= 25) return 1.4;
    if (A > 25) return round2(A * 0.08);
    return 0;
}

function calculateAndDisplay(){
    const amountUsdEl = document.getElementById('amountUsd');
    const rateEl = document.getElementById('inputRate');
    const typeEl = document.getElementById('inputType');

    const A = parseFloat(amountUsdEl.value) || 0;
    const r = parseFloat(rateEl.value) || 0;
    const type = (typeEl.value || 'Comprar');

    const commission_usd = getCommissionUsd(A);

    const commissionUsdEl = document.getElementById('commissionUsd');
    const commissionBsEl = document.getElementById('commissionBs');
    const netUsdEl = document.getElementById('netUsd');
    const totalBsEl = document.getElementById('totalBs');
    const amountBsInput = document.getElementById('amountBs');
    const step2TotalLabel = document.getElementById('step2TotalLabel');
    const step2TotalBs = document.getElementById('step2TotalBs');

    if(type === 'Vender'){
        const net_usd = round2(A - commission_usd);
        const receive_bs = round2(net_usd * r);

        if(commissionUsdEl) commissionUsdEl.textContent = formatUsd(commission_usd);
        if(commissionBsEl) commissionBsEl.textContent = formatBs(commission_usd * r);
        if(netUsdEl) netUsdEl.textContent = formatUsd(net_usd);
        if(totalBsEl) totalBsEl.textContent = formatBs(receive_bs);
        if(amountBsInput) amountBsInput.value = formatBs(receive_bs);

        const netRow = document.getElementById('netUsdRow'); if(netRow) netRow.style.display = 'block';
        const commissionBsRow = document.getElementById('commissionBsRow'); if(commissionBsRow) commissionBsRow.style.display = 'block';
        const totalLabel = document.getElementById('totalLabel'); if(totalLabel) totalLabel.textContent = 'Recibirás (Bs):';
        if(step2TotalLabel) step2TotalLabel.textContent = 'Recibirás (USD):';
        if(step2TotalBs) step2TotalBs.textContent = formatUsd(A);
    } else {
        const gross_usd = round2(A + commission_usd);
        const pay_bs = round2(gross_usd * r);
        const commission_bs = round2(commission_usd * r);

        if(commissionUsdEl) commissionUsdEl.textContent = formatUsd(commission_usd);
        if(commissionBsEl) commissionBsEl.textContent = formatBs(commission_bs);
        if(netUsdEl) netUsdEl.textContent = formatUsd(A);
        if(totalBsEl) totalBsEl.textContent = formatBs(pay_bs);
        if(amountBsInput) amountBsInput.value = formatBs(pay_bs);

        const netRow = document.getElementById('netUsdRow'); if(netRow) netRow.style.display = 'none';
        const commissionBsRow = document.getElementById('commissionBsRow'); if(commissionBsRow) commissionBsRow.style.display = 'block';
        const totalLabel = document.getElementById('totalLabel'); if(totalLabel) totalLabel.textContent = 'Total a enviar (Bs):';
        if(step2TotalLabel) step2TotalLabel.textContent = 'Total a enviar (Bs):';
        if(step2TotalBs) step2TotalBs.textContent = formatBs(pay_bs);
    }
}

// Nota: setTransactionType ya está definido al principio y maneja la lógica de actualización.

// Listeners adicionales
document.addEventListener('DOMContentLoaded', function(){
    const amountUsdEl = document.getElementById('amountUsd');
    const rateEl = document.getElementById('inputRate');

    if(amountUsdEl) amountUsdEl.addEventListener('input', calculateAndDisplay);
    if(rateEl) rateEl.addEventListener('input', calculateAndDisplay);

    calculateAndDisplay();
});