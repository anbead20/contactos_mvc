<?php
/*****************************************************
 * AuthController - Controlador de autenticación
 * 
 * Gestiona todas las acciones relacionadas con
 * autenticación de usuarios: registro, login, logout
*/

namespace App\Controllers;

use App\Services\AuthService;

class AuthController extends BaseController 
{
    private AuthService $authService;
    
    public function __construct() 
    {
        parent::__construct();
        $this->authService = new AuthService();
    }

    /**
     * Mostrar formulario de login
     */
    public function loginAction(): void
    {
        // Si ya está autenticado, redirigir al inicio
        if ($this->authService->estaAutenticado()) {
            $this->redirect('/contactos');
            return;
        }

        $this->renderHTML(VIEWS_DIR . '/auth/login_view.php', [
            'titulo' => 'Iniciar Sesión'
        ]);
    }

    /**
     * Procesar formulario de login
     */
    public function loginPostAction(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/auth/login');
            return;
        }

        $usuario = $_POST['usuario'] ?? '';
        $password = $_POST['password'] ?? '';

        // Validación básica
        if (empty($usuario) || empty($password)) {
            $this->renderHTML(VIEWS_DIR . '/auth/login_view.php', [
                'titulo' => 'Iniciar Sesión',
                'error' => 'Por favor, complete todos los campos',
                'form' => [
                    'usuario' => htmlspecialchars($usuario, ENT_QUOTES, 'UTF-8')
                ]
            ]);
            return;
        }

        // Intentar autenticar
        $resultado = $this->authService->autenticar($usuario, $password);

        if (!$resultado['exito']) {
            $this->renderHTML(VIEWS_DIR . '/auth/login_view.php', [
                'titulo' => 'Iniciar Sesión',
                'error' => $resultado['mensaje'],
                'form' => [
                    'usuario' => htmlspecialchars($usuario, ENT_QUOTES, 'UTF-8')
                ]
            ]);
            return;
        }

        // Iniciar sesión
        $this->authService->iniciarSesion($resultado['usuario']);

        // Redirigir a la página de contactos
        $this->redirect('/contactos?success=login');
    }

    /**
     * Mostrar formulario de registro
     */
    public function registroAction(): void
    {
        // Si ya está autenticado, redirigir al inicio
        if ($this->authService->estaAutenticado()) {
            $this->redirect('/contactos');
            return;
        }

        $this->renderHTML(VIEWS_DIR . '/auth/registro_view.php', [
            'titulo' => 'Registro de Usuario'
        ]);
    }

    /**
     * Procesar formulario de registro
     */
    public function registroPostAction(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/auth/registro');
            return;
        }

        $usuario = $_POST['usuario'] ?? '';
        $password = $_POST['password'] ?? '';
        $password_confirmar = $_POST['password_confirmar'] ?? '';
        $nombre = $_POST['nombre'] ?? '';
        $email = $_POST['email'] ?? '';

        $errors = [];

        // Validaciones
        if (empty($usuario)) {
            $errors['usuario'] = 'El nombre de usuario es obligatorio';
        } elseif (strlen($usuario) < 3) {
            $errors['usuario'] = 'El usuario debe tener al menos 3 caracteres';
        }

        if (empty($nombre)) {
            $errors['nombre'] = 'El nombre es obligatorio';
        }

        if (empty($email)) {
            $errors['email'] = 'El email es obligatorio';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'El email no es válido';
        }

        if (empty($password)) {
            $errors['password'] = 'La contraseña es obligatoria';
        } elseif (strlen($password) < 6) {
            $errors['password'] = 'La contraseña debe tener al menos 6 caracteres';
        }

        if ($password !== $password_confirmar) {
            $errors['password_confirmar'] = 'Las contraseñas no coinciden';
        }

        // Si hay errores de validación, mostrar formulario con errores
        if (!empty($errors)) {
            $this->renderHTML(VIEWS_DIR . '/auth/registro_view.php', [
                'titulo' => 'Registro de Usuario',
                'errors' => $errors,
                'form' => [
                    'usuario' => htmlspecialchars($usuario, ENT_QUOTES, 'UTF-8'),
                    'nombre' => htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8'),
                    'email' => htmlspecialchars($email, ENT_QUOTES, 'UTF-8')
                ]
            ]);
            return;
        }

        // Intentar registrar
        $resultado = $this->authService->registrarUsuario([
            'usuario' => $usuario,
            'password' => $password,
            'nombre' => $nombre,
            'email' => $email
        ]);

        if (!$resultado['exito']) {
            $this->renderHTML(VIEWS_DIR . '/auth/registro_view.php', [
                'titulo' => 'Registro de Usuario',
                'error' => $resultado['mensaje'],
                'form' => [
                    'usuario' => htmlspecialchars($usuario, ENT_QUOTES, 'UTF-8'),
                    'nombre' => htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8'),
                    'email' => htmlspecialchars($email, ENT_QUOTES, 'UTF-8')
                ]
            ]);
            return;
        }

        // Registro exitoso - redirigir al login
        $this->redirect('/auth/login?success=registered');
    }

    /**
     * Cerrar sesión
     */
    public function logoutAction(): void
    {
        $this->authService->cerrarSesion();
        $this->redirect('/?success=logout');
    }
}
