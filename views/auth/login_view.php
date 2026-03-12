<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-primary">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="mb-0"><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</h3>
                </div>
                
                <div class="card-body p-4">
                    <?php if (isset($_GET['success']) && $_GET['success'] === 'registered'): ?>
                        <div class="alert alert-success shadow-sm">
                            <i class="fas fa-check-circle"></i> ¡Registro exitoso! Ya puedes iniciar sesión.
                        </div>
                    <?php endif; ?>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger shadow-sm">
                            <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= BASE_URL ?>/auth/login" method="POST">
                        <div class="form-group mb-3">
                            <label for="usuario" class="font-weight-bold">
                                <i class="fas fa-user"></i> Usuario
                            </label>
                            <input type="text" name="usuario" id="usuario" 
                                   class="form-control form-control-lg" 
                                   placeholder="Nombre de usuario"
                                   value="<?= htmlspecialchars($form['usuario'] ?? '') ?>"
                                   required
                                   autofocus>
                        </div>

                        <div class="form-group mb-4">
                            <label for="password" class="font-weight-bold">
                                <i class="fas fa-lock"></i> Contraseña
                            </label>
                            <input type="password" name="password" id="password" 
                                   class="form-control form-control-lg" 
                                   placeholder="Contraseña"
                                   required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg btn-block mb-3">
                            <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                        </button>
                    </form>

                    <hr>

                    <p class="text-center mb-0">
                        ¿No tienes cuenta? 
                        <a href="<?= BASE_URL ?>/auth/registro" class="font-weight-bold">
                            <i class="fas fa-user-plus"></i> Regístrate aquí
                        </a>
                    </p>

                    <p class="text-center mt-2 mb-0">
                        <a href="<?= BASE_URL ?>/" class="text-muted">
                            <i class="fas fa-arrow-left"></i> Volver al inicio
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
