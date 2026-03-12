-- ============================================
-- Base de Datos: Agenda de Contactos MVC
-- ============================================
-- Archivo de creación de la base de datos
-- para el proyecto de Gestión de Contactos
-- ============================================

-- Eliminar la base de datos si existe (para desarrollo)
DROP DATABASE IF EXISTS contactos_mvc;

-- Crear la base de datos
CREATE DATABASE contactos_mvc 
    CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;

-- Seleccionar la base de datos
USE contactos_mvc;

-- ============================================
-- Tabla: contactos
-- ============================================
-- Almacena la información de los contactos
-- ============================================

CREATE TABLE contactos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Índices para mejorar las búsquedas
    INDEX idx_nombre (nombre),
    INDEX idx_email (email),
    INDEX idx_telefono (telefono)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla: usuarios
-- ============================================
-- Almacena la información de los usuarios
-- ============================================

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Índices para búsqueda
    INDEX idx_usuario (usuario),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Datos de ejemplo (Opcional)
-- ============================================
-- Descomentar para insertar datos de prueba
-- ============================================

/*
INSERT INTO contactos (nombre, telefono, email) VALUES
('Juan Pérez', '912345678', 'juan.perez@email.com'),
('María García', '923456789', 'maria.garcia@email.com'),
('Carlos López', '934567890', 'carlos.lopez@email.com'),
('Ana Martínez', '945678901', 'ana.martinez@email.com'),
('Luis Rodríguez', '956789012', 'luis.rodriguez@email.com'),
('Elena Fernández', '967890123', 'elena.fernandez@email.com'),
('Pedro Sánchez', '978901234', 'pedro.sanchez@email.com'),
('Laura Gómez', '989012345', 'laura.gomez@email.com'),
('David Díaz', '990123456', 'david.diaz@email.com'),
('Carmen Ruiz', '901234567', 'carmen.ruiz@email.com');
*/

-- ============================================
-- Verificar la creación
-- ============================================

SELECT 'Base de datos creada correctamente' AS mensaje;
SHOW TABLES;
DESCRIBE contactos;
DESCRIBE usuarios;
