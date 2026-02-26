#!/bin/bash

# Script para instalar dependencias del proyecto con Composer
# Instala las librerías necesarias para el Hito 1
# Compatible con: macOS, Linux/Ubuntu, Windows (Git Bash/WSL)

# Detectar sistema operativo
OS="unknown"
if [[ "$OSTYPE" == "darwin"* ]]; then
    OS="macos"
elif [[ "$OSTYPE" == "linux-gnu"* ]]; then
    OS="linux"
elif [[ "$OSTYPE" == "msys" ]] || [[ "$OSTYPE" == "cygwin" ]] || [[ "$OSTYPE" == "win32" ]]; then
    OS="windows"
else
    # Detectar WSL (Windows Subsystem for Linux)
    if grep -qi microsoft /proc/version 2>/dev/null; then
        OS="wsl"
    else
        echo "[ERROR] Sistema operativo no soportado: $OSTYPE"
        exit 1
    fi
fi

echo "[INFO] Sistema operativo detectado: $OS"
echo ""
echo "[INFO] Verificando PHP..."

# Verificar si PHP está instalado
if ! command -v php &> /dev/null; then
    echo "[WARNING] PHP no está instalado."
    
    if [ "$OS" == "macos" ]; then
        echo "[INFO] Instalando PHP con Homebrew..."
        
        if ! command -v brew &> /dev/null; then
            echo "[ERROR] Homebrew no está instalado."
            echo "        Instala Homebrew: /bin/bash -c \"\$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)\""
            exit 1
        fi
        
        # Arreglar permisos de Homebrew si es necesario
        if [ -d "/usr/local/lib/pkgconfig" ] && [ ! -w "/usr/local/lib/pkgconfig" ]; then
            echo "[INFO] Corrigiendo permisos de Homebrew..."
            sudo chown -R $(whoami) /usr/local/lib/pkgconfig
            chmod u+w /usr/local/lib/pkgconfig
        fi
        
        brew install php
        
    elif [ "$OS" == "linux" ] || [ "$OS" == "wsl" ]; then
        echo "[INFO] Instalando PHP con apt..."
        sudo apt update
        sudo apt install -y php php-cli php-mbstring php-xml php-zip unzip
        
    elif [ "$OS" == "windows" ]; then
        echo "[ERROR] PHP no está instalado."
        echo "[INFO] Para Windows (Git Bash), instala PHP manualmente:"
        echo "       1. Descarga PHP desde: https://windows.php.net/download/"
        echo "       2. Extrae en C:\\php"
        echo "       3. Agrega C:\\php a la variable PATH del sistema"
        echo "       4. Reinicia Git Bash y ejecuta este script nuevamente"
        exit 1
    fi
    
    if [ $? -eq 0 ]; then
        echo "[SUCCESS] PHP instalado correctamente"
    else
        echo "[ERROR] Hubo un problema al instalar PHP"
        exit 1
    fi
else
    echo "[SUCCESS] PHP ya está instalado ($(php -v | head -n 1))"
fi

echo ""
echo "[INFO] Verificando Composer..."

# Verificar si composer está instalado
if ! command -v composer &> /dev/null; then
    echo "[WARNING] Composer no está instalado."
    echo "[INFO] Instalando Composer..."
    
    # Descargar el instalador de Composer
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    
    # Verificar el instalador
    EXPECTED_CHECKSUM="$(php -r 'copy("https://composer.github.io/installer.sig", "php://stdout");')"
    ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"
    
    if [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ]; then
        echo "[ERROR] Checksum inválido del instalador"
        rm composer-setup.php
        exit 1
    fi
    
    # Instalar Composer
    php composer-setup.php --quiet
    rm composer-setup.php
    
    if [ "$OS" == "windows" ]; then
        # En Windows, dejar composer.phar local
        if [ -f "composer.phar" ]; then
            echo "[SUCCESS] Composer instalado localmente (usa: php composer.phar)"
            alias composer="php composer.phar"
            COMPOSER_CMD="php composer.phar"
        fi
    else
        # En Unix, instalar globalmente
        sudo mv composer.phar /usr/local/bin/composer
        if [ $? -eq 0 ]; then
            echo "[SUCCESS] Composer instalado correctamente"
            COMPOSER_CMD="composer"
        else
            echo "[ERROR] Hubo un problema al instalar Composer"
            exit 1
        fi
    fi
