<?php $title = "Métodos de Pago | Mueve"; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="flex min-h-screen">
    <!-- Sidebar -->
    <?php require_once __DIR__ . '/../dashboard/components/sidebar.php'; ?>

    <!-- Main Content -->
    <main class="flex-1 md:ml-64 p-4 md:p-10 pt-24 md:pt-10">
        
        <!-- Header -->
        <div class="mb-10">
            <h1 class="text-3xl font-headline font-extrabold text-on-surface">Métodos de Pago</h1>
            <p class="text-on-surface-variant mt-1">Administra tus cuentas de Zinli, Wally, USDT y Pago Móvil</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Add Method Form -->
            <div class="glass-panel rounded-[2.5rem] border border-outline-variant p-8 shadow-2xl h-fit">
                <h2 class="text-xl font-headline font-bold text-on-surface mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">add_circle</span>
                    Agregar Nuevo Método
                </h2>

                <?php if(isset($_GET['added'])): ?>
                    <div class="flex items-center gap-3 p-4 mb-6 rounded-2xl bg-primary/10 border border-primary/20 text-primary text-sm">
                        <span class="material-symbols-outlined">check_circle</span>
                        <span>Método agregado correctamente.</span>
                    </div>
                <?php endif; ?>

                <?php if(isset($_GET['error'])): ?>
                    <div class="flex items-center gap-3 p-4 mb-6 rounded-2xl bg-error/10 border border-error/20 text-error text-sm">
                        <span class="material-symbols-outlined">error</span>
                        <span>Error al agregar el método. Revisa los datos.</span>
                    </div>
                <?php endif; ?>

                <form method="post" action="/payment-methods/add" id="addPaymentForm" class="space-y-6">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? ''); ?>">
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-label text-on-surface-variant ml-1">Tipo de Plataforma</label>
                        <select name="type" id="pmType" required class="w-full bg-surface-container border border-outline-variant rounded-2xl py-3.5 px-4 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all appearance-none cursor-pointer">
                            <option value="Zinli">Zinli</option>
                            <option value="Wally">Wally</option>
                            <option value="USDT">USDT</option>
                            <option value="PagoMovil">Pago Móvil</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-label text-on-surface-variant ml-1">Nombre del Titular</label>
                        <input type="text" name="owner_name" placeholder="Ej: Juan Pérez" required class="w-full bg-surface-container border border-outline-variant rounded-2xl py-3.5 px-4 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                    </div>

                    <div id="mailRow" class="space-y-2">
                        <label class="block text-sm font-label text-on-surface-variant ml-1">Correo Electrónico (Pago)</label>
                        <input type="email" name="mail_pay" id="mailPayInput" placeholder="correo@ejemplo.com" class="w-full bg-surface-container border border-outline-variant rounded-2xl py-3.5 px-4 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                    </div>

                    <div id="pagomovilRows" class="hidden space-y-4">
                        <div class="space-y-2">
                            <label class="block text-sm font-label text-on-surface-variant ml-1">Cédula de Identidad</label>
                            <input type="text" name="ci" placeholder="V-12345678" class="w-full bg-surface-container border border-outline-variant rounded-2xl py-3.5 px-4 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-label text-on-surface-variant ml-1">Banco</label>
                            <input type="text" name="bank" placeholder="Ej: Banesco" class="w-full bg-surface-container border border-outline-variant rounded-2xl py-3.5 px-4 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-label text-on-surface-variant ml-1">Teléfono</label>
                            <input type="text" name="phone" placeholder="04121234567" class="w-full bg-surface-container border border-outline-variant rounded-2xl py-3.5 px-4 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                        </div>
                    </div>

                    <button type="submit" class="btn-gradient w-full py-4 rounded-full font-headline font-bold text-on-primary-container flex items-center justify-center gap-2 hover:opacity-90 transition-all active:scale-[0.98] shadow-lg shadow-primary/20">
                        Guardar Método
                        <span class="material-symbols-outlined">save</span>
                    </button>
                </form>
            </div>

            <!-- Methods List -->
            <div class="space-y-6">
                <h2 class="text-xl font-headline font-bold text-on-surface flex items-center gap-2 mb-2">
                    <span class="material-symbols-outlined text-primary">list_alt</span>
                    Tus Métodos Guardados
                </h2>

                <?php
                $methods = $payment_methods ?? [];
                if(empty($methods)): ?>
                    <div class="glass-panel rounded-3xl p-8 border border-outline-variant text-center opacity-60">
                        <span class="material-symbols-outlined text-5xl text-on-surface-variant mb-4">account_balance_wallet</span>
                        <p class="text-on-surface-variant">Aún no has agregado métodos de pago.</p>
                    </div>
                <?php else:
                    $groups = [];
                    foreach($methods as $m){
                        $t = $m['type'] ?? 'Otros';
                        $groups[$t][] = $m;
                    }

                    foreach($groups as $type => $items): ?>
                        <div class="space-y-3">
                            <h3 class="text-xs font-label uppercase tracking-widest text-primary ml-2"><?php echo htmlspecialchars($type); ?></h3>
                            <div class="space-y-3">
                                <?php foreach($items as $it): 
                                    $owner = htmlspecialchars($it['owner_name'] ?? '');
                                    $typeSafe = htmlspecialchars($it['type'] ?? '');
                                    if(strtolower($type) === 'pagomovil'){
                                        $ci = htmlspecialchars($it['ci'] ?? '');
                                        $bank = htmlspecialchars($it['bank'] ?? '');
                                        $phone = htmlspecialchars($it['phone'] ?? '');
                                        $recipient = htmlspecialchars(trim(($phone ? $phone : '') . ($bank ? ' — ' . $bank : '') . ($ci ? ' — ' . $ci : '')));
                                    } else {
                                        $recipient = htmlspecialchars($it['mail_pay'] ?? '');
                                    }
                                ?>
                                <div class="glass-panel rounded-2xl p-4 border border-outline-variant flex justify-between items-center group hover:border-primary/30 transition-all">
                                    <div class="min-w-0">
                                        <p class="font-bold text-on-surface truncate"><?php echo $owner; ?></p>
                                        <p class="text-xs text-on-surface-variant mt-1"><?php echo $recipient; ?></p>
                                    </div>
                                    <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-all">
                                        <!-- Opciones futuras como editar/borrar -->
                                        <span class="material-symbols-outlined text-on-surface-variant hover:text-error cursor-pointer transition-colors p-2 rounded-full hover:bg-error/10">delete</span>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach;
                endif; ?>
            </div>
        </div>

    </main>
</div>

<script>
    const pmType = document.getElementById('pmType');
    const mailRow = document.getElementById('mailRow');
    const pagomovilRows = document.getElementById('pagomovilRows');

    function togglePmFields(){
        const v = pmType.value;
        if(v === 'PagoMovil' || v === 'Pago Móvil'){
            mailRow.classList.add('hidden');
            pagomovilRows.classList.remove('hidden');
        } else {
            mailRow.classList.remove('hidden');
            pagomovilRows.classList.add('hidden');
        }
    }

    pmType.addEventListener('change', togglePmFields);
    document.addEventListener('DOMContentLoaded', togglePmFields);
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
