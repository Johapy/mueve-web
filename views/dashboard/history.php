<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title']; ?></title>
    <link rel="icon" type="image/x-icon" href="<?php echo $icon; ?>">
    <link rel="stylesheet" href="/assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="dashboard-container">
    
    <?php require_once __DIR__ . '/components/sidebar.php'; ?>

    <main class="main-content">
        
        <div class="content-header">
            <div>
                <h2>Historial de Movimientos</h2>
                <p style="color: var(--text-muted); margin-top: 5px;">Tus operaciones recientes</p>
            </div>
            
            <div style="position: relative; width: 300px; display: none; @media(min-width: 768px){display:block;}">
                <i class="fa-solid fa-search" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                <input type="text" placeholder="Buscar referencia..." style="padding-left: 40px; background-color: var(--surface-color); border-radius: 50px;">
            </div>
        </div>

        <?php if (empty($data['transactions'])): ?>
            <div class="card" style="text-align: center; padding: 60px 20px;">
                <div style="font-size: 48px; color: var(--text-muted); margin-bottom: 20px; opacity: 0.3;">
                    <i class="fa-solid fa-folder-open"></i>
                </div>
                <h3 style="margin-bottom: 10px;">Aún no hay movimientos</h3>
                <p style="color: var(--text-muted); margin-bottom: 30px;">Tus operaciones de cambio aparecerán aquí.</p>
                <a href="/dashboard" class="btn-primary">Nueva Operación</a>
            </div>

        <?php else: ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Tipo / ID</th>
                            <th>Plataforma</th>
                            <th>Monto USD</th>
                            <th>Tasa</th>
                            <th>Total Bs</th>
                            <th>Estado</th>
                            <th>Fecha/Ref</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $t): ?>
                            <tr>
                                <td data-label="Tipo / ID">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div style="
                                            width: 32px; height: 32px; 
                                            border-radius: 50%; 
                                            display: flex; align-items: center; justify-content: center;
                                            background-color: <?php echo ($t['transaction_type'] == 'Comprar') ? 'rgba(0, 204, 102, 0.1)' : 'rgba(255, 77, 77, 0.1)'; ?>;
                                            color: <?php echo ($t['transaction_type'] == 'Comprar') ? 'var(--success-color)' : 'var(--danger-color)'; ?>;
                                        ">
                                            <i class="fa-solid <?php echo ($t['transaction_type'] == 'Comprar') ? 'fa-arrow-down' : 'fa-arrow-up'; ?>"></i>
                                        </div>
                                        <div>
                                            <div style="font-weight: 600;"><?php echo htmlspecialchars($t['transaction_type']); ?></div>
                                            <div style="font-size: 10px; color: var(--text-muted);">ID: #<?php echo $t['id']; ?></div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td data-label="Plataforma">
                                    <span style="color: var(--text-color); font-weight: 500;">
                                        <?php echo htmlspecialchars($t['type_pay']); ?>
                                    </span>
                                </td>
                                
                                <td data-label="Monto USD">
                                    <div class="amount-usd">$<?php echo number_format($t['amount_usd'], 2); ?></div>
                                </td>
                                
                                <td data-label="Tasa" style="color: var(--text-muted);">
                                    <?php echo $t['rate_bs']; ?>
                                </td>
                                
                                <td data-label="Total Bs">
                                    <div class="amount-bs">Bs <?php echo number_format($t['total_bs'], 2); ?></div>
                                </td>
                                
                                <td data-label="Estado">
                                    <?php 
                                        $statusClass = ($t['status'] === 'Completado') ? 'badge-success' : 'badge-pending';
                                    ?>
                                    <span class="badge <?php echo $statusClass; ?>">
                                        <?php echo htmlspecialchars($t['status']); ?>
                                    </span>
                                </td>
                                
                                <td data-label="Fecha/Ref">
                                    <div style="color: var(--text-muted); font-family: monospace;">
                                        <?php echo htmlspecialchars($t['payment_reference']); ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

    </main>
</div>

</body>
</html>