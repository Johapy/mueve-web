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