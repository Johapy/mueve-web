<?php
require_once __DIR__ . '/../core/Controller.php';

class PaymentMethodsController extends Controller {

    // Mostrar la página de métodos de pago y listado
    public function index() {
        if (!isset($_SESSION['token'])) {
            header('Location: /login');
            exit;
        }

        $userMail = $_SESSION['email'] ?? '';
        $userName = $_SESSION['name'] ?? '';

        // Obtener métodos desde la API (server-side con token)
        $methods = [];
        if (!empty($_SESSION['token'])) {
            $ch = curl_init(API_URL . '/payments-methods');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer " . $_SESSION['token'],
                "Content-Type: application/json"
            ]);
            $resp = curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($code === 200) {
                $json = json_decode($resp, true);
                $methods = is_array($json) ? $json : [];
            }
        }

        $data = [
            'title' => APP_NAME . ' | Métodos de Pago',
            'icon' => ICON_PATH,
            'userMail' => $userMail,
            'userName' => $userName,
            'payment_methods' => $methods
        ];

        $this->view('payment-methods/index', $data);
    }

    // Manejar creación de nuevo método de pago (form POST)
    public function add() {
        if (!isset($_SESSION['token'])) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /payment-methods');
            exit;
        }

        $type = $_POST['type'] ?? '';
        $owner_name = $_POST['owner_name'] ?? '';

        $payload = [];
        // Construir payload según tipo
        if (in_array($type, ['USDT','Zinli','Wally'])) {
            $payload = [
                'type' => $type,
                'mail_pay' => $_POST['mail_pay'] ?? '',
                'owner_name' => $owner_name
            ];
        } elseif ($type === 'PagoMovil') {
            $payload = [
                'type' => $type,
                'ci' => $_POST['ci'] ?? '',
                'bank' => $_POST['bank'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'owner_name' => $owner_name
            ];
        }

        // Enviar a la API
        $ch = curl_init(API_URL . '/payments-methods/add');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $_SESSION['token']
        ]);

        $resp = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Redirigir de vuelta con parámetro flash simple (query)
        if ($code === 201 || $code === 200) {
            header('Location: /payment-methods?added=1');
            exit;
        } else {
            header('Location: /payment-methods?error=1');
            exit;
        }
    }

}