else
    echo "[SUCCESS] Composer ya está instalado"
    COMPOSER_CMD="composer"
fi

echo ""

# Directorio base del proyecto
BASE_DIR="$(cd "$(dirname "$0")" && pwd)"
cd "$BASE_DIR"

# Crear composer.json si no existe o está incompleto
if [ ! -f "composer.json" ]; then
    echo "[INFO] Creando composer.json con configuración PSR-4..."
    cat > composer.json << 'EOF'
{
    "name": "contactos/app",
    "description": "Aplicación de contactos",
    "type": "project",
    "require": {
        "php": ">=7.4",
        "vlucas/phpdotenv": "^5.6",
        "filp/whoops": "^2.18"
    },
    "autoload": {
        "psr-4": {
            "App\\": "app/"
        }
    },
    "require-dev": {},
    "config": {
        "optimize-autoloader": true
    }
}
EOF
    echo "[SUCCESS] composer.json creado correctamente"
else
    # Verificar si tiene la configuración de autoload
    if ! grep -q '"autoload"' composer.json; then
        echo "[INFO] Actualizando composer.json con configuración PSR-4..."
        # Hacer backup del composer.json actual
        cp composer.json composer.json.bak
        
        cat > composer.json << 'EOF'
{
    "name": "contactos/app",
    "description": "Aplicación de contactos",
    "type": "project",
    "require": {
        "php": ">=7.4",
        "vlucas/phpdotenv": "^5.6",
        "filp/whoops": "^2.18"
    },
    "autoload": {
        "psr-4": {
            "App\\": "app/"
        }
    },
    "require-dev": {},
    "config": {
        "optimize-autoloader": true
    }
}
EOF
        echo "[SUCCESS] composer.json actualizado con configuración completa"
    else
        echo "[SUCCESS] composer.json ya existe y está configurado correctamente"
    fi
fi

echo ""

# Verificar si las dependencias ya están instaladas
NEED_INSTALL=false

if [ ! -d "vendor" ]; then
    echo "[INFO] La carpeta vendor/ no existe. Se instalarán las dependencias."
    NEED_INSTALL=true
elif [ ! -f "composer.lock" ]; then
    echo "[INFO] El archivo composer.lock no existe. Se instalarán las dependencias."
    NEED_INSTALL=true
else
    if ! grep -q "vlucas/phpdotenv" composer.json || ! grep -q "filp/whoops" composer.json; then
        echo "[INFO] Faltan dependencias en composer.json. Se instalarán."
        NEED_INSTALL=true
    else
        echo "[SUCCESS] Las dependencias ya están instaladas."
        echo "[INFO] Si deseas reinstalarlas, ejecuta: $COMPOSER_CMD install"
    fi
fi

if [ "$NEED_INSTALL" = true ]; then
    echo ""
    echo "[INFO] Instalando dependencias con Composer..."
    echo ""
    
    $COMPOSER_CMD install
    
    if [ $? -eq 0 ]; then
        echo ""
        echo "[SUCCESS] Dependencias instaladas correctamente"
        echo "[SUCCESS] Se ha generado la carpeta vendor/ y el archivo composer.lock"
        
        # Regenerar autoloader con configuración PSR-4
        echo ""
        echo "[INFO] Regenerando autoloader con configuración PSR-4..."
        $COMPOSER_CMD dump-autoload --optimize
        
        if [ $? -eq 0 ]; then
            echo "[SUCCESS] Autoloader regenerado correctamente"
        fi
    else
        echo ""
        echo "[ERROR] Hubo un problema al instalar las dependencias"
        exit 1
    fi
fi

echo ""
echo "El proyecto está listo para el desarrollo."
