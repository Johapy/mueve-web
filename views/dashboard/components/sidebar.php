



<?php $uri = $_SERVER['REQUEST_URI']; ?>

<aside class="sidebar">
    <h3><i class="fa-solid fa-wallet"></i> ExchangeApp</h3>
    <nav>
        <a href="/dashboard" class="nav-link <?php echo ($uri == '/dashboard') ? 'active' : ''; ?>">
            <i class="fa-solid fa-chart-line"></i> 
            <span>Nueva Transacción</span> </a>
        <a href="/history" class="nav-link <?php echo ($uri == '/history') ? 'active' : ''; ?>">
            <i class="fa-solid fa-clock-rotate-left"></i> 
            <span>Historial</span>
        </a>
        <a href="#" class="nav-link">
            <i class="fa-solid fa-user"></i> 
            <span>Perfil</span>
        </a>
    </nav>
    <div style="margin-top: auto;">
        <form action="/logout" method="POST">
            <button type="submit" class="btn-primary" style="background: transparent; border: 1px solid var(--border-color); width: 100%;">
                <i class="fa-solid fa-sign-out-alt"></i> Cerrar Sesión
            </button>
        </form>
    </div>
</aside>