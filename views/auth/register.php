<?php
$title = "Registro | Mueve";
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="min-h-[calc(100vh-200px)] flex items-center justify-center px-6 py-12">
    <div class="w-full max-w-md">
        <!-- Logo/Header -->
        <div class="text-center mb-10">
            <a href="/" class="inline-block text-4xl font-black italic text-[#ADC7FF] tracking-tighter font-headline mb-4">Mueve</a>
            <h2 class="text-2xl font-headline font-bold text-on-surface">Crea tu cuenta</h2>
            <p class="text-on-surface-variant mt-2">Únete a la plataforma de divisas más rápida</p>
        </div>

        <!-- Register Card -->
        <div class="glass-panel rounded-[2rem] p-8 border border-outline-variant shadow-2xl">
            <!-- Flash Messages -->
            <?php if (isset($error)): ?>
                <div class="flex items-center gap-3 p-4 mb-6 rounded-2xl bg-error/10 border border-error/20 text-error text-sm">
                    <span class="material-symbols-outlined text-xl">error</span>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <div class="flex items-center gap-3 p-4 mb-6 rounded-2xl bg-primary/10 border border-primary/20 text-primary text-sm">
                    <span class="material-symbols-outlined text-xl">check_circle</span>
                    <span><?php echo htmlspecialchars($success); ?></span>
                </div>
            <?php endif; ?>

            <form action="/register" method="POST" class="space-y-6">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? ''); ?>">

                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-sm font-label text-on-surface-variant mb-2 ml-1">Nombre Completo</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">person</span>
                        <input type="text" name="name" id="name" 
                               class="w-full bg-surface-container border border-outline-variant rounded-2xl py-3.5 pl-12 pr-4 text-on-surface placeholder:text-on-surface-variant/50 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all" 
                               placeholder="Tu nombre" required autofocus>
                    </div>
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-label text-on-surface-variant mb-2 ml-1">Correo Electrónico</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">mail</span>
                        <input type="email" name="email" id="email" 
                               class="w-full bg-surface-container border border-outline-variant rounded-2xl py-3.5 pl-12 pr-4 text-on-surface placeholder:text-on-surface-variant/50 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all" 
                               placeholder="tu@correo.com" required>
                    </div>
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-label text-on-surface-variant mb-2 ml-1">Contraseña</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">lock</span>
                        <input type="password" name="password" id="password" 
                               class="w-full bg-surface-container border border-outline-variant rounded-2xl py-3.5 pl-12 pr-4 text-on-surface placeholder:text-on-surface-variant/50 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all" 
                               placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" class="btn-gradient w-full py-4 rounded-full font-headline font-bold text-on-primary-container flex items-center justify-center gap-2 hover:opacity-90 transition-all active:scale-[0.98] mt-4 shadow-lg shadow-primary/20">
                    Registrarme
                    <span class="material-symbols-outlined">how_to_reg</span>
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-on-surface-variant text-sm">
                    ¿Ya tienes cuenta? 
                    <a href="/login" class="text-primary font-bold hover:underline">Inicia sesión aquí</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>