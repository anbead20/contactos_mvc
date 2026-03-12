<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?= BASE_URL ?>/">
            <i class="fas fa-address-book me-2"></i> Agenda de Contactos
        </a>
        
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/contactos">Contactos</a>
                </li>
            </ul>
            
            <ul class="navbar-nav ml-auto">
                <?php if (isset($_SESSION['autenticado']) && $_SESSION['autenticado']): ?>
                    <li class="nav-item">
                        <span class="navbar-text text-light mr-3">
                            <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['nombre'] ?? $_SESSION['usuario']) ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-light btn-sm" href="<?= BASE_URL ?>/auth/logout">
                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/auth/login">
                            <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-success btn-sm" href="<?= BASE_URL ?>/auth/registro">
                            <i class="fas fa-user-plus"></i> Registrarse
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
