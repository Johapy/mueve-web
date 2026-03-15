<?php

class AuthController extends Controller
{

    public function loginForm()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $data = [
            'title' => 'Iniciar Sesión',
            'csrf_token' => $this->generateCsrfToken()
        ];

        // si hay mensajes flash en sesión, pasarlos y eliminarlos
        if (!empty($_SESSION['flash_success'])) {
            $data['success'] = $_SESSION['flash_success'];
            unset($_SESSION['flash_success']);
        }
        if (!empty($_SESSION['flash_error'])) {
            $data['error'] = $_SESSION['flash_error'];
            unset($_SESSION['flash_error']);
        }

        $this->view('auth/login', $data);
    }

    public function registerForm()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $data = [
            'title' => 'Registro',
            'csrf_token' => $this->generateCsrfToken()
        ];
        if (!empty($_SESSION['flash_error'])) {
            $data['error'] = $_SESSION['flash_error'];
            unset($_SESSION['flash_error']);
        }
        $this->view('auth/register', $data);
    }

    public function logout(){
        // Asegurar que la sesión está iniciada
        session_destroy();

        // Redirigir
        header('Location: /login');
        exit;
    }




    // --- 1. Función auxiliar para codificar en Base64Url (el formato de JWT) ---
    private function base64url_encode($data)
    {
        // Reemplazamos caracteres no seguros para URL (+ y /) y quitamos el relleno (=)
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->view('auth/login', ['title' => 'Iniciar Sesión', 'csrf_token' => $this->generateCsrfToken()]);
            return;
        }

        // Validar CSRF
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!$this->validateCsrfToken($csrfToken)) {
            $this->view('auth/login', [
                'title' => 'Iniciar Sesión',
                'error' => 'Solicitud inválida. Recarga la página e intenta de nuevo.',
                'csrf_token' => $this->generateCsrfToken()
            ]);
            return;
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $data = json_encode(['email' => $email, 'password' => $password]);

        $ch = curl_init(API_URL . '/login');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $responseData = json_decode($response, true);

        // --- AQUÍ EMPIEZA LA MAGIA DE LA VERIFICACIÓN ---
        if ($httpCode === 200 && isset($responseData['token'])) {

            $token = $responseData['token'];
            $secret = 'supersecretkey'; // Debe ser IDENTICA a la de Node.js

            // 1. Desarmamos el token
            $tokenParts = explode('.', $token);

            if (count($tokenParts) === 3) {
                $header = $tokenParts[0];
                $payload = $tokenParts[1];
                $signatureProvided = $tokenParts[2];

                // 2. Calculamos la firma nosotros mismos
                // Fórmula: HMAC-SHA256(Header . Payload, Secreto)
                $signatureCalculated = hash_hmac('sha256', $header . "." . $payload, $secret, true);

                // 3. Codificamos el resultado para que tenga el formato correcto
                $signatureCalculatedEncoded = $this->base64url_encode($signatureCalculated);

                // 4. COMPARACIÓN FINAL (El momento de la verdad)
                // Usamos hash_equals para mayor seguridad contra hackers
                if (hash_equals($signatureProvided, $signatureCalculatedEncoded)) {

                    // ¡ES VÁLIDO! 🎉
                    $_SESSION['token'] = $token;

                    // Decodificamos el payload para sacar los datos
                    $payloadData = json_decode(base64_decode($payload), true);
                    $_SESSION['user_id'] = $payloadData['id'];
                    $_SESSION['email'] = $payloadData['email'];
                    $_SESSION['name'] = $payloadData['name'];
                    // Guardar expiración del token para referencia
                    if (isset($payloadData['exp'])) {
                        $_SESSION['token_exp'] = $payloadData['exp'];
                    }

                    header('Location: /dashboard');
                    exit;
                } else {
                    // El token fue alterado o la clave secreta no coincide
                    $this->view('auth/login', ['title' => 'Iniciar Sesión', 'error' => 'Error de seguridad: Token inválido', 'icon' => ICON_PATH]);
                    return;
                }
            }
        }

        // Si llegamos aquí, algo falló en el login o en la validación
        $error = $responseData['error'] ?? 'Credenciales incorrectas o error de sistema';
        $this->view('auth/login', ['title' => 'Iniciar Sesión', 'error' => $error]);
    }


    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->view('auth/register', ['title' => 'Registro', 'csrf_token' => $this->generateCsrfToken()]);
            return;
        }

        // Validar CSRF
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!$this->validateCsrfToken($csrfToken)) {
            $this->view('auth/register', [
                'title' => 'Registro',
                'error' => 'Solicitud inválida. Recarga la página e intenta de nuevo.',
                'csrf_token' => $this->generateCsrfToken()
            ]);
            return;
        }

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $data = json_encode(['name' => $name, 'email' => $email, 'password' => $password]);

        $ch = curl_init(API_URL . '/register');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $responseData = json_decode($response, true);

        if ($httpCode === 201) {
            // Registro exitoso, utilizamos flash y redirigimos al login
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['flash_success'] = 'Usuario creado correctamente. Inicia sesión para continuar.';
            header('Location: /login');
            exit;
        } else {
            $error = $responseData['error'] ?? 'Error en el registro';
            $this->view('auth/register', ['title' => 'Registro', 'error' => $error, 'icon' => ICON_PATH]);
        }
    }

    // CRÍTICO: Este es el endpoint que llamará JavaScript después de loguearse en Node.js
    public function syncSession()
    {
        // Recibimos el JSON que envía JS
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['user_id'])) {
            // Guardamos el ID en la sesión de PHP
            $_SESSION['user_id'] = $data['user_id'];
            $_SESSION['email'] = $data['email'] ?? '';

            echo json_encode(['status' => 'success', 'message' => 'Sesión sincronizada en PHP']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Faltan datos']);
        }
    }

}
