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
                            <?php
                                // $payment_methods viene del controlador y contiene los métodos del usuario
                                $methods = $payment_methods ?? [];
                                // por defecto, mostrar métodos aplicables a la plataforma inicial (Zinli)
                                $initialWallet = 'Zinli';
                                $initialType = 'Comprar';

                                $initialList = array_filter($methods, function($m) use ($initialWallet, $initialType) {
                                    if (!isset($m['type'])) return false;
                                    $type = strtolower($m['type']);
                                    $wallet = strtolower($initialWallet);
                                    if ($initialType === 'Vender') return $type === 'pagomovil';
                                    if ($wallet === 'zinli') return $type === 'zinli';
                                    if ($wallet === 'wally') return $type === 'wally';
                                    if ($wallet === 'usdt') return $type === 'usdt';
                                    return false;
                                });

                                if (empty($initialList)) {
                                    echo "<option value=\"\">No hay métodos guardados</option>";
                                } else {
                                    foreach ($initialList as $method) {
                                        $id = htmlspecialchars($method['id']);
                                        $owner = htmlspecialchars($method['owner_name'] ?? ($method['bank'] ?? 'Método'));
                                        $bank = htmlspecialchars($method['bank'] ?? '');
                                        $phone = htmlspecialchars($method['phone'] ?? '');
                                        $ci = htmlspecialchars($method['ci'] ?? '');
                                        $mail = htmlspecialchars($method['mail_pay'] ?? '');
                                        $type = htmlspecialchars($method['type'] ?? '');

                                        // data-* contienen la información que JS usará para poblar hidden fields
                                        echo "<option value=\"$id\" data-bank=\"$bank\" data-phone=\"$phone\" data-ci=\"$ci\" data-mail=\"$mail\" data-owner=\"" . htmlspecialchars($method['owner_name'] ?? '') . "\" data-type=\"$type\">$owner</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Recibes / Pagas en Bolívares (Bs)</label>
                                                <input type="text" id="amountBs" placeholder="0.00" readonly style="background-color: var(--surface-color); cursor: not-allowed;">

                                                <!-- Info de comisiones (añadido por snippet) -->
                                                <div class="commission-info" style="margin-top:10px; font-size:14px; color:var(--text-muted);">
                                                    <div id="commissionUsdRow">Comisión: <strong id="commissionUsd">0.00 USD</strong></div>
                                                    <div id="commissionBsRow" style="display:block;">Comisión (Bs): <strong id="commissionBs">0.00 Bs</strong></div>
                                                    <div id="netUsdRow" style="display:none;">USD netos para cambio: <strong id="netUsd">0.00 USD</strong></div>
                                                    <div id="totalRow" style="margin-top:6px;">
                                                        <span id="totalLabel">Total a enviar (Bs):</span>
                                                        <strong id="totalBs" style="margin-left:6px;">0.00 Bs</strong>
                                                    </div>
                                                </div>
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
<script>
    // Inyectar métodos de pago obtenidos por PHP para que JS los use sin fetch (token permanece en servidor)
    window.PAYMENT_METHODS = <?php echo json_encode($payment_methods ?? []); ?>;
</script>

</body>
</html>