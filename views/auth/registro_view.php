<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-success">
                <div class="card-header bg-success text-white text-center">
                    <h3 class="mb-0"><i class="fas fa-user-plus"></i> Crear Nueva Cuenta</h3>
                </div>
                
                <div class="card-body p-4">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger shadow-sm">
                            <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= BASE_URL ?>/auth/registro" method="POST">
                        <div class="form-group mb-3">
                            <label for="usuario" class="font-weight-bold">
                                <i class="fas fa-user"></i> Nombre de Usuario *
                            </label>
                            <input type="text" name="usuario" id="usuario" 
                                   class="form-control <?= isset($errors['usuario']) ? 'is-invalid' : '' ?>" 
                                   placeholder="Elige un nombre de usuario"
                                   value="<?= htmlspecialchars($form['usuario'] ?? '') ?>"
                                   required
                                   autofocus>
                            <?php if (isset($errors['usuario'])): ?>
                                <div class="invalid-feedback"><?= htmlspecialchars($errors['usuario']) ?></div>
                            <?php endif; ?>
                            <small class="form-text text-muted">Mínimo 3 caracteres</small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="nombre" class="font-weight-bold">
                                <i class="fas fa-id-card"></i> Nombre Completo *
                            </label>
                            <input type="text" name="nombre" id="nombre" 
                                   class="form-control <?= isset($errors['nombre']) ? 'is-invalid' : '' ?>" 
                                   placeholder="Ej: Juan Pérez"
                                   value="<?= htmlspecialchars($form['nombre'] ?? '') ?>"
                                   required>
                            <?php if (isset($errors['nombre'])): ?>
                                <div class="invalid-feedback"><?= htmlspecialchars($errors['nombre']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group mb-3">
                            <label for="email" class="font-weight-bold">
                                <i class="fas fa-envelope"></i> Correo Electrónico *
                            </label>
                            <input type="email" name="email" id="email" 
                                   class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                                   placeholder="ejemplo@correo.com"
                                   value="<?= htmlspecialchars($form['email'] ?? '') ?>"
                                   required>
                            <?php if (isset($errors['email'])): ?>
                                <div class="invalid-feedback"><?= htmlspecialchars($errors['email']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="font-weight-bold">
                                    <i class="fas fa-lock"></i> Contraseña *
                                </label>
                                <input type="password" name="password" id="password" 
                                       class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
                                       placeholder="Mínimo 6 caracteres"
                                       required>
                                <?php if (isset($errors['password'])): ?>
                                    <div class="invalid-feedback"><?= htmlspecialchars($errors['password']) ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmar" class="font-weight-bold">
                                    <i class="fas fa-lock"></i> Confirmar Contraseña *
                                </label>
                                <input type="password" name="password_confirmar" id="password_confirmar" 
                                       class="form-control <?= isset($errors['password_confirmar']) ? 'is-invalid' : '' ?>" 
                                       placeholder="Repite la contraseña"
                                       required>
                                <?php if (isset($errors['password_confirmar'])): ?>
                                    <div class="invalid-feedback"><?= htmlspecialchars($errors['password_confirmar']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg btn-block mb-3">
                            <i class="fas fa-user-plus"></i> Crear Cuenta
                        </button>
                    </form>

                    <hr>

                    <p class="text-center mb-0">
                        ¿Ya tienes cuenta? 
                        <a href="<?= BASE_URL ?>/auth/login" class="font-weight-bold">
                            <i class="fas fa-sign-in-alt"></i> Inicia sesión aquí
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
