<?php
require_once __DIR__ . '/../core/Controller.php';

class TransactionController extends Controller {

    public function store() {
        // Verificar sesión
        if (!isset($_SESSION['token'])) {
            http_response_code(401);
            echo json_encode(['message' => 'No autorizado']);
            exit;
        }

        // Leer el JSON que envía el JS
        $inputJSON = file_get_contents('php://input');

        // Decodificar para asegurarnos de incluir recipient_account y otros campos necesarios
        $payload = json_decode($inputJSON, true);
        if (!is_array($payload)) $payload = [];

        // Garantizar que exista la clave recipient_account (puede venir de un hidden field en el formulario)
        if (!isset($payload['recipient_account'])) {
            $payload['recipient_account'] = null;
        }

        // Re-encodificar y reenviar al endpoint de la API externa
        $forwardJson = json_encode($payload);

        // Configurar cURL hacia la API externa y reenviar el body (incluyendo recipient_account)
        $ch = curl_init(API_URL . '/transactions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $forwardJson);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $_SESSION['token'] // token de sesión para la API
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