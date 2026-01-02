<?php
require_once __DIR__ . '/../core/Controller.php';

class DashboardController extends Controller {

    public function index() {
        // 1. Protección de Ruta
        if (!isset($_SESSION['token'])) {
            header('Location: /login');
            exit;
        }

        // 2. Datos para la vista
        $userMail = $_SESSION['email']; // Recuperamos datos guardados en login
        $userName = $_SESSION['name']; // Recuperamos datos guardados en login

        $data = [
            'title' => APP_NAME . ' | Dashboard',
            'userMail' => $userMail,
            'userName' => $userName,
            'current_rate' => 310.94 // Simulamos la tasa actual (idealmente vendría de una API)
        ];

        $this->view('dashboard/index', $data);
    }

    // ... dentro de DashboardController ...

    public function history() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        // 1. Protección
        if (!isset($_SESSION['token'])) {
            header('Location: /login');
            exit;
        }

        // 2. Obtener TODAS las transacciones
        // (Aquí podrías agregar lógica de paginación en el futuro)
        $transactions = $this->fetchTransactions($_SESSION['token']);

        // 3. Renderizar vista de historial
        $data = [
            'title' => 'Historial | ExchangeApp',
            'userMail' => $_SESSION['email'],
            'transactions' => $transactions
        ];

        $this->view('dashboard/history', $data);
    }

    private function fetchTransactions($token) {
        $ch = curl_init(API_URL . '/transactions'); // Asumiendo GET
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $token",
            "Content-Type: application/json"
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            $json = json_decode($response, true);
            // Si la API devuelve { transactions: [...] } o directo [...]
            return $json['transactions'] ?? $json ?? []; 
        }

        return []; // Si falla, devolvemos array vacío para no romper la vista
    }
}
?>