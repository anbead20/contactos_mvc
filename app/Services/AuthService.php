<?php
/*****************************************************
 * AuthService - Servicio de autenticación
 * 
 * Gestiona toda la lógica de negocio relacionada con
 * autenticación, registro de usuarios y sesiones.
*/

namespace App\Services;

use App\Models\UserModel;
use App\Models\DatabaseException;

class AuthService
{
    private UserModel $userModel;
   
    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Registrar un nuevo usuario en el sistema
     */
    public function registrarUsuario(array $datos): array
    {
        try {
            // Validar que el usuario no exista
            if ($this->userModel->existeUsuario($datos['usuario'])) {
                return [
                    'exito' => false,
                    'mensaje' => 'El nombre de usuario ya está registrado'
                ];
            }

            // Validar que el email no exista
            if ($this->userModel->existeEmail($datos['email'])) {
                return [
                    'exito' => false,
                    'mensaje' => 'El email ya está registrado'
                ];
            }

            // Hash de la contraseña
            $passwordHash = password_hash($datos['password'], PASSWORD_DEFAULT);

            // Establecer datos en el modelo
            $this->userModel->setUsuario($datos['usuario']);
            $this->userModel->setPassword($passwordHash);
            $this->userModel->setNombre($datos['nombre']);
            $this->userModel->setEmail($datos['email']);

            // Guardar en la base de datos
            $resultado = $this->userModel->set();

            if ($resultado) {
                $userId = $this->userModel->getLastInsertId();
                return [
                    'exito' => true,
                    'mensaje' => 'Usuario registrado correctamente',
                    'user_id' => $userId
                ];
            }

            return [
                'exito' => false,
                'mensaje' => 'Error al registrar el usuario'
            ];

        } catch (DatabaseException $e) {
            error_log("Error en AuthService::registrarUsuario: " . $e->getMessage());
            return [
                'exito' => false,
                'mensaje' => 'Error en la base de datos al registrar el usuario'
            ];
        }
    }

    /**
     * Autenticar un usuario (login)
     */
    public function autenticar(string $usuario, string $password): array
    {
        try {
            // Buscar usuario por nombre de usuario
            $userData = $this->userModel->getByUsername($usuario);

            if (!$userData) {
                return [
                    'exito' => false,
                    'mensaje' => 'Usuario o contraseña incorrectos'
                ];
            }

            // Verificar la contraseña
            if (!password_verify($password, $userData['password'])) {
                return [
                    'exito' => false,
                    'mensaje' => 'Usuario o contraseña incorrectos'
                ];
            }

            // Autenticación exitosa - devolver datos del usuario (sin password)
            return [
                'exito' => true,
                'mensaje' => 'Autenticación exitosa',
                'usuario' => [
                    'id' => $userData['id'],
                    'usuario' => $userData['usuario'],
                    'nombre' => $userData['nombre'],
                    'email' => $userData['email']
                ]
            ];

        } catch (DatabaseException $e) {
            error_log("Error en AuthService::autenticar: " . $e->getMessage());
            return [
                'exito' => false,
                'mensaje' => 'Error en la base de datos al autenticar'
            ];
        }
    }

    /**
     * Iniciar sesión para un usuario autenticado
     */
    public function iniciarSesion(array $userData): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['usuario_id'] = $userData['id'];
        $_SESSION['usuario'] = $userData['usuario'];
        $_SESSION['nombre'] = $userData['nombre'];
        $_SESSION['email'] = $userData['email'];
        $_SESSION['autenticado'] = true;

        // Regenerar ID de sesión por seguridad
        session_regenerate_id(true);
    }

    /**
     * Cerrar sesión del usuario actual
     */
    public function cerrarSesion(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Limpiar todas las variables de sesión
        $_SESSION = [];

        // Eliminar la cookie de sesión si existe
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        // Destruir la sesión
        session_destroy();
    }

    /**
     * Verificar si el usuario está autenticado
     */
    public function estaAutenticado(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['autenticado']) && $_SESSION['autenticado'] === true;
    }

    /**
     * Obtener los datos del usuario autenticado
     */
    public function obtenerUsuarioAutenticado(): ?array
    {
        if (!$this->estaAutenticado()) {
            return null;
        }

        return [
            'id' => $_SESSION['usuario_id'] ?? null,
            'usuario' => $_SESSION['usuario'] ?? null,
            'nombre' => $_SESSION['nombre'] ?? null,
            'email' => $_SESSION['email'] ?? null
        ];
    }
}
