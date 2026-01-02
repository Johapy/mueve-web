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
        
        // Configurar cURL hacia Node.js
        $ch = curl_init(API_URL . '/transactions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $inputJSON);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $_SESSION['token'] // ¡Importante! El token va aquí
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