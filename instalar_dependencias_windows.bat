@echo off
REM Script para instalar dependencias del proyecto con Composer
REM Compatible con Windows CMD/PowerShell

echo [INFO] Verificando PHP...

REM Verificar si PHP esta instalado
where php >nul 2>nul
if %errorlevel% neq 0 (
    echo [ERROR] PHP no esta instalado.
    echo [INFO] Por favor, instala PHP manualmente desde:
    echo        https://windows.php.net/download/
    echo.
    echo [INFO] Recomendaciones:
    echo        1. Descarga PHP 8.x Thread Safe ZIP
    echo        2. Extrae en C:\php
    echo        3. Agrega C:\php a la variable PATH del sistema
    echo        4. Reinicia la terminal y ejecuta este script nuevamente
    pause
    exit /b 1
) else (
    for /f "tokens=*" %%i in ('php -v 2^>^&1 ^| findstr /R "^PHP"') do set PHP_VERSION=%%i
    echo [SUCCESS] PHP ya esta instalado
    echo            !PHP_VERSION!
)

echo.
echo [INFO] Verificando Composer...

REM Verificar si Composer esta instalado
where composer >nul 2>nul
if %errorlevel% neq 0 (
    echo [WARNING] Composer no esta instalado.
    echo [INFO] Instalando Composer...
    echo.
    
    REM Descargar el instalador de Composer
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    
    if not exist composer-setup.php (
        echo [ERROR] No se pudo descargar el instalador de Composer
        pause
        exit /b 1
    )
    
    REM Instalar Composer
    php composer-setup.php --quiet
    del composer-setup.php
    
    if exist composer.phar (
        echo [SUCCESS] Composer instalado localmente
        set COMPOSER_CMD=php composer.phar
    ) else (
        echo [ERROR] Hubo un problema al instalar Composer
        pause
        exit /b 1
    )
) else (
    echo [SUCCESS] Composer ya esta instalado
    set COMPOSER_CMD=composer
)

echo.

REM Crear composer.json si no existe
if not exist "composer.json" (
    echo [INFO] Creando composer.json con configuracion PSR-4...
    (
        echo {
        echo     "name": "contactos/app",
        echo     "description": "Aplicacion de contactos",
        echo     "type": "project",
        echo     "require": {
        echo         "php": "^>=7.4",
        echo         "vlucas/phpdotenv": "^^5.6",
        echo         "filp/whoops": "^^2.18"
        echo     },
        echo     "autoload": {
        echo         "psr-4": {
        echo             "App\\": "app/"
        echo         }
        echo     },
        echo     "require-dev": {},
        echo     "config": {
        echo         "optimize-autoloader": true
        echo     }
        echo }
    ) > composer.json
    echo [SUCCESS] composer.json creado correctamente
) else (
    echo [SUCCESS] composer.json ya existe
)

echo.

REM Verificar si necesita instalar dependencias
if not exist "vendor" (
    set NEED_INSTALL=true
) else if not exist "composer.lock" (
    set NEED_INSTALL=true
) else (
    set NEED_INSTALL=false
    echo [SUCCESS] Las dependencias ya estan instaladas.
)

if "%NEED_INSTALL%"=="true" (
    echo [INFO] Instalando dependencias con Composer...
    echo.
    
    %COMPOSER_CMD% install
    
    if %errorlevel% equ 0 (
        echo.
        echo [SUCCESS] Dependencias instaladas correctamente
        echo [SUCCESS] Se ha generado la carpeta vendor\ y el archivo composer.lock
        echo.
        echo [INFO] Regenerando autoloader con configuracion PSR-4...
        %COMPOSER_CMD% dump-autoload --optimize
        if %errorlevel% equ 0 (
            echo [SUCCESS] Autoloader regenerado correctamente
        )
    ) else (
        echo.
        echo [ERROR] Hubo un problema al instalar las dependencias
        pause
        exit /b 1
    )
)

echo.
echo El proyecto esta listo para el desarrollo.
echo.
pause
