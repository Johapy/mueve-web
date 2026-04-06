<?php $title = "Panel de Control | Mueve"; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="flex min-h-screen">
    <!-- Sidebar Sidebar (Already updated) -->
    <?php require_once __DIR__ . '/components/sidebar.php'; ?>

    <!-- Main Content -->
    <main class="flex-1 md:ml-64 p-4 md:p-10 pt-24 md:pt-10">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
            <div>
                <h1 class="text-3xl font-headline font-extrabold text-on-surface">Hola, <?php echo $userName ?> 👋</h1>
                <p class="text-on-surface-variant mt-1"><?php echo $userMail; ?></p>
            </div>
            <div class="glass-panel px-6 py-3 rounded-2xl border border-primary/20 flex items-center gap-3">
                <span class="material-symbols-outlined text-primary animate-pulse">trending_up</span>
                <span class="text-on-surface-variant text-sm font-medium">Tasa del día:</span>
                <span class="text-primary font-black text-xl"><span id="rateDisplay"><?php echo $current_rate; ?></span> Bs/USD</span>
            </div>
        </div>

        <!-- Transaction Card -->
        <div class="max-w-3xl glass-panel rounded-[2.5rem] border border-outline-variant p-2 md:p-4 shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 blur-[120px] rounded-full pointer-events-none"></div>
            
            <div class="p-6 md:p-8 relative z-10">
                <!-- Tabs -->
                <div class="flex p-1.5 bg-surface-container rounded-full mb-10 border border-outline-variant max-w-sm">
                    <button onclick="setTransactionType('Comprar')" id="tab-comprar" class="flex-1 py-3 px-6 rounded-full font-headline font-bold text-sm transition-all duration-300 active-tab bg-primary text-on-primary-container shadow-lg">
                        Comprar USD
                    </button>
                    <button onclick="setTransactionType('Vender')" id="tab-vender" class="flex-1 py-3 px-6 rounded-full font-headline font-bold text-sm transition-all duration-300 text-on-surface-variant hover:text-on-surface">
                        Vender USD
                    </button>
                </div>

                <form id="transactionForm" onsubmit="submitTransaction(event)" class="space-y-8">
                    <input type="hidden" name="transaction_type" id="inputType" value="Comprar">
                    <input type="hidden" name="rate_bs" id="inputRate" value="<?php echo $current_rate; ?>">

                    <!-- Step 1 -->
                    <div id="step1" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-label text-on-surface-variant ml-1">Plataforma (Billetera)</label>
                                <select name="type_pay" id="walletSelect" class="w-full bg-surface-container border border-outline-variant rounded-2xl py-3.5 px-4 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all appearance-none cursor-pointer">
                                    <option value="Zinli">Zinli</option>
                                    <option value="Wally">Wally</option>
                                    <option value="USDT">USDT (Binance)</option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-label text-on-surface-variant ml-1">Monto en Dólares ($)</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-primary font-bold">$</span>
                                    <input type="number" name="amount_usd" id="amountUsd" placeholder="0.00" step="0.01" min="1" required class="w-full bg-surface-container border border-outline-variant rounded-2xl py-3.5 pl-10 pr-4 text-on-surface font-bold text-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-label text-on-surface-variant ml-1">Selecciona tu método de pago</label>
                            <select name="bank" id="bankSelect" class="w-full bg-surface-container border border-outline-variant rounded-2xl py-3.5 px-4 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all appearance-none cursor-pointer">
                                <?php
                                $methods = $payment_methods ?? [];
                                $initialWallet = 'Zinli';
                                $initialType = 'Comprar';

                                $initialList = array_filter($methods, function ($m) use ($initialWallet, $initialType) {
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

                                        echo "<option value=\"$id\" data-bank=\"$bank\" data-phone=\"$phone\" data-ci=\"$ci\" data-mail=\"$mail\" data-owner=\"" . htmlspecialchars($method['owner_name'] ?? '') . "\" data-type=\"$type\">$owner</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <?php if (empty($initialList)): ?>
                            <div class="p-4 rounded-2xl bg-error/10 border border-error/20 text-error text-center text-sm">
                                <strong>No hay métodos de pago disponibles para esta plataforma.</strong><br>
                                <a href="/payment-methods" class="inline-block mt-2 font-bold hover:underline">Crear método de pago</a>
                            </div>
                        <?php endif; ?>

                        <div class="space-y-4">
                            <label class="block text-sm font-label text-on-surface-variant ml-1">Cálculo de Operación</label>
                            <div class="bg-surface-container rounded-3xl p-6 border border-outline-variant space-y-4">
                                <div class="flex justify-between items-center text-sm" id="commissionUsdRow">
                                    <span class="text-on-surface-variant">Comisión Mueve:</span>
                                    <span class="text-on-surface font-bold" id="commissionUsd">0.00 USD</span>
                                </div>
                                <div class="flex justify-between items-center text-sm" id="commissionBsRow">
                                    <span class="text-on-surface-variant">Comisión (Bs):</span>
                                    <span class="text-on-surface font-bold" id="commissionBs">0.00 Bs</span>
                                </div>
                                <div class="pt-4 border-t border-outline-variant/30 flex justify-between items-center">
                                    <span class="text-on-surface font-headline font-bold" id="totalLabel">Total a enviar (Bs):</span>
                                    <span class="text-primary text-2xl font-black" id="totalBs">0.00 Bs</span>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn-gradient w-full py-4 rounded-full font-headline font-bold text-on-primary-container flex items-center justify-center gap-2 hover:opacity-90 transition-all active:scale-[0.98] <?php if(empty($initialList)) echo 'opacity-50 cursor-not-allowed'; ?>" onclick="goToStep(2)" <?php if(empty($initialList)) echo 'disabled'; ?> >
                            Continuar
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </button>
                    </div>

                    <!-- Step 2 -->
                    <div id="step2" class="hidden space-y-6 animate-fade-in">
                        <div id="paymentInfo" class="bg-surface-container-high rounded-3xl p-6 border border-primary/20 space-y-4">
                            <!-- JS will populate this -->
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-label text-on-surface-variant ml-1">Referencia Bancaria (6-8 dígitos)</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">confirmation_number</span>
                                <input type="text" name="payment_reference" id="paymentRef" placeholder="Ej: 123456" required class="w-full bg-surface-container border border-outline-variant rounded-2xl py-3.5 pl-12 pr-4 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                            </div>
                        </div>

                        <div class="bg-surface-container rounded-3xl p-6 border border-outline-variant flex justify-between items-center">
                            <span class="text-on-surface font-headline font-bold" id="step2TotalLabel">Total a enviar (Bs):</span>
                            <span class="text-primary text-2xl font-black" id="step2TotalBs">0.00 Bs</span>
                        </div>

                        <div class="flex gap-4">
                            <button type="button" class="flex-1 py-4 rounded-full font-headline font-bold text-on-surface-variant border border-outline-variant hover:bg-surface-container-high transition-all" onclick="goToStep(1)">
                                Atrás
                            </button>
                            <button type="submit" id="submitBtn" class="flex-[2] btn-gradient py-4 rounded-full font-headline font-bold text-on-primary-container flex items-center justify-center gap-2 hover:opacity-90 transition-all active:scale-[0.98]">
                                Finalizar Operación
                                <span class="material-symbols-outlined">verified</span>
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>

    </main>
</div>

<!-- Notification Container -->
<div id="notification-container" class="fixed bottom-6 right-6 z-[60] flex flex-col gap-3"></div>

<script src="/assets/app.js"></script>
<script>
    // JS Logic preserved and adapted
    window.PAYMENT_METHODS = <?php echo json_encode($payment_methods ?? []); ?>;
    window.CSRF_TOKEN = <?php echo json_encode($csrf_token ?? ''); ?>;
    window.MUEVE_PAYMENT_CONFIG = <?php echo json_encode($mueve_payment_config ?? []); ?>;

    function setTransactionType(type) {
        document.getElementById('inputType').value = type;
        const btnComprar = document.getElementById('tab-comprar');
        const btnVender = document.getElementById('tab-vender');
        
        if (type === 'Comprar') {
            btnComprar.className = "flex-1 py-3 px-6 rounded-full font-headline font-bold text-sm transition-all duration-300 bg-primary text-on-primary-container shadow-lg";
            btnVender.className = "flex-1 py-3 px-6 rounded-full font-headline font-bold text-sm transition-all duration-300 text-on-surface-variant hover:text-on-surface";
        } else {
            btnVender.className = "flex-1 py-3 px-6 rounded-full font-headline font-bold text-sm transition-all duration-300 bg-primary text-on-primary-container shadow-lg";
            btnComprar.className = "flex-1 py-3 px-6 rounded-full font-headline font-bold text-sm transition-all duration-300 text-on-surface-variant hover:text-on-surface";
        }
        
        // This function should be defined in app.js, calling it to update the view
        if (typeof window.updateTransactionView === 'function') {
            window.updateTransactionView();
        }
    }

    function goToStep(step) {
        const s1 = document.getElementById('step1');
        const s2 = document.getElementById('step2');
        if (step === 1) {
            s1.classList.remove('hidden');
            s2.classList.add('hidden');
        } else {
            s1.classList.add('hidden');
            s2.classList.remove('hidden');
            // Populate payment info if available
            if (typeof window.populatePaymentInfo === 'function') {
                window.populatePaymentInfo();
            }
        }
    }
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>