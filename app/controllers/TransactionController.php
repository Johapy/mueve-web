<?php
require_once __DIR__ . '/../core/Controller.php';

class TransactionController extends Controller {

    public function store() {
        // Verificar sesión + expiración del token
        $this->requireAuth();

        // Validar CSRF (el JS envía el token en un header X-CSRF-Token)
        $csrfToken = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (!$this->validateCsrfToken($csrfToken)) {
            http_response_code(403);
            echo json_encode(['message' => 'Token CSRF inválido. Recarga la página.']);
            exit;
        }

        // Leer el JSON que envía el JS
        $inputJSON = file_get_contents('php://input');

        // Decodificar para asegurarnos de incluir recipient_account y otros campos necesarios
        $payload = json_decode($inputJSON, true);
        if (!is_array($payload)) $payload = [];

        // Garantizar que exista la clave recipient_account
        if (!isset($payload['recipient_account'])) {
            $payload['recipient_account'] = null;
        }

        // Re-encodificar y reenviar al endpoint de la API externa
        $forwardJson = json_encode($payload);

        // Configurar cURL hacia la API externa y reenviar el body
        $ch = curl_init(API_URL . '/transactions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $forwardJson);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $_SESSION['token']
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Devolver respuesta tal cual al JS
        http_response_code($httpCode);
        echo $response;
    }
}
?>