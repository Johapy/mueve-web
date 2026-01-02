// Variable global para la tasa (viene del PHP en el HTML, pero la leemos del input oculto)
let currentRate = parseFloat(document.getElementById('inputRate').value);

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

// 2. Cálculo Automático
const amountUsdInput = document.getElementById('amountUsd');
const amountBsInput = document.getElementById('amountBs');

amountUsdInput.addEventListener('input', function () {
    const usd = parseFloat(this.value);
    if (!isNaN(usd)) {
        // Cálculo simple
        const bs = usd * currentRate;
        // Formatear a 2 decimales
        amountBsInput.value = bs.toFixed(2);
    } else {
        amountBsInput.value = '';
    }
});

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