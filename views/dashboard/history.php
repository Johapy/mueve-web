<?php $title = "Historial de Movimientos | Mueve"; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="flex min-h-screen">
    <!-- Sidebar -->
    <?php require_once __DIR__ . '/components/sidebar.php'; ?>

    <!-- Main Content -->
    <main class="flex-1 md:ml-64 p-4 md:p-10 pt-24 md:pt-10">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
            <div>
                <h1 class="text-3xl font-headline font-extrabold text-on-surface">Historial de Movimientos</h1>
                <p class="text-on-surface-variant mt-1">Tus operaciones recientes y estados de transacción</p>
            </div>
            
            <div class="relative w-full md:w-72">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                <input type="text" placeholder="Buscar referencia..." class="w-full bg-surface-container border border-outline-variant rounded-full py-2.5 pl-12 pr-4 text-sm text-on-surface focus:outline-none focus:border-primary transition-all">
            </div>
        </div>

        <?php if (empty($transactions)): ?>
            <div class="glass-panel rounded-[2.5rem] border border-outline-variant p-20 text-center shadow-2xl">
                <div class="w-20 h-20 bg-surface-container rounded-3xl flex items-center justify-center mx-auto mb-6 border border-outline-variant">
                    <span class="material-symbols-outlined text-4xl text-on-surface-variant opacity-30">folder_open</span>
                </div>
                <h3 class="text-xl font-headline font-bold text-on-surface mb-2">Aún no hay movimientos</h3>
                <p class="text-on-surface-variant mb-8 max-w-sm mx-auto">Tus operaciones de cambio aparecerán aquí una vez que realices tu primera transacción.</p>
                <a href="/dashboard" class="btn-gradient px-8 py-3 rounded-full font-headline font-bold text-on-primary-container inline-flex items-center gap-2 hover:opacity-90 transition-all active:scale-95">
                    <span class="material-symbols-outlined">add_circle</span>
                    Nueva Operación
                </a>
            </div>

        <?php else: ?>
            <div class="glass-panel rounded-[2rem] border border-outline-variant overflow-hidden shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-high border-b border-outline-variant">
                                <th class="px-6 py-4 text-xs font-label uppercase tracking-widest text-on-surface-variant">Operación</th>
                                <th class="px-6 py-4 text-xs font-label uppercase tracking-widest text-on-surface-variant">Plataforma</th>
                                <th class="px-6 py-4 text-xs font-label uppercase tracking-widest text-on-surface-variant">Monto USD</th>
                                <th class="px-6 py-4 text-xs font-label uppercase tracking-widest text-on-surface-variant">Tasa</th>
                                <th class="px-6 py-4 text-xs font-label uppercase tracking-widest text-on-surface-variant text-right">Total Bs</th>
                                <th class="px-6 py-4 text-xs font-label uppercase tracking-widest text-on-surface-variant">Estado</th>
                                <th class="px-6 py-4 text-xs font-label uppercase tracking-widest text-on-surface-variant">Referencia</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/30">
                            <?php foreach ($transactions as $t): ?>
                                <tr class="hover:bg-primary/5 transition-colors group">
                                    <td class="px-6 py-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-xl flex items-center justify-center <?php echo ($t['transaction_type'] == 'Comprar') ? 'bg-primary/10 text-primary' : 'bg-tertiary/10 text-tertiary'; ?> group-hover:scale-110 transition-transform">
                                                <span class="material-symbols-outlined text-xl"><?php echo ($t['transaction_type'] == 'Comprar') ? 'arrow_downward' : 'arrow_upward'; ?></span>
                                            </div>
                                            <div>
                                                <p class="font-bold text-on-surface"><?php echo htmlspecialchars($t['transaction_type']); ?></p>
                                                <p class="text-[10px] uppercase tracking-tighter text-on-surface-variant">ID: #<?php echo $t['id']; ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-6 font-medium text-on-surface">
                                        <?php echo htmlspecialchars($t['type_pay']); ?>
                                    </td>
                                    
                                    <td class="px-6 py-6">
                                        <span class="text-on-surface font-black text-lg">$<?php echo number_format($t['amount_usd'], 2); ?></span>
                                    </td>
                                    
                                    <td class="px-6 py-6 text-on-surface-variant text-sm">
                                        <?php echo $t['rate_bs']; ?> <span class="text-[10px]">Bs/$</span>
                                    </td>
                                    
                                    <td class="px-6 py-6 text-right">
                                        <span class="text-primary font-black text-lg"><?php echo number_format($t['total_bs'], 2); ?> Bs</span>
                                    </td>
                                    
                                    <td class="px-6 py-6">
                                        <?php if ($t['status'] === 'Completado'): ?>
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-primary/10 text-primary text-[10px] font-bold uppercase tracking-widest border border-primary/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                                                Completado
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-on-surface-variant/10 text-on-surface-variant text-[10px] font-bold uppercase tracking-widest border border-outline-variant">
                                                <span class="w-1.5 h-1.5 rounded-full bg-on-surface-variant"></span>
                                                Pendiente
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td class="px-6 py-6 text-on-surface-variant font-mono text-xs">
                                        <?php echo htmlspecialchars($t['payment_reference']); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

    </main>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>