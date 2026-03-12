<?php
/*****************************************************
 * AuthMiddleware - Middleware de autenticación
 * 
 * Verifica que el usuario esté autenticado antes de
 * permitir el acceso a rutas protegidas.
*/

namespace App\Middleware;

use App\Services\AuthService;

class AuthMiddleware
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    /**
     * Ejecutar el middleware - verificar autenticación
     */
    public function handle(): bool
    {
        // Verificar si el usuario está autenticado
        if (!$this->authService->estaAutenticado()) {
            // Guardar la URL actual para redirigir después del login
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? '/contactos';
            
            // Redirigir al login
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        return true;
    }
}
