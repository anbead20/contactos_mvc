<?php
/*****************************************************
 * UserModel - Modelo de gestión de usuarios
 * 
 * Gestiona todas las operaciones de base de datos 
 * relacionadas con usuarios y autenticación.
*/

namespace App\Models;

class UserModel extends DBAbstractModel 
{
    private $id;
    private $usuario;
    private $password;
    private $nombre;
    private $email;
    private $created_at;
    private $updated_at;

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setUsuario($usuario) { $this->usuario = $usuario; }
    public function setPassword($password) { $this->password = $password; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setEmail($email) { $this->email = $email; }

    // Getters
    public function getId() { return $this->id; }
    public function getUsuario() { return $this->usuario; }
    public function getPassword() { return $this->password; }
    public function getNombre() { return $this->nombre; }
    public function getEmail() { return $this->email; }
    public function getCreatedAt() { return $this->created_at; }
    public function getUpdatedAt() { return $this->updated_at; }

    /**
     * Obtener un usuario por ID
     */
    public function get($id = '') 
    {
        try {
            $this->query = "SELECT * FROM usuarios WHERE id = :id";
            $this->parametros['id'] = $id;
            $this->get_results_from_query();
            
            if (count($this->rows) === 1) {
                $row = $this->rows[0];
                $this->setId($row['id']);
                $this->setUsuario($row['usuario']);
                $this->setPassword($row['password']);
                $this->setNombre($row['nombre']);
                $this->setEmail($row['email']);
                $this->created_at = $row['created_at'] ?? null;
                $this->updated_at = $row['updated_at'] ?? null;
                
                $this->mensaje = 'Usuario encontrado';
                return $row;
            } 
            
            $this->mensaje = 'Usuario no encontrado';
            return null;
            
        } catch (\Exception $e) {
            $error = new DatabaseException("Error en BD: " . $e->getMessage());
            $error->logError();
            throw $error;
        }
    }

    /**
     * Buscar usuario por nombre de usuario
     */
    public function getByUsername($usuario) 
    {
        try {
            $this->query = "SELECT * FROM usuarios WHERE usuario = :usuario";
            $this->parametros['usuario'] = $usuario;
            $result = $this->get_single_result();
            
            if ($result) {
                $this->setId($result['id']);
                $this->setUsuario($result['usuario']);
                $this->setPassword($result['password']);
                $this->setNombre($result['nombre']);
                $this->setEmail($result['email']);
                $this->created_at = $result['created_at'] ?? null;
                $this->updated_at = $result['updated_at'] ?? null;
                
                $this->mensaje = 'Usuario encontrado';
                return $result;
            }
            
            $this->mensaje = 'Usuario no encontrado';
            return null;
            
        } catch (\Exception $e) {
            $error = new DatabaseException("Error en BD: " . $e->getMessage());
            $error->logError();
            throw $error;
        }
    }

    /**
     * Buscar usuario por email
     */
    public function getByEmail($email) 
    {
        try {
            $this->query = "SELECT * FROM usuarios WHERE email = :email";
            $this->parametros['email'] = $email;
            $result = $this->get_single_result();
            
            if ($result) {
                $this->mensaje = 'Usuario encontrado';
                return $result;
            }
            
            $this->mensaje = 'Usuario no encontrado';
            return null;
            
        } catch (\Exception $e) {
            $error = new DatabaseException("Error en BD: " . $e->getMessage());
            $error->logError();
            throw $error;
        }
    }

    /**
     * Insertar un nuevo usuario
     */
    public function set() 
    {
        try {
            $this->query = "INSERT INTO usuarios (usuario, password, nombre, email) VALUES (:usuario, :password, :nombre, :email)";
            $this->parametros = [
                'usuario' => $this->usuario,
                'password' => $this->password,
                'nombre' => $this->nombre,
                'email' => $this->email
            ];
            
            $this->execute_single_query();
            $this->mensaje = 'Usuario registrado correctamente';
            return true;
            
        } catch (\Exception $e) {
            $error = new DatabaseException("Error en BD: " . $e->getMessage());
            $error->logError();
            throw $error;
        }
    }

    /**
     * Actualizar un usuario existente
     */
    public function edit() 
    {
        try {
            $this->query = "UPDATE usuarios SET usuario = :usuario, nombre = :nombre, email = :email, updated_at = NOW() WHERE id = :id";
            $this->parametros = [
                'id' => $this->id,
                'usuario' => $this->usuario,
                'nombre' => $this->nombre,
                'email' => $this->email
            ];
            
            $this->execute_single_query();
            $this->mensaje = 'Usuario actualizado correctamente';
            return true;
            
        } catch (\Exception $e) {
            $error = new DatabaseException("Error en BD: " . $e->getMessage());
            $error->logError();
            throw $error;
        }
    }

    /**
     * Eliminar un usuario
     */
    public function delete($id = '') 
    {
        try {
            $this->query = "DELETE FROM usuarios WHERE id = :id";
            $this->parametros = ['id' => $id];
            $this->execute_single_query();
            
            if ($this->affected_rows > 0) {
                $this->mensaje = 'Usuario eliminado correctamente';
                return true;
            }
            
            $this->mensaje = 'No se pudo eliminar el usuario';
            return false;
            
        } catch (\Exception $e) {
            $error = new DatabaseException("Error en BD: " . $e->getMessage());
            $error->logError();
            throw $error;
        }
    }

    /**
     * Verificar si existe un usuario con ese nombre de usuario
     */
    public function existeUsuario($usuario) 
    {
        try {
            $this->query = "SELECT COUNT(*) as total FROM usuarios WHERE usuario = :usuario";
            $this->parametros['usuario'] = $usuario;
            $result = $this->get_single_result();
            
            return ($result['total'] ?? 0) > 0;
            
        } catch (\Exception $e) {
            $error = new DatabaseException("Error en BD: " . $e->getMessage());
            $error->logError();
            throw $error;
        }
    }

    /**
     * Verificar si existe un usuario con ese email
     */
    public function existeEmail($email) 
    {
        try {
            $this->query = "SELECT COUNT(*) as total FROM usuarios WHERE email = :email";
            $this->parametros['email'] = $email;
            $result = $this->get_single_result();
            
            return ($result['total'] ?? 0) > 0;
            
        } catch (\Exception $e) {
            $error = new DatabaseException("Error en BD: " . $e->getMessage());
            $error->logError();
            throw $error;
        }
    }
}
