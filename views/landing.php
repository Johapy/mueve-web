<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Cambia tus Bolívares a Zinli o Wally al instante con Mueve. La plataforma de intercambio más rápida y segura de Venezuela. Recarga tu billetera digital hoy mismo.">
    <meta name="keywords" content="recargar zinli venezuela, recargar wally bolívares, cambiar saldo zinli, exchange venezuela, dolares zinli, mueve app">
    <meta name="author" content="Mueve Exchange">
    <link rel="icon" type="image/x-icon" href="/assets/mueve.ico">
    <meta property="og:title" content="Mueve | Recarga Zinli y Wally con Bolívares al instante">
    <meta property="og:description" content="Olvídate de las complicaciones. Mueve tu dinero de Bolívares a Dólares en segundos. Tasa competitiva y seguridad total.">
    <meta property="og:image" content="/assets/mueve1.png"> <!-- Reemplazar con imagen real -->
    <meta property="og:type" content="website">
    
    <title>Mueve | Recarga Zinli y Wally en Venezuela - Rápido y Seguro</title>
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ========================================
           CORE STYLES (Misma paleta que el Dashboard)
           ========================================
        */
        :root {
            --bg-color: #050505;
            --surface-color: #121212;
            --surface-highlight: #1c1c1c;
            --text-color: #e0e0e0;
            --text-muted: #888888;
            --primary-color: #2F80ED;
            --primary-glow: rgba(47, 128, 237, 0.5);
            --secondary-color: #00CC66; /* Verde para dinero/éxito */
            --accent-purple: #BB86FC; /* Para detalles visuales */
            --border-color: #2a2a2a;
            --border-radius: 24px;
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            line-height: 1.6;
            overflow-x: hidden;
        }

        a { text-decoration: none; color: inherit; transition: var(--transition); }
        ul { list-style: none; }

        /* Utiliades */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .text-gradient {
            background: linear-gradient(90deg, #fff, var(--primary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn {
            display: inline-block;
            padding: 14px 32px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            transition: var(--transition);
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
            box-shadow: 0 4px 20px var(--primary-glow);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px var(--primary-glow);
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-color);
        }

        .btn-outline:hover {
            border-color: var(--text-color);
            background: rgba(255,255,255,0.05);
        }

        /* ========================================
           HEADER / NAVBAR
           ========================================
        */
        header {
            padding: 20px 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            background: rgba(5, 5, 5, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
        }
        
        .logo i { color: var(--primary-color); }

        .nav-links {
            display: flex;
            gap: 30px;
        }

        @media(max-width: 768px) {
            .nav-links { display: none; } /* Simplificado para móvil */
        }

        /* ========================================
           HERO SECTION
           ========================================
        */
        .hero {
            padding: 160px 0 100px;
            position: relative;
            overflow: hidden;
        }

        /* Elementos decorativos de fondo */
        .glow-bg {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(47, 128, 237, 0.15) 0%, rgba(0,0,0,0) 70%);
            top: -100px;
            left: 50%;
            transform: translateX(-50%);
            z-index: -1;
            pointer-events: none;
        }

        .hero-content {
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
        }

        .hero h1 {
            font-size: 56px;
            line-height: 1.1;
            font-weight: 800;
            margin-bottom: 24px;
            letter-spacing: -1px;
        }

        .hero p {
            font-size: 20px;
            color: var(--text-muted);
            margin-bottom: 40px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-badges {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .badge {
            background: rgba(255,255,255,0.05);
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            border: 1px solid var(--border-color);
        }

        /* ========================================
           LIVE RATE TICKER (Simulado)
           ========================================
        */
        .ticker-container {
            background: var(--surface-color);
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
            padding: 15px 0;
            overflow: hidden;
            white-space: nowrap;
            position: relative;
        }

        .ticker-wrap {
            display: inline-block;
            animation: ticker 20s linear infinite;
        }

        .ticker-item {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-right: 40px;
            color: var(--text-muted);
            font-size: 14px;
        }

        .ticker-item strong { color: white; }
        .ticker-up { color: var(--secondary-color); }
        
        @keyframes ticker {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        /* ========================================
           FEATURES / WALLETS
           ========================================
        */
        .features {
            padding: 100px 0;
        }

        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-header h2 {
            font-size: 36px;
            margin-bottom: 16px;
        }

        .grid-3 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .feature-card {
            background: var(--surface-color);
            border: 1px solid var(--border-color);
            padding: 40px;
            border-radius: var(--border-radius);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary-color);
        }

        .icon-box {
            width: 60px;
            height: 60px;
            background: rgba(255,255,255,0.05);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 24px;
            color: var(--primary-color);
        }

        /* ========================================
           HOW IT WORKS (Steps)
           ========================================
        */
        .steps-section {
            padding: 100px 0;
            background: var(--surface-color);
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
        }

        .step-card {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 30px;
        }

        .step-number {
            font-size: 48px;
            font-weight: 800;
            color: rgba(255,255,255,0.05);
            line-height: 1;
        }

        /* ========================================
           CTA FINAL
           ========================================
        */
        .cta-section {
            padding: 100px 0;
            text-align: center;
        }

        .cta-box {
            background: linear-gradient(135deg, var(--surface-color) 0%, #0d121d 100%);
            border: 1px solid var(--border-color);
            border-radius: 40px;
            padding: 60px 20px;
            max-width: 900px;
            margin: 0 auto;
            position: relative;
        }

        .cta-box h2 { font-size: 32px; margin-bottom: 20px; }
        
        /* ========================================
           FOOTER
           ========================================
        */
        footer {
            padding: 60px 0;
            border-top: 1px solid var(--border-color);
            font-size: 14px;
            color: var(--text-muted);
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        /* Responsive Text */
        @media(max-width: 768px) {
            .hero h1 { font-size: 36px; }
            .hero p { font-size: 16px; }
            .grid-3 { grid-template-columns: 1fr; }
            .cta-box { padding: 40px 20px; border-radius: 24px; }
        }

    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <div class="container">
            <nav>
                <div class="logo">
                    <i class="fa-solid fa-bolt"></i> Mueve
                </div>
                <div class="nav-links">
                    <a href="#funciona">Cómo funciona</a>
                    <a href="#tarifas">Tasas</a>
                    <a href="#seguridad">Seguridad</a>
                </div>
                <div class="auth-buttons">
                    <a href="/login" class="btn btn-outline" style="padding: 10px 20px; font-size: 14px; margin-right: 10px;">Ingresar</a>
                    <a href="/register" class="btn btn-primary" style="padding: 10px 20px; font-size: 14px;">Registrarse</a>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="glow-bg"></div>
        <div class="container hero-content">
            <div class="hero-badges">
                <div class="badge"><i class="fa-solid fa-check" style="color: var(--secondary-color);"></i> Verificado</div>
                <div class="badge"><i class="fa-solid fa-rocket" style="color: var(--accent-purple);"></i> Procesamiento Automático</div>
            </div>
            <h1>
                Cambia tus <span class="text-gradient">Bolívares</span> por<br>
                Zinli o Wally al instante.
            </h1>
            <p>
                La forma más segura y rápida de recargar tus billeteras digitales en Venezuela. 
                Sin esperas, sin complicaciones y con la tasa más competitiva del mercado.
            </p>
            <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                <a href="/register.html" class="btn btn-primary">
                    Empezar Ahora <i class="fa-solid fa-arrow-right" style="margin-left: 8px;"></i>
                </a>
                <a href="#funciona" class="btn btn-outline">
                    <i class="fa-solid fa-play" style="margin-right: 8px;"></i> Cómo funciona
                </a>
            </div>
        </div>
    </section>

    <!-- Ticker de Tasas -->
    <div class="ticker-container">
        <div class="ticker-wrap">
            <div class="ticker-item"><strong>Bs a Zinli:</strong> 45.50 <span class="ticker-up"><i class="fa-solid fa-caret-up"></i> 0.5%</span></div>
            <div class="ticker-item"><strong>Bs a Wally:</strong> 45.60 <span class="ticker-up"><i class="fa-solid fa-caret-up"></i> 0.2%</span></div>
            <div class="ticker-item"><strong>Zinli a Bs:</strong> 44.20 <span class="ticker-up"><i class="fa-solid fa-minus"></i> Estable</span></div>
            <div class="ticker-item"><strong>USDT a Bs:</strong> 44.80 <span class="ticker-up"><i class="fa-solid fa-caret-up"></i> 1.2%</span></div>
            <!-- Repetir para efecto infinito -->
            <div class="ticker-item"><strong>Bs a Zinli:</strong> 45.50 <span class="ticker-up"><i class="fa-solid fa-caret-up"></i> 0.5%</span></div>
            <div class="ticker-item"><strong>Bs a Wally:</strong> 45.60 <span class="ticker-up"><i class="fa-solid fa-caret-up"></i> 0.2%</span></div>
            <div class="ticker-item"><strong>Zinli a Bs:</strong> 44.20 <span class="ticker-up"><i class="fa-solid fa-minus"></i> Estable</span></div>
        </div>
    </div>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <div class="section-header">
                <h2>¿Por qué usar Mueve?</h2>
                <p style="color: var(--text-muted);">Diseñado para freelancers, gamers y cualquiera que necesite dólares digitales.</p>
            </div>

            <div class="grid-3">
                <!-- Card 1 -->
                <div class="feature-card">
                    <div class="icon-box">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                    <h3>Velocidad Rayo</h3>
                    <p style="color: var(--text-muted); margin-top: 10px;">
                        Tus recargas se procesan en minutos. Nuestro sistema automatizado valida tu pago móvil y libera los fondos.
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="feature-card">
                    <div class="icon-box">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h3>Seguridad Bancaria</h3>
                    <p style="color: var(--text-muted); margin-top: 10px;">
                        Tus datos están encriptados. Operamos con los estándares más altos para proteger tu dinero en cada transacción.
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="feature-card">
                    <div class="icon-box">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                    <h3>Multi-Plataforma</h3>
                    <p style="color: var(--text-muted); margin-top: 10px;">
                        Recarga Zinli, Wally Tech o compra USDT. Todo desde un solo lugar sin necesidad de P2P complicados.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- How it Works -->
    <section class="steps-section" id="funciona">
        <div class="container">
            <div class="grid-3" style="align-items: center;">
                <div style="grid-column: span 1;">
                    <h2 style="font-size: 40px; line-height: 1.2; margin-bottom: 20px;">
                        Cambiar dinero nunca fue tan <span class="text-gradient">sencillo</span>.
                    </h2>
                    <p style="color: var(--text-muted); margin-bottom: 30px;">
                        Olvídate de buscar compradores en grupos de WhatsApp inseguros. En Mueve, tú tienes el control.
                    </p>
                    <a href="/register.html" class="btn btn-primary">Registrarme Gratis</a>
                </div>

                <div style="grid-column: span 2; padding-left: 40px;">
                    <div class="step-card">
                        <div class="step-number">01</div>
                        <div>
                            <h3>Crea tu orden</h3>
                            <p style="color: var(--text-muted);">Selecciona qué quieres recargar (Zinli o Wally) e ingresa el monto en Bolívares.</p>
                        </div>
                    </div>
                    <div class="step-card">
                        <div class="step-number">02</div>
                        <div>
                            <h3>Realiza el pago</h3>
                            <p style="color: var(--text-muted);">Haz un Pago Móvil a los datos que te mostramos en pantalla. Es rápido y seguro.</p>
                        </div>
                    </div>
                    <div class="step-card">
                        <div class="step-number">03</div>
                        <div>
                            <h3>Recibe tu dinero</h3>
                            <p style="color: var(--text-muted);">Verificamos tu referencia y abonamos los dólares a tu correo Zinli o billetera Wally al instante.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Social Proof / Testimonios -->
    <section style="padding: 100px 0;">
        <div class="container">
            <div class="section-header">
                <h2>Lo que dicen nuestros usuarios</h2>
            </div>
            <div class="grid-3">
                <div class="feature-card" style="padding: 30px;">
                    <div style="color: #FFD700; margin-bottom: 15px;"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                    <p style="font-style: italic; margin-bottom: 20px;">"Necesitaba saldo en Zinli urgente para pagar Netflix y Mueve me salvó. En 5 minutos ya tenía el dinero."</p>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 40px; height: 40px; background: #333; border-radius: 50%;"></div>
                        <div><strong>Carlos R.</strong><br><span style="font-size: 12px; color: var(--text-muted);">Caracas, VE</span></div>
                    </div>
                </div>
                <div class="feature-card" style="padding: 30px;">
                    <div style="color: #FFD700; margin-bottom: 15px;"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                    <p style="font-style: italic; margin-bottom: 20px;">"La mejor tasa que he conseguido para recargar Wally. La interfaz es súper fácil de usar."</p>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 40px; height: 40px; background: #333; border-radius: 50%;"></div>
                        <div><strong>Ana M.</strong><br><span style="font-size: 12px; color: var(--text-muted);">Maracaibo, VE</span></div>
                    </div>
                </div>
                <div class="feature-card" style="padding: 30px;">
                    <div style="color: #FFD700; margin-bottom: 15px;"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i></div>
                    <p style="font-style: italic; margin-bottom: 20px;">"Excelente servicio. Me gusta que no tengo que hablar con nadie, todo es automático."</p>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 40px; height: 40px; background: #333; border-radius: 50%;"></div>
                        <div><strong>Luis P.</strong><br><span style="font-size: 12px; color: var(--text-muted);">Valencia, VE</span></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-box">
                <h2>¿Listo para mover tu dinero?</h2>
                <p style="color: var(--text-muted); margin-bottom: 30px; max-width: 600px; margin-left: auto; margin-right: auto;">
                    Únete a miles de venezolanos que ya usan Mueve para gestionar sus divisas de forma inteligente.
                </p>
                <a href="/register.html" class="btn btn-primary" style="font-size: 18px; padding: 16px 40px;">
                    Crear Cuenta Gratis
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="logo">
                    <i class="fa-solid fa-bolt"></i> Mueve
                </div>
                <div>
                    &copy; 2024 Mueve Exchange. Todos los derechos reservados.
                </div>
                <div style="display: flex; gap: 20px; font-size: 20px;">
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#"><i class="fa-brands fa-telegram"></i></a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>