<?php
$title = "Mueve | Cambios Instantáneos de Zinli, Wally y USDT";
$meta = '
    <meta name="description" content="Cambia tus Bolívares a Zinli o Wally al instante con Mueve. La plataforma de intercambio más rápida y segura de Venezuela. Recarga tu billetera digital hoy mismo.">
    <meta name="keywords" content="recargar zinli venezuela, recargar wally bolívares, cambiar saldo zinli, exchange venezuela, dolares zinli, mueve app">
    <meta name="author" content="Mueve Exchange">
    <link rel="icon" type="image/x-icon" href="/assets/mueve.ico">
    <meta property="og:title" content="Mueve | Recarga Zinli y Wally con Bolívares al instante">
    <meta property="og:description" content="Olvídate de las complicaciones. Mueve tu dinero de Bolívares a Dólares en segundos. Tasa competitiva y seguridad total.">
    <meta property="og:image" content="/assets/mueve1.png">
    <meta property="og:type" content="website">
';

require_once __DIR__ . '/layouts/header.php';
?>

<!-- TopNavBar (Shared Component Execution) -->
<header class="fixed top-4 left-1/2 -translate-x-1/2 w-[95%] max-w-7xl rounded-full z-50 bg-[#111417]/80 backdrop-blur-xl shadow-2xl shadow-black/50 border border-outline-variant">
    <nav class="flex justify-between items-center px-8 h-16 md:h-20">
        <div class="text-2xl font-black italic text-[#ADC7FF] tracking-tighter font-headline">Mueve</div>
        <div class="hidden md:flex gap-8 items-center">
            <a class="font-headline uppercase tracking-tight text-[#ADC7FF] border-b-2 border-[#ADC7FF] pb-1" href="#home">Home</a>
            <a class="font-headline uppercase tracking-tight text-[#C1C6D7] hover:text-white transition-colors" href="#features">Features</a>
            <a class="font-headline uppercase tracking-tight text-[#C1C6D7] hover:text-white transition-colors" href="#how-it-works">How It Works</a>
        </div>
        <div class="flex gap-4 items-center">
            <a href="/login" class="hidden md:block font-headline uppercase tracking-tight text-[#C1C6D7] hover:text-white transition-colors">Login</a>
            <a href="/register" class="btn-gradient px-6 py-2.5 rounded-full font-headline text-on-primary-container font-bold uppercase tracking-tight text-sm hover:opacity-80 transition-all duration-300 active:scale-95">Sign Up</a>
        </div>
    </nav>
</header>

<main class="max-w-4xl mx-auto px-6 pt-32 pb-24">
    <!-- Hero Section -->
    <section class="text-center mb-24" id="home">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-surface-container-high border border-outline-variant mb-8">
            <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
            <span class="text-xs font-label uppercase tracking-widest text-on-surface-variant">Operaciones 24/7 Activas</span>
        </div>
        <h1 class="text-5xl md:text-7xl font-headline font-extrabold tracking-tight mb-6 text-on-surface leading-tight">
            Mueve tus fondos al <span class="text-gradient">instante.</span>
        </h1>
        <p class="text-lg md:text-xl text-on-surface-variant max-w-2xl mx-auto mb-10 leading-relaxed">
            Cambia Zinli, Wally y USDT a Bolívares en menos de 5 minutos con la tasa más competitiva del mercado y seguridad garantizada.
        </p>
        <div class="flex flex-col md:flex-row gap-4 justify-center items-center">
            <button class="btn-gradient w-full md:w-auto px-8 py-4 rounded-full font-headline font-extrabold flex items-center justify-center gap-2 hover:opacity-90 transition-all active:scale-95">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">send</span>
                Telegram Bot
            </button>
            <a href="/register" class="w-full md:w-auto px-8 py-4 rounded-full border border-primary text-primary font-headline font-extrabold flex items-center justify-center gap-2 hover:bg-primary/10 transition-all active:scale-95 text-center">
                <span class="material-symbols-outlined">rocket_launch</span>
                Web App
            </a>
        </div>
    </section>

    <!-- Services Bento Grid -->
    <section class="mb-32" id="features">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-headline font-bold mb-4">Servicios Soportados</h2>
            <div class="h-1 w-12 bg-primary mx-auto rounded-full"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Zinli Card -->
            <div class="group bg-surface-container rounded-3xl p-8 border border-outline-variant hover:border-primary/50 transition-all duration-500">
                <div class="flex justify-between items-start mb-6">
                    <div class="p-4 rounded-2xl bg-primary/10 text-primary group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">account_balance_wallet</span>
                    </div>
                    <span class="text-xs font-label text-on-surface-variant bg-surface-container-high px-3 py-1 rounded-full border border-outline-variant">LIVE RATE</span>
                </div>
                <h3 class="text-2xl font-headline font-bold mb-2">Zinli</h3>
                <p class="text-on-surface-variant mb-6">Carga y retira desde tu billetera Zinli sin comisiones ocultas.</p>
                <div class="flex items-center gap-3">
                    <div class="flex-1 h-[2px] bg-outline-variant/30"></div>
                    <span class="text-primary font-bold">Cambiar ahora</span>
                    <span class="material-symbols-outlined text-primary text-sm">arrow_forward</span>
                </div>
            </div>
            <!-- USDT Card -->
            <div class="group bg-surface-container rounded-3xl p-8 border border-outline-variant hover:border-primary/50 transition-all duration-500">
                <div class="flex justify-between items-start mb-6">
                    <div class="p-4 rounded-2xl bg-tertiary/10 text-tertiary group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">currency_bitcoin</span>
                    </div>
                    <span class="text-xs font-label text-on-surface-variant bg-surface-container-high px-3 py-1 rounded-full border border-outline-variant">CRYPTO</span>
                </div>
                <h3 class="text-2xl font-headline font-bold mb-2">USDT</h3>
                <p class="text-on-surface-variant mb-6">Convierte tus Stablecoins directamente a tu banco nacional.</p>
                <div class="flex items-center gap-3">
                    <div class="flex-1 h-[2px] bg-outline-variant/30"></div>
                    <span class="text-tertiary font-bold">Ver Tasa</span>
                    <span class="material-symbols-outlined text-tertiary text-sm">arrow_forward</span>
                </div>
            </div>
            <!-- Wally Card -->
            <div class="group bg-surface-container-high rounded-3xl p-8 border border-outline-variant hover:border-primary/50 transition-all duration-500">
                <div class="flex justify-between items-start mb-6">
                    <div class="p-4 rounded-2xl bg-primary/10 text-primary group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">credit_card</span>
                    </div>
                </div>
                <h3 class="text-2xl font-headline font-bold mb-2">Wally Tech</h3>
                <p class="text-on-surface-variant">Integración nativa con Wally para cambios ultra-rápidos.</p>
            </div>
            <!-- Bolivares Card -->
            <div class="group bg-surface-container-high rounded-3xl p-8 border border-outline-variant hover:border-primary/50 transition-all duration-500">
                <div class="flex justify-between items-start mb-6">
                    <div class="p-4 rounded-2xl bg-primary/10 text-primary group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">payments</span>
                    </div>
                </div>
                <h3 class="text-2xl font-headline font-bold mb-2">Bolívares</h3>
                <p class="text-on-surface-variant">Pagos móviles y transferencias inmediatas a cualquier banco.</p>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="mb-32 grid grid-cols-2 md:grid-cols-4 gap-8">
        <div class="text-center">
            <div class="text-4xl font-headline font-black text-primary mb-2">98%</div>
            <div class="text-xs font-label uppercase tracking-widest text-on-surface-variant">Satisfacción</div>
        </div>
        <div class="text-center">
            <div class="text-4xl font-headline font-black text-primary mb-2">~5 min</div>
            <div class="text-xs font-label uppercase tracking-widest text-on-surface-variant">Promedio</div>
        </div>
        <div class="text-center">
            <div class="text-4xl font-headline font-black text-primary mb-2">10k+</div>
            <div class="text-xs font-label uppercase tracking-widest text-on-surface-variant">Operaciones</div>
        </div>
        <div class="text-center">
            <div class="text-4xl font-headline font-black text-primary mb-2">24/7</div>
            <div class="text-xs font-label uppercase tracking-widest text-on-surface-variant">Soporte</div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="mb-32" id="how-it-works">
        <div class="bg-surface-container-lowest rounded-[2.5rem] p-8 md:p-16 border border-outline-variant relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 blur-[120px] rounded-full"></div>
            <h2 class="text-4xl font-headline font-extrabold text-center mb-16">¿Cómo funciona?</h2>
            <div class="space-y-12 relative">
                <div class="flex flex-col md:flex-row gap-8 items-start">
                    <div class="w-12 h-12 rounded-2xl bg-primary flex items-center justify-center font-headline font-black text-xl text-on-primary-container shrink-0">1</div>
                    <div>
                        <h4 class="text-xl font-headline font-bold mb-2 text-on-surface">Selecciona tu método</h4>
                        <p class="text-on-surface-variant leading-relaxed">Elige si deseas cambiar desde Zinli, Wally o USDT. Nuestro sistema detectará la mejor tasa disponible para ti al instante.</p>
                    </div>
                </div>
                <div class="flex flex-col md:flex-row gap-8 items-start">
                    <div class="w-12 h-12 rounded-2xl bg-primary/20 border border-primary/30 flex items-center justify-center font-headline font-black text-xl text-primary shrink-0">2</div>
                    <div>
                        <h4 class="text-xl font-headline font-bold mb-2 text-on-surface">Inicia la transacción</h4>
                        <p class="text-on-surface-variant leading-relaxed">Envía los fondos a la dirección o correo proporcionado por nuestro Bot verificado. Todo el proceso está encriptado de punta a punta.</p>
                    </div>
                </div>
                <div class="flex flex-col md:flex-row gap-8 items-start">
                    <div class="w-12 h-12 rounded-2xl bg-primary/20 border border-primary/30 flex items-center justify-center font-headline font-black text-xl text-primary shrink-0">3</div>
                    <div>
                        <h4 class="text-xl font-headline font-bold mb-2 text-on-surface">Recibe tus Bolívares</h4>
                        <p class="text-on-surface-variant leading-relaxed">Una vez confirmada la recepción, nuestro sistema liquida automáticamente a tu cuenta bancaria vía Pago Móvil en segundos.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Security Highlight -->
    <section class="mb-32">
        <div class="flex flex-col md:flex-row gap-12 items-center bg-surface-container-high rounded-3xl p-8 border border-outline-variant">
            <div class="w-full md:w-1/3">
                <img alt="Cybersecurity Concept" class="rounded-2xl w-full h-48 object-cover grayscale opacity-50" src="https://lh3.googleusercontent.com/aida-public/AB6AXuABIX34sdKD9Gtiyos5QrsUZ5NuhWVcnXIWzrBKqqdwQ7XNCE6WlIVB35lEFsuCOZU_CZqMGr2ADS9hkIsZYGJ-WNOYl3rQ43R7b-6otSffy238j3UvhIlyuH8uFpsFSWMmNpyc09wOpGcRDDXY_5zKrf6EK-51J3Li4MSrnyUNxzZCpt_k4yLTkmRacAiO8HUfE-RA_17XdevPZPrauhjsyoYmf7YexVNx9XnDiu0JJQ5lVo1ND3RrfH7fs_WyQvE2CRs8a91BoKY"/>
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-2 text-primary mb-4">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">verified_user</span>
                    <span class="font-headline font-bold uppercase tracking-widest text-sm">Seguridad de Grado Bancario</span>
                </div>
                <h3 class="text-2xl font-headline font-extrabold mb-4">Tu tranquilidad es nuestra prioridad</h3>
                <p class="text-on-surface-variant mb-6">Utilizamos protocolos de encriptación AES-256 y autenticación multifactor en todos nuestros procesos. Nuestro Bot de Telegram es el único canal oficial para garantizar transacciones libres de riesgos.</p>
                <div class="flex gap-4">
                    <div class="flex items-center gap-2 text-xs font-label text-on-surface-variant">
                        <span class="material-symbols-outlined text-primary text-lg">check_circle</span>
                        Canales Oficiales
                    </div>
                    <div class="flex items-center gap-2 text-xs font-label text-on-surface-variant">
                        <span class="material-symbols-outlined text-primary text-lg">check_circle</span>
                        Bot Anti-Fraude
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="text-center py-20 bg-primary/5 rounded-[3rem] border border-primary/20 relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-primary/10 via-transparent to-transparent"></div>
        <h2 class="text-4xl md:text-5xl font-headline font-black mb-8 relative z-10 leading-tight">¿Listo para mover tu dinero?</h2>
        <p class="text-on-surface-variant mb-12 max-w-xl mx-auto relative z-10">Únete a más de 10,000 usuarios que ya confían en la rapidez y transparencia de Mueve Financial.</p>
        <div class="flex justify-center relative z-10">
            <a href="/register" class="btn-gradient px-12 py-5 rounded-full font-headline font-black text-xl text-on-primary-container shadow-xl shadow-primary/20 hover:scale-105 transition-transform active:scale-95 text-center">
                Empezar Cambio Ahora
            </a>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>