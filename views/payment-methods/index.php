<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="/assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="dashboard-container">
    <?php require_once __DIR__ . '/../dashboard/components/sidebar.php'; ?>

    <main class="main-content">

        <div class="content-header">
            <div>
                <h2>Métodos de Pago</h2>
                <p style="color: var(--text-muted); margin-top: 5px;">Agregar y administrar tus métodos de cobro</p>
            </div>
        </div>

        <div class="card" style="margin-top: 16px;">
            <?php if(isset($_GET['added'])): ?>
                <div class="flash-message" style="border-color:var(--secondary-color); margin-bottom:12px;">Método agregado correctamente.</div>
            <?php endif; ?>
            <?php if(isset($_GET['error'])): ?>
                <div class="flash-message" style="border-color:crimson; margin-bottom:12px;">Error al agregar el método.</div>
            <?php endif; ?>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:24px; align-items:start;">
                <div>
                    <h3>Agregar Método</h3>
                    <form method="post" action="/payment-methods/add" id="addPaymentForm">
                        <div class="form-group">
                            <label>Tipo</label>
                            <select name="type" id="pmType" required>
                                <option value="Zinli">Zinli</option>
                                <option value="Wally">Wally</option>
                                <option value="USDT">USDT</option>
                                <option value="PagoMovil">PagoMovil</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Nombre (owner_name)</label>
                            <input type="text" name="owner_name" required>
                        </div>

                        <div id="mailRow" style="display:block;">
                            <div class="form-group">
                                <label>Email (mail_pay)</label>
                                <input type="email" name="mail_pay" id="mailPayInput">
                            </div>
                        </div>

                        <div id="pagomovilRows" style="display:none;">
                            <div class="form-group">
                                <label>CI</label>
                                <input type="text" name="ci">
                            </div>
                            <div class="form-group">
                                <label>Banco</label>
                                <input type="text" name="bank">
                            </div>
                            <div class="form-group">
                                <label>Teléfono</label>
                                <input type="text" name="phone">
                            </div>
                        </div>

                        <div style="display:flex; gap:10px; margin-top:12px;">
                            <button type="submit" class="btn btn-primary">Agregar</button>
                        </div>
                    </form>
                </div>

                <div>
                    <h3>Listado de Métodos</h3>

                    <?php
                        $methods = $payment_methods ?? [];
                        if(empty($methods)){
                            echo '<p class="text-muted">No hay métodos guardados aún.</p>';
                        } else {
                            // Agrupar por tipo
                            $groups = [];
                            foreach($methods as $m){
                                $t = $m['type'] ?? 'Otros';
                                $groups[$t][] = $m;
                            }

                            foreach($groups as $type => $items){
                                echo "<h4 style=\"margin-top:12px;\">".htmlspecialchars($type)."</h4>";
                                echo "<div style=\"display:flex;flex-direction:column;gap:8px;\">";
                                foreach($items as $it){
                                    $owner = htmlspecialchars($it['owner_name'] ?? '');
                                    $typeSafe = htmlspecialchars($it['type'] ?? '');
                                    if(strtolower($type) === 'pagomovil'){
                                        $ci = htmlspecialchars($it['ci'] ?? '');
                                        $bank = htmlspecialchars($it['bank'] ?? '');
                                        $phone = htmlspecialchars($it['phone'] ?? '');
                                        $recipient = htmlspecialchars(trim(($phone ? $phone.' — ' : '') . ($bank ? $bank.' — ' : '') . $ci));
                                    } else {
                                        $recipient = htmlspecialchars($it['mail_pay'] ?? '');
                                    }

                                    echo "<div class=\"card pm-list-item\" style=\"padding:12px; border-radius:14px; display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:10px;\">";
                                    echo "<div style=\"min-width:0;\">";
                                    echo "<div style=\"font-weight:700; font-size:15px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;\">$owner</div>";
                                    echo "<div style=\"color:var(--text-muted); font-size:13px; margin-top:6px;\">$typeSafe</div>";
                                    echo "</div>";
                                    echo "<div style=\"text-align:right; min-width:160px; max-width:260px;\">";
                                    echo "<div style=\"font-weight:600; color:var(--primary-color); font-size:13px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;\">$recipient</div>";
                                    echo "</div>";
                                    echo "</div>";
                                }
                                echo "</div>";
                            }
                        }
                    ?>
                </div>
            </div>
        </div>

    </main>
</div>

<script>
    // Toggle fields según tipo seleccionado
    const pmType = document.getElementById('pmType');
    const mailRow = document.getElementById('mailRow');
    const pagomovilRows = document.getElementById('pagomovilRows');

    function togglePmFields(){
        const v = pmType.value;
        if(v === 'PagoMovil'){
            mailRow.style.display = 'none';
            pagomovilRows.style.display = 'block';
        } else {
            mailRow.style.display = 'block';
            pagomovilRows.style.display = 'none';
        }
    }

    pmType.addEventListener('change', togglePmFields);
    document.addEventListener('DOMContentLoaded', togglePmFields);
</script>

</body>
</html>
