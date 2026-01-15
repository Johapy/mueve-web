// Variable global para la tasa (se leerá dinámicamente desde el input oculto cuando sea necesario)
let currentRate = 0;

// 1. Manejo de Pestañas (Comprar / Vender)
function setTransactionType(type) {
    // Actualizar input oculto
    document.getElementById('inputType').value = type;

    // Actualizar estilos visuales
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.type === type) btn.classList.add('active');
    });

    // Opcional: Cambiar texto del botón o colores según sea compra/venta
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
    // Validar paso 1 antes de avanzar
    if (stepNumber === 2) {
        if (!amountUsdInput.value || amountUsdInput.value <= 0) {
            alert("Por favor ingresa un monto válido");
            return;
        }
    }

    // Ocultar todos, mostrar el deseado
    document.querySelectorAll('.step-content').forEach(el => el.classList.remove('active-step'));
    document.getElementById('step' + stepNumber).classList.add('active-step');

    // Si entramos al paso 2, renderizar la información de pago dinámica
    if(stepNumber === 2 && typeof renderPaymentInfo === 'function') renderPaymentInfo();
}

// 4. Envío del Formulario (Submit)
async function submitTransaction(e) {
    e.preventDefault();

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
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.ok) {
            alert("¡Transacción Exitosa! ID: " + result.transaction.id);
            // Reiniciar formulario o redirigir a historial
            window.location.reload();
        } else {
            alert("Error: " + (result.message || "Algo salió mal"));
            conloge.log("errorrrrr: " + result.message)
        }

    } catch (error) {
        console.error(error);
        alert("Error de conexión");
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
    if(!container) return;

    const typeInput = document.getElementById('inputType');
    const walletSelect = document.getElementById('walletSelect');
    const bankSelect = document.getElementById('bankSelect');

    const transType = (typeInput && typeInput.value) ? typeInput.value : 'Comprar';
    const wallet = (walletSelect && walletSelect.value) ? walletSelect.value.toLowerCase() : '';

    // Helper para leer hidden fields creados
    function getHidden(name){
        const el = document.querySelector('[name="'+name+'"]');
        return el ? el.value : '';
    }

    // Limpiar
    container.innerHTML = '';

    if(transType === 'Vender'){
        // Si vende a wallets tipo Zinli/Wally/USDT mostramos correo fijo
        if(['zinli','wally','usdt'].includes(wallet)){
            const html = `
                <div class="flash-message" style="border-color: var(--primary-color); text-align:left;">
                    <strong>Enviar a este correo:</strong><br>
                    <div style="margin-top:8px; font-weight:700; color:var(--text-color);">yohanderjose2002@gmail.com</div>
                    <div style="color:var(--text-muted); margin-top:6px; font-size:13px;">Usa este correo en la plataforma de destino para completar la recepción.</div>
                </div>
            `;
            container.innerHTML = html;
            return;
        }
        // Si vende pero el método es PagoMovil, intentar mostrar datos del método seleccionado
        const owner = getHidden('payment_owner_name');
        const phone = getHidden('payment_phone');
        const bank = getHidden('payment_bank');
        const ci = getHidden('payment_ci');

        if(owner || phone || bank || ci){
            container.innerHTML = `
                <div class="flash-message" style="border-color: var(--primary-color); text-align:left;">
                    <strong>Datos de Pago Móvil:</strong><br>
                    <span class="text-muted">Titular:</span> ${owner || '-'}<br>
                    <span class="text-muted">Teléfono:</span> ${phone || '-'}<br>
                    <span class="text-muted">Banco:</span> ${bank || '-'}<br>
                    <span class="text-muted">CI/RIF:</span> ${ci || '-'}
                </div>
            `;
            return;
        }

        // Fallback
        container.innerHTML = `<div class="flash-message">No se han encontrado datos del método seleccionado.</div>`;
        return;
    }

    // Si es Comprar (u otro), mostrar los datos de PagoMovil (normal)
    const owner = getHidden('payment_owner_name');
    const phone = getHidden('payment_phone');
    const bank = getHidden('payment_bank');
    const ci = getHidden('payment_ci');

    if(owner || phone || bank || ci){
        container.innerHTML = `
            <div class="flash-message" style="border-color: var(--primary-color); text-align:left;">
                <strong>Datos de Pago Móvil:</strong><br>
                <span class="text-muted">Titular:</span> ${owner || '-'}<br>
                <span class="text-muted">Teléfono:</span> ${phone || '-'}<br>
                <span class="text-muted">Banco:</span> ${bank || '-'}<br>
                <span class="text-muted">CI/RIF:</span> ${ci || '-'}
            </div>
        `;
        return;
    }

    // Si no hay datos, instrucción genérica
    container.innerHTML = `<div class="flash-message">Selecciona un método en el paso anterior para ver los datos aquí.</div>`;
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
    if (A > 25) return round2(1.4 + (A - 25) * 0.08);
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
    }
}

// Reutilizar la función existente setTransactionType (ya definida arriba) para recalcular
const originalSetTransactionType = window.setTransactionType;
window.setTransactionType = function(type){
    if(typeof originalSetTransactionType === 'function') originalSetTransactionType(type);
    // Repoblar selector de métodos para reflejar el nuevo tipo (Comprar/Vender)
    if(typeof populateBankSelectFromInjectedData === 'function') populateBankSelectFromInjectedData();
    // Recalcular montos y labels
    calculateAndDisplay();
}

// Listeners adicionales
document.addEventListener('DOMContentLoaded', function(){
    const amountUsdEl = document.getElementById('amountUsd');
    const rateEl = document.getElementById('inputRate');

    if(amountUsdEl) amountUsdEl.addEventListener('input', calculateAndDisplay);
    if(rateEl) rateEl.addEventListener('input', calculateAndDisplay);

    calculateAndDisplay();
});