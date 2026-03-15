<?php

class Controller {
    // Método para cargar vistas y pasarle datos
    protected function view($path, $data = []) {
        // Convierte el array asociativo en variables: ['titulo' => 'Hola'] se vuelve $titulo = 'Hola'
        extract($data);
        
        // Busca la vista en la carpeta views
        $viewFile = __DIR__ . '/../../views/' . $path . '.php';
        
        if (file_exists($viewFile)) {
            require $viewFile; 
            return; 
        }
        
        // Si no existe, lanza un error para que sepamos qué pasó
        die("Error: La vista '$viewFile' no existe.");
    }

    // ========================
    // CSRF Protection
    // ========================

    /**
     * Genera un token CSRF y lo almacena en la sesión.
     * Si ya existe uno válido, lo reutiliza (para no romper formularios abiertos).
     */
    protected function generateCsrfToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Valida el token CSRF recibido contra el de la sesión.
     * Después de validar, se regenera para one-time use (evita replay attacks).
     * @param string $token — token recibido del formulario/header
     * @return bool
     */
    protected function validateCsrfToken($token) {
        if (empty($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }
        $valid = hash_equals($_SESSION['csrf_token'], $token);
        if ($valid) {
            // Regenerar para que cada envío necesite un token nuevo
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $valid;
    }

    // ========================
    // JWT Token Expiration
    // ========================

    /**
     * Verifica si el JWT almacenado en sesión ha expirado.
     * Lee el claim `exp` del payload sin necesitar librerías externas.
     * @return bool — true si el token está expirado o no existe
     */
    protected function isTokenExpired() {
        if (empty($_SESSION['token'])) {
            return true;
        }

        $parts = explode('.', $_SESSION['token']);
        if (count($parts) !== 3) {
            return true;
        }

        $payload = json_decode(base64_decode($parts[1]), true);
        if (!$payload || !isset($payload['exp'])) {
            // Si no tiene exp, lo tratamos como válido (depende de tu API)
            return false;
        }

        return time() >= $payload['exp'];
    }

    /**
     * Verifica que la sesión tenga un token válido (existe y no ha expirado).
     * Si no es válido, destruye la sesión y redirige al login.
     */
    protected function requireAuth() {
        if (!isset($_SESSION['token']) || $this->isTokenExpired()) {
            // Limpiar sesión si el token expiró
            if ($this->isTokenExpired() && isset($_SESSION['token'])) {
                session_destroy();
                session_start();
                $_SESSION['flash_error'] = 'Tu sesión ha expirado. Por favor, inicia sesión de nuevo.';
            }
            header('Location: /login');
            exit;
        }
    }
}