<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | ExchangeApp</title>
    <link rel="icon" type="image/x-icon" href="<?php echo $icon; ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ========================================
           ESTILOS LOGIN (Over9k Dark Theme)
           ========================================
        */
        :root {
            /* Paleta de colores */
            --bg-color: #050505;
            --surface-color: #121212;
            --input-bg: #1e1e1e;
            --border-color: #2a2a2a;
            
            --text-color: #e0e0e0;
            --text-muted: #888888;
            
            --primary-color: #2F80ED;
            --primary-hover: #1a6cdb;
            --danger-color: #FF4D4D;
            
            --border-radius: 24px;
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* Contenedor Principal */
        .auth-container {
            width: 100%;
            max-width: 400px;
            animation: fadeIn 0.5s ease-out;
        }

        /* Tarjeta */
        .auth-card {
            background-color: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            padding: 40px 30px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
            position: relative;
            overflow: hidden;
        }

        /* Efecto de luz sutil en la parte superior */
        .auth-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--primary-color), transparent);
            opacity: 0.7;
        }

        /* Header */
        .auth-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .auth-header .logo-icon {
            font-size: 40px;
            color: var(--primary-color);
            margin-bottom: 15px;
            display: inline-block;
            filter: drop-shadow(0 0 10px rgba(47, 128, 237, 0.4));
        }

        .auth-header h2 {
            font-size: 24px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 5px;
        }

        .auth-header p {
            color: var(--text-muted);
            font-size: 14px;
        }

        /* Formularios e Inputs */
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-muted);
            margin-left: 5px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            transition: var(--transition);
        }

        input {
            width: 100%;
            background-color: var(--input-bg);
            border: 1px solid transparent;
            color: var(--text-color);
            padding: 14px 18px 14px 45px; /* Padding extra a la izquierda para el icono */
            border-radius: 16px;
            outline: none;
            font-size: 14px;
            transition: var(--transition);
        }

        input:focus {
            background-color: #252525;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(47, 128, 237, 0.15);
        }

        input:focus + i,
        .input-wrapper:focus-within i {
            color: var(--primary-color);
        }

        /* Botón */
        .btn-primary {
            width: 100%;
            background-color: var(--primary-color);
            color: #fff;
            border: none;
            padding: 14px;
            font-size: 15px;
            font-weight: 600;
            border-radius: 50px;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(47, 128, 237, 0.4);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(47, 128, 237, 0.6);
        }

        /* Footer */
        .auth-footer {
            margin-top: 25px;
            text-align: center;
            font-size: 14px;
            color: var(--text-muted);
        }

        .auth-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .auth-footer a:hover {
            text-decoration: underline;
            color: #fff;
        }

        /* Alertas */
        .flash-message {
            background: rgba(255, 77, 77, 0.1);
            border: 1px solid rgba(255, 77, 77, 0.3);
            color: var(--danger-color);
            padding: 12px;
            border-radius: 12px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="auth-body">

    <div class="auth-container">
        
        <div class="auth-card">
            <div class="auth-header">
                <!-- Icono de marca -->
                <img class="logo-icon" src="/assets/mueve.ico" style="width: 100%; max-width: 100px; height: auto;" alt="Logo de Mueve">
                <h2>Bienvenido</h2>
                <p>Ingresa a tu panel de divisas</p>
            </div>

            <!-- Simulación de error PHP para el diseño (visible si la variable existiera) -->
            <!-- <?php if (isset($error)): ?> -->
            <div class="flash-message error" style="display: none;" id="demoError">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>Usuario o contraseña incorrectos</span>
            </div>
            <!-- <?php endif; ?> -->

            <form action="/login" method="POST">
                
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <div class="input-wrapper">
                        <input type="email" name="email" id="email" placeholder="ejemplo@correo.com" required autofocus>
                        <i class="fa-regular fa-envelope"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="input-wrapper">
                        <input type="password" name="password" id="password" placeholder="••••••••" required>
                        <i class="fa-solid fa-lock"></i>
                    </div>
                </div>

                <!-- Opcional: Recordar / Olvidé contraseña -->
                <div style="display: flex; justify-content: space-between; margin-bottom: 20px; font-size: 12px;">
                    <label style="color: var(--text-muted); display: flex; align-items: center; gap: 5px; cursor: pointer;">
                        <input type="checkbox" style="width: auto; margin: 0;"> Recordarme
                    </label>
                    <a href="#" style="color: var(--text-muted); text-decoration: none;">¿Olvidaste tu clave?</a>
                </div>

                <button type="submit" class="btn-primary">
                    Ingresar <i class="fa-solid fa-arrow-right" style="margin-left: 8px;"></i>
                </button>
            </form>

            <div class="auth-footer">
                <p>¿No tienes cuenta? <a href="/register">Regístrate aquí</a></p>
            </div>
        </div>

    </div>

</body>
</html>