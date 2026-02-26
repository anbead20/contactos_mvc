#!/bin/bash

# Script para crear la estructura de directorios del proyecto MVC
# Agenda de Contactos
# Sigue el estándar PSR-4 para namespaces
# Compatible con: macOS, Linux/Ubuntu, Windows (Git Bash/WSL)

echo "[INFO] Creando estructura de directorios..."

# Directorio base del proyecto
BASE_DIR="$(cd "$(dirname "$0")" && pwd)"
cd "$BASE_DIR"

# Crear estructura de directorios (funciona en todos los sistemas)
mkdir -p app/{config,Controllers,Core,Forms,helpers,Middleware,Models,Services}
mkdir -p cache
mkdir -p logs
mkdir -p public/{assets/{css,img,js},test,uploads/contactos}
mkdir -p tests
mkdir -p views/{contactos/partials,errors,helpers,includes,index,layouts}

echo "[SUCCESS] Estructura de directorios creada correctamente"
echo ""

# Crear archivo .env.example si no existe
if [ ! -f ".env.example" ]; then
    echo "[INFO] Creando archivo .env.example..."
    cat > .env.example << 'EOF'
# Configuración del entorno
APP_ENV=development
APP_DEBUG=true

# Configuración de la base de datos
DB_HOST=localhost
DB_NAME=
DB_USER=
DB_PASS=
DB_CHARSET=utf8mb4
EOF
    echo "[SUCCESS] Archivo .env.example creado"
else
    echo "[SUCCESS] Archivo .env.example ya existe"
fi

# Crear archivo .env si no existe
if [ ! -f ".env" ]; then
    echo "[INFO] Creando archivo .env desde .env.example..."
    cp .env.example .env
    echo "[SUCCESS] Archivo .env creado"
    echo "[WARNING] Recuerda configurar las variables de .env con tus datos reales"
else
    echo "[SUCCESS] Archivo .env ya existe"
fi

echo ""

# Crear archivo .htaccess en public/ si no existe
if [ ! -f "public/.htaccess" ]; then
    echo "[INFO] Creando archivo .htaccess en public/..."
    cat > public/.htaccess << 'EOF'
# Configuración del .htaccess para nuestro proyecto MVC
#
# Activamos el motor de reescritura de Apache.
# Sin esto, nada de lo siguiente funcionaría.
# Básicamente le decimos al servidor "oye, voy a cambiar cómo se ven las URLs"
RewriteEngine On

# Evitamos que la gente pueda ver el listado de archivos de nuestras carpetas.
# Si no ponemos esto y alguien entra a /assets, vería TODOS los archivos.
# Mal asunto.
Options -Indexes

# Aquí le decimos: "solo reescribe la URL si lo que piden NO es un archivo real"
RewriteCond %{REQUEST_FILENAME} !-f
# Y también: "tampoco lo hagas si es una carpeta que existe de verdad"
RewriteCond %{REQUEST_FILENAME} !-d

# Redirección limpia al Front Controller
# Todo lo que no sea un archivo o carpeta real, que vaya a index.php
# [L] significa "última regla, no sigas buscando"
# [QSA] mantiene los parámetros GET
RewriteRule ^ index.php [L,QSA]
EOF
    echo "[SUCCESS] Archivo .htaccess creado en public/"
else
    echo "[SUCCESS] Archivo .htaccess ya existe en public/"
fi

echo ""

# Establecer permisos de escritura en carpetas necesarias
echo "[INFO] Configurando permisos de escritura..."
chmod -R 775 cache logs public/uploads 2>/dev/null || true
echo "[SUCCESS] Permisos configurados"

echo ""
echo "Estructura generada:"
echo "├── app/"
echo "│   ├── config/"
echo "│   ├── Controllers/"
echo "│   ├── Core/"
echo "│   ├── Forms/"
echo "│   ├── helpers/"
echo "│   ├── Middleware/"
echo "│   ├── Models/"
echo "│   └── Services/"
echo "├── cache/"
echo "├── logs/"
echo "├── public/"
echo "│   ├── assets/"
echo "│   │   ├── css/"
echo "│   │   ├── img/"
echo "│   │   └── js/"
echo "│   ├── test/"
echo "│   └── uploads/"
echo "│       └── contactos/"
echo "├── tests/"
echo "└── views/"
echo "    ├── contactos/"
echo "    │   └── partials/"
echo "    ├── errors/"
echo "    ├── helpers/"
echo "    ├── includes/"
echo "    ├── index/"
echo "    └── layouts/"
echo ""
echo "El proyecto está listo para comenzar el desarrollo."
