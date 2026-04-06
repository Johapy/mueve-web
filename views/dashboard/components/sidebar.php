<?php $uri = $_SERVER['REQUEST_URI']; ?>

<aside class="w-64 glass-panel border-r border-outline-variant h-screen fixed left-0 top-0 hidden md:flex flex-col p-6 z-40">
    <div class="mb-12">
        <a href="/dashboard" class="text-3xl font-black italic text-[#ADC7FF] tracking-tighter font-headline">Mueve</a>
    </div>

    <nav class="flex-1 space-y-2">
        <a href="/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200 <?php echo ($uri == '/dashboard') ? 'bg-primary/20 text-primary border border-primary/30' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface'; ?>">
            <span class="material-symbols-outlined">add_circle</span>
            <span class="font-bold">Nueva Transacción</span>
        </a>
        <a href="/history" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200 <?php echo ($uri == '/history') ? 'bg-primary/20 text-primary border border-primary/30' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface'; ?>">
            <span class="material-symbols-outlined">history</span>
            <span class="font-bold">Historial</span>
        </a>
        <a href="/payment-methods" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200 <?php echo ($uri == '/payment-methods') ? 'bg-primary/20 text-primary border border-primary/30' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface'; ?>">
            <span class="material-symbols-outlined">payments</span>
            <span class="font-bold">Métodos de Pago</span>
        </a>
    </nav>

    <div class="mt-auto">
        <form action="/logout" method="POST">
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-2xl border border-outline-variant text-on-surface-variant hover:bg-error/10 hover:text-error hover:border-error/30 transition-all font-bold">
                <span class="material-symbols-outlined">logout</span>
                Cerrar Sesión
            </button>
        </form>
    </div>
</aside>

<!-- Mobile Header -->
<div class="md:hidden glass-panel border-b border-outline-variant p-4 fixed top-0 left-0 right-0 z-40 flex justify-between items-center px-6">
    <a href="/dashboard" class="text-2xl font-black italic text-[#ADC7FF] tracking-tighter font-headline">Mueve</a>
    <button class="material-symbols-outlined text-on-surface" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">menu</button>
</div>

<!-- Mobile Menu Overlay -->
<div id="mobile-menu" class="hidden fixed inset-0 z-50 glass-panel md:hidden p-8 animate-fade-in">
    <div class="flex justify-end mb-8">
        <button class="material-symbols-outlined text-on-surface text-3xl" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">close</button>
    </div>
    <nav class="space-y-4 text-center">
        <a href="/dashboard" class="block text-2xl font-headline font-bold text-on-surface p-4">Nueva Transacción</a>
        <a href="/history" class="block text-2xl font-headline font-bold text-on-surface p-4">Historial</a>
        <a href="/payment-methods" class="block text-2xl font-headline font-bold text-on-surface p-4">Métodos de Pago</a>
        <form action="/logout" method="POST" class="pt-8">
            <button type="submit" class="text-xl font-headline font-bold text-error border border-error/30 px-8 py-3 rounded-full">Cerrar Sesión</button>
        </form>
    </nav>
</div>