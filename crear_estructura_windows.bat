@echo off
REM Script para crear la estructura de directorios del proyecto MVC
REM Agenda de Contactos
REM Compatible con Windows CMD/PowerShell

echo [INFO] Creando estructura de directorios...

REM Crear estructura de directorios
mkdir app\config 2>nul
mkdir app\Controllers 2>nul
mkdir app\Core 2>nul
mkdir app\Forms 2>nul
mkdir app\helpers 2>nul
mkdir app\Middleware 2>nul
mkdir app\Models 2>nul
mkdir app\Services 2>nul
mkdir cache 2>nul
mkdir logs 2>nul
mkdir public\assets\css 2>nul
mkdir public\assets\img 2>nul
mkdir public\assets\js 2>nul
mkdir public\test 2>nul
mkdir public\uploads\contactos 2>nul
mkdir tests 2>nul
mkdir views\contactos\partials 2>nul
mkdir views\errors 2>nul
mkdir views\helpers 2>nul
mkdir views\includes 2>nul
mkdir views\index 2>nul
mkdir views\layouts 2>nul

echo [SUCCESS] Estructura de directorios creada correctamente
echo.

REM Crear archivo .env.example si no existe
if not exist ".env.example" (
    echo [INFO] Creando archivo .env.example...
    (
        echo # Configuracion del entorno
        echo APP_ENV=development
        echo APP_DEBUG=true
        echo.
        echo # Configuracion de la base de datos
        echo DB_HOST=localhost
        echo DB_NAME=
        echo DB_USER=
        echo DB_PASS=
        echo DB_CHARSET=utf8mb4
    ) > .env.example
    echo [SUCCESS] Archivo .env.example creado
) else (
    echo [SUCCESS] Archivo .env.example ya existe
)

REM Crear archivo .env si no existe
if not exist ".env" (
    echo [INFO] Creando archivo .env desde .env.example...
    copy .env.example .env >nul
    echo [SUCCESS] Archivo .env creado
    echo [WARNING] Recuerda configurar las variables de .env con tus datos reales
) else (
    echo [SUCCESS] Archivo .env ya existe
)

echo.

REM Crear archivo .htaccess en public/ si no existe
if not exist "public\.htaccess" (
    echo [INFO] Creando archivo .htaccess en public/...
    (
        echo # Configuracion del .htaccess para nuestro proyecto MVC
        echo.
        echo # Activamos el motor de reescritura de Apache. Sin esto, nada de lo siguiente funcionaria.
        echo # Basicamente le decimos al servidor "oye, voy a cambiar como se ven las URLs"
        echo RewriteEngine On
        echo.
        echo # Evitamos que la gente pueda ver el listado de archivos de nuestras carpetas.
        echo # Si no ponemos esto y alguien entra a /assets, veria TODOS los archivos. Mal asunto.
        echo Options -Indexes
        echo.
        echo # Aqui le decimos: "solo reescribe la URL si lo que piden NO es un archivo real"
        echo RewriteCond %%{REQUEST_FILENAME} !-f
        echo # Y tambien: "tampoco lo hagas si es una carpeta que existe de verdad"
        echo RewriteCond %%{REQUEST_FILENAME} !-d
        echo.
        echo # Redireccion limpia al Front Controller
        echo # Todo lo que no sea un archivo o carpeta real, que vaya a index.php
        echo # [L] significa "ultima regla, no sigas buscando" y [QSA] mantiene los parametros GET
        echo RewriteRule ^^ index.php [L,QSA]
    ) > public\.htaccess
    echo [SUCCESS] Archivo .htaccess creado en public/
) else (
    echo [SUCCESS] Archivo .htaccess ya existe en public/
)

echo.

REM Crear archivo .htaccess en public/ si no existe
if not exist "public\.htaccess" (
    echo [INFO] Creando archivo .htaccess en public/...
    (
        echo # Configuracion del .htaccess para nuestro proyecto MVC
        echo #
        echo # Activamos el motor de reescritura de Apache.
        echo # Sin esto, nada de lo siguiente funcionaria.
        echo # Basicamente le decimos al servidor "oye, voy a cambiar como se ven las URLs"
        echo RewriteEngine On
        echo.
        echo # Evitamos que la gente pueda ver el listado de archivos de nuestras carpetas.
        echo # Si no ponemos esto y alguien entra a /assets, veria TODOS los archivos.
        echo # Mal asunto.
        echo Options -Indexes
        echo.
        echo # Aqui le decimos: "solo reescribe la URL si lo que piden NO es un archivo real"
        echo RewriteCond %%{REQUEST_FILENAME} !-f
        echo # Y tambien: "tampoco lo hagas si es una carpeta que existe de verdad"
        echo RewriteCond %%{REQUEST_FILENAME} !-d
        echo.
        echo # Redireccion limpia al Front Controller
        echo # Todo lo que no sea un archivo o carpeta real, que vaya a index.php
        echo # [L] significa "ultima regla, no sigas buscando"
        echo # [QSA] mantiene los parametros GET
        echo RewriteRule ^^ index.php [L,QSA]
    ) > public\.htaccess
    echo [SUCCESS] Archivo .htaccess creado en public/
) else (
    echo [SUCCESS] Archivo .htaccess ya existe en public/
)

echo.
echo El proyecto esta listo para comenzar el desarrollo.
echo.
pause
