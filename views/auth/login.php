<?php
$title = "Iniciar Sesión | Mueve";
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="min-h-[calc(100vh-200px)] flex items-center justify-center px-6 py-12">
    <div class="w-full max-w-md">
        <!-- Logo/Header -->
        <div class="text-center mb-10">
            <a href="/" class="inline-block text-4xl font-black italic text-[#ADC7FF] tracking-tighter font-headline mb-4">Mueve</a>
            <h2 class="text-2xl font-headline font-bold text-on-surface">Bienvenido de nuevo</h2>
            <p class="text-on-surface-variant mt-2">Ingresa a tu panel de divisas</p>
        </div>

        <!-- Login Card -->
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

            <form action="/login" method="POST" class="space-y-6">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? ''); ?>">

                <div>
                    <label for="email" class="block text-sm font-label text-on-surface-variant mb-2 ml-1">Correo Electrónico</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">mail</span>
                        <input type="email" name="email" id="email" 
                               class="w-full bg-surface-container border border-outline-variant rounded-2xl py-3.5 pl-12 pr-4 text-on-surface placeholder:text-on-surface-variant/50 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all" 
                               placeholder="tu@correo.com" required autofocus>
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-label text-on-surface-variant mb-2 ml-1">Contraseña</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">lock</span>
                        <input type="password" name="password" id="password" 
                               class="w-full bg-surface-container border border-outline-variant rounded-2xl py-3.5 pl-12 pr-4 text-on-surface placeholder:text-on-surface-variant/50 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all" 
                               placeholder="••••••••" required>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs sm:text-sm">
                    <label class="flex items-center gap-2 cursor-pointer text-on-surface-variant">
                        <input type="checkbox" class="w-4 h-4 rounded border-outline-variant bg-surface-container text-primary focus:ring-primary">
                        <span>Recordarme</span>
                    </label>
                    <a href="#" class="text-primary hover:underline font-medium">¿Olvidaste tu clave?</a>
                </div>

                <button type="submit" class="btn-gradient w-full py-4 rounded-full font-headline font-bold text-on-primary-container flex items-center justify-center gap-2 hover:opacity-90 transition-all active:scale-[0.98] mt-4 shadow-lg shadow-primary/20">
                    Ingresar
                    <span class="material-symbols-outlined">arrow_forward</span>
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-on-surface-variant text-sm">
                    ¿No tienes cuenta? 
                    <a href="/register" class="text-primary font-bold hover:underline">Regístrate aquí</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>