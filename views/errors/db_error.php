<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error de Base de Datos - Agenda Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="error-wrapper">
        <div class="error-card db-error-card">
            <div class="error-icon">⚠️</div>
            <h1 class="error-title"><?= htmlspecialchars($data['titulo']) ?></h1>
            <p class="error-text">
                <?= htmlspecialchars($data['mensaje']) ?>
            </p>
            <div class="technical-notice">
                El administrador ha sido notificado. Por favor, inténtelo de nuevo más tarde.
            </div>
            <a href="<?= BASE_URL ?>" class="btn-error btn-db">Reintentar conexión</a>
        </div>
    </div>
</body>
</html>