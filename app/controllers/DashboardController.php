<?php
require_once __DIR__ . '/../core/Controller.php';

class DashboardController extends Controller {

    public function index() {
        // 1. Protección de Ruta (verifica token + expiración)
        $this->requireAuth();

        // 2. Datos para la vista
        $userMail = $_SESSION['email'];
        $userName = $_SESSION['name'];

        $data = [
            'title' => APP_NAME . ' | Dashboard',
            'icon' => ICON_PATH,
            'userMail' => $userMail,
            'userName' => $userName,
            'csrf_token' => $this->generateCsrfToken()
        ];

        // SERVER-SIDE: obtener métodos de pago del API usando el token almacenado en la sesión.
        $paymentMethods = [];
        if (!empty($_SESSION['token'])) {
            $ch = curl_init(API_URL . '/payments-methods');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer " . $_SESSION['token'],
                "Content-Type: application/json"
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                $json = json_decode($response, true);
                $paymentMethods = is_array($json) ? $json : [];
            }
        }

        $data['payment_methods'] = $paymentMethods;

        // Obtener tasa actual desde la API (endpoint público)
        $currentRate = 0;
        $ch2 = curl_init(API_URL . '/rate');
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch2, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json"
        ]);
        $rateResp = curl_exec($ch2);
        $rateCode = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
        curl_close($ch2);
        if ($rateCode === 200) {
            $rateJson = json_decode($rateResp, true);
            if (isset($rateJson['rate'])) $currentRate = floatval($rateJson['rate']);
        }

        $data['current_rate'] = $currentRate ?: ($data['current_rate'] ?? 0);

        // Datos de pago de Mueve (antes hardcodeados en app.js)
        $data['mueve_payment_config'] = [
            'owner' => 'Mueve',
            'phone' => '0424-3354141',
            'bank' => 'BNC - 0191',
            'ci' => '29.846.137',
            'email' => 'yohanderjose2002@gmail.com'
        ];

        $this->view('dashboard/index', $data);
    }

    public function history() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        // Protección con verificación de expiración
        $this->requireAuth();

        $transactions = $this->fetchTransactions($_SESSION['token']);

        $data = [
            'title' => 'Historial | Mueve',
            'icon' => ICON_PATH,
            'userMail' => $_SESSION['email'],
            'transactions' => $transactions
        ];

        $this->view('dashboard/history', $data);
    }

    public function payment_methods() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $this->requireAuth();

        $transactions = $this->fetchTransactions($_SESSION['token']);

        $data = [
            'title' => 'Métodos de Pago | Mueve',
            'icon' => ICON_PATH,
            'userMail' => $_SESSION['email'],
            'transactions' => $transactions
        ];

        $this->view('dashboard/payment-methods', $data);
    }

    private function fetchTransactions($token) {
        $ch = curl_init(API_URL . '/transactions');
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
            return $json['transactions'] ?? $json ?? []; 
        }

        return [];
    }
}
?>