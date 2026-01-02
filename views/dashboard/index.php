<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="/assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<div class="dashboard-container">
    
    <?php require_once __DIR__ . '/components/sidebar.php'; ?>

    <main class="main-content">
        
        <div class="content-header">
            <h2>Hola, <?php echo $userName ?> 👋</h2>
            <div class="user-badge">
                <span style="color: var(--text-muted);"><?php echo $userMail ; ?></span>
            </div>
        </div>

        <div class="card transaction-card">
            
            <div class="type-tabs">
                <div class="tab-btn active" data-type="Comprar" onclick="setTransactionType('Comprar')">Comprar USD</div>
                <div class="tab-btn" data-type="Vender" onclick="setTransactionType('Vender')">Vender USD</div>
            </div>

            <div class="rate-display">
                <i class="fa-solid fa-arrow-trend-up"></i> Tasa del día: <strong><span id="rateDisplay"><?php echo $current_rate; ?></span> Bs/USD</strong>
            </div>

            <form id="transactionForm" onsubmit="submitTransaction(event)">
                <input type="hidden" name="transaction_type" id="inputType" value="Comprar">
                <input type="hidden" name="rate_bs" id="inputRate" value="<?php echo $current_rate; ?>">

                <div id="step1" class="step-content active-step">
                    <div class="form-group">
                        <label>Plataforma (Billetera)</label>
                        <select name="type_pay" id="walletSelect">
                            <option value="Zinli">Zinli</option>
                            <option value="Wally">Wally</option>
                            <option value="USDT">USDT (Binance)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Monto en Dólares ($)</label>
                        <input type="number" name="amount_usd" id="amountUsd" placeholder="0.00" step="0.01" min="1" required>
                    </div>

                    <div class="form-group">
                        <label>Selecciona tu banco</label>
                        <select name="bank" id="bankSelect">
                            <option value="Banco de Venezuela">Banco de Venezuela</option>
                            <option value="Banco Mercantil">Banco Mercantil</option>
                            <option value="Banco Provincial">Banco Provincial</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Recibes / Pagas en Bolívares (Bs)</label>
                        <input type="text" id="amountBs" placeholder="0.00" readonly style="background-color: var(--surface-color); cursor: not-allowed;">
                    </div>

                    <button type="button" class="btn-primary btn-block" onclick="goToStep(2)">
                        Continuar <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>

                <div id="step2" class="step-content">
                    <div class="flash-message" style="margin-bottom: 20px; text-align: left; border-color: var(--primary-color);">
                        <strong>Datos de Pago Móvil:</strong><br>
                        <span class="text-muted">Banco:</span> Venezuela<br>
                        <span class="text-muted">Teléfono:</span> 0412-1234567<br>
                        <span class="text-muted">RIF:</span> J-123456789
                    </div>

                    <div class="form-group">
                        <label>Referencia Bancaria (6 dígitos)</label>
                        <input type="text" name="payment_reference" id="paymentRef" placeholder="Ej: 123456" required>
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <button type="button" class="btn-primary" style="background: transparent; border: 1px solid var(--border-color);" onclick="goToStep(1)">Atrás</button>
                        <button type="submit" class="btn-primary btn-block">Finalizar Operación</button>
                    </div>
                </div>

            </form>
        </div>

    </main>
</div>

<script src="/assets/app.js"></script>

</body>
</html>