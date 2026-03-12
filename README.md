### [📚 Agenda de Contactos. MVC](https://github.com/fjagui/practica_contactos_mvc) {target=blank}

Esta práctica consiste en el desarrollo de una aplicación básica de gestión de contactos utilizando una arquitectura Modelo-Vista-Controlador (MVC) profesional en PHP. A lo largo de varios hitos, construiremos desde la infraestructura base hasta un sistema completo con validación, servicios y persistencia en base de datos.

Descarga el repositorio y copia los archivos necesarios para el desarrollo de cada hito.
La entrega y documentación se realizará a través del repositorio de GitHub.

### 🚩 Hito 0: Infraestructura y servidor.

### **Objetivo.**

Establecer la arquitectura física del proyecto y configurar el entorno de ejecución.

### Tareas.

1. **Creación de la estructura de directorios:** Presta atención al uso de las mayúscula sengún el [estándar PSR para _namespaces_](https://www.php-fig.org/psr/psr-4/){target=blank}:

```text
.
├── app
│   ├── config
│   ├── Controllers
│   ├── Core
│   ├── Forms
│   ├── helpers
│   ├── Middleware
│   ├── Models
│   └── Services
├── cache
├── logs
├── public
│   ├── assets
│   │   ├── css
│   │   ├── img
│   │   └── js
│   ├── test
│   └── uploads
│       └── contactos
├── tests
└── views
    ├── contactos
    │   └── partials
    ├── errors
    ├── helpers
    ├── includes
    ├── index
    └── layouts

```

2. **Configuración de un virtual host para el proyecto**.
   - Crea un archivo de configuración de un virtual host para la aplicación.
3. **Implementación de .htaccess:**
   - Copia el archivo entregado `.htaccess` dentro de la carpeta `/public`.
   - Completa los comentarios del archivo.
4. **Control de Versiones:**
   - Inicia el repositorio.
   - Crea el archivo .gitignore.
   - Realiza el primer commit: `Hito 0: Estructura de carpetas y configuración del servidor.`
   - Actualiza repositorio remoto.

### 🤔 Incluye en la documentación.

- **Seguridad:** ¿Por qué configuramos el `DocumentRoot` en `/public` y no en la raíz del proyecto donde están las carpetas `app` o `config`?

  La configuración del `DocumentRoot` en `/public` establece una barrera de seguridad esencial: únicamente los archivos contenidos en esta carpeta son accesibles directamente desde el navegador. De esta manera, los directorios que contienen código fuente, configuraciones sensibles (como `app/`, `config/`) y el archivo `.env` con credenciales permanecen inaccesibles desde peticiones HTTP externas. Si configuráramos el DocumentRoot en la raíz del proyecto, un atacante podría acceder directamente a rutas como `http://tudominio.com/app/config/config.php` y exponer información crítica del sistema.

- **Git:** ¿Por qué es una mala práctica subir la carpeta `vendor/` o el archivo `.env` al repositorio de GitHub?

  La carpeta `vendor/` contiene miles de archivos de dependencias externas que pueden regenerarse automáticamente ejecutando `composer install`, lo que hace innecesario versionar estos archivos y evita inflar el repositorio con código redundante. Por otro lado, el archivo `.env` almacena credenciales y datos sensibles del entorno específico (contraseñas de base de datos, API keys). Incluirlo en el repositorio expondría esta información confidencial a cualquier persona con acceso al código, representando un grave riesgo de seguridad.

- **Organización:** ¿Qué diferencia esperas encontrar entre los archivos guardados en `app/Controllers` y los guardados en `views/`?

  Los archivos en `app/Controllers` contienen la lógica de control de la aplicación: procesan las peticiones HTTP, interactúan con los modelos y servicios, y deciden qué respuesta enviar al usuario. Por su parte, los archivos en `views/` contienen únicamente las plantillas de presentación (HTML con PHP mínimo) responsables de mostrar la información al usuario final. Esta separación es un pilar fundamental del patrón MVC, facilitando el mantenimiento y permitiendo que diseñadores trabajen en las vistas mientras los desarrolladores se centran en la lógica de negocio.

### 🚩 Hito 1: Dependencias y variables de entorno.

### Objetivo.

Configurar el gestor de dependencias **Composer**, estableciendo el sistema de autocargado de clases bajo el estándar **PSR-4** y prepararando el entorno para manejar datos sensibles de forma segura, mediante variables de entorno y herramientas de depuración profesional.

### Tareas.

1. **Autocarga de clases**
   - Copia el archivo `composer.json` descargado en el directorio raiz del proyecto.
2. **Instalación de librerías.** Utiliza composer para instalar:
   - [`vlucas/phpdotenv`](https://packagist.org/packages/vlucas/phpdotenv?query=whoops){target=blanck} para la gestión de las variables de entorno.
   - [`filp/whoops`](https://packagist.org/packages/filp/whoops){target=blank} para la gestión de errores.
3. **Gestión de variables de entorno y seguridad:**
   - **Crear `.env`:** Crea este archivo en la raíz del proyecto con la definición de las variables de acceso a la base de datos(`DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`).
   - **Crear `.env.example`:** Crea una copia llamada `.env.example` pero **vacía de valores reales**. Este archivo servirá de plantilla para otros desarrolladores.
4. **Git**
   - Realiza el commit del hito.
   - Actualiza el repositorio remoto.
   - Verifica el repositorio remoto.

### 🤔 Incluye en la documentación.

- **Seguridad:** Hemos creado un `.env` y un `.env.example`. ¿Por qué es necesario que el `.env.example` **sí** esté en Git y el `.env` **no**?

  El archivo `.env.example` sirve como plantilla de configuración que documenta qué variables de entorno requiere la aplicación, pero sin contener valores reales. Su inclusión en el repositorio permite que otros desarrolladores identifiquen rápidamente qué configuraciones necesitan establecer en su entorno local. En contraste, el archivo `.env` contiene las credenciales reales del entorno (contraseñas, tokens, etc.) y debe permanecer exclusivamente en el sistema local, nunca en el control de versiones, para evitar exposición de información sensible.

- **Verificación:** Si al ejecutar `git status` ves el archivo `.env` en la lista de archivos para agregar, ¿qué significa y qué desastre podrías causar si haces `git push`?

  Esto indica que el archivo `.env` no está correctamente excluido en el `.gitignore`, lo cual representa un error crítico de seguridad. Si se realiza un `git push` en esta situación, las credenciales quedarán registradas permanentemente en el historial del repositorio. Incluso si posteriormente se elimina el archivo, las credenciales seguirán siendo accesibles en commits anteriores, comprometiendo la seguridad del sistema y requiriendo la rotación inmediata de todas las credenciales expuestas.

- **Autoloading:** Gracias al PSR-4, ¿qué ventaja tenemos ahora a la hora de crear nuevas clases en `app/Controllers` respecto al uso tradicional de `require_once`?

  El estándar PSR-4 implementado por Composer elimina la necesidad de incluir manualmente cada archivo mediante `require_once`. El autoloader mapea automáticamente los namespaces a la estructura de directorios, cargando las clases bajo demanda cuando se referencian. Esto reduce significativamente el código boilerplate, minimiza errores de rutas, y mejora el mantenimiento al eliminar la gestión manual de dependencias entre archivos.

- **Dependencias:** ¿Para qué sirve el archivo `composer.lock` que se ha generado automáticamente? ¿Debería estar incluido en nuestro `.gitignore`?

  El archivo `composer.lock` registra las versiones exactas de todas las dependencias instaladas, incluyendo sus sub-dependencias. Esto garantiza que todos los entornos de desarrollo, testing y producción utilicen idénticas versiones de las librerías, eliminando inconsistencias del tipo "funciona en mi máquina". Este archivo **debe incluirse en el control de versiones** y nunca en `.gitignore`, ya que es esencial para reproducir el entorno de dependencias de forma determinista.

### 🚩 Hito 2: El arranque. Bootstrap y configuración.

### **Objetivo.**

Configurar la lógica de arranque y la configuración de los datos necesarios para el funcionamiento de la aplicación.

### **Tareas.**

1. **Configuración.**
   - Copia en el directorio de configuración el archivo descargado.
2. **El arranque**.
   - Copia en el proyecto el fichero bootstrap.php.
   - Realiza las tareas incluidas como comentarios en el archivo bootstrap.php.
   - Diseña una pequeña prueba para ver el funcionamiento de la librería de depuración. Cambia entre los modos de desarrollo para ver el resultado.
3. **Git**
   - Realiza el commit del hito.
   - Actualiza el repositorio remoto.
   - Verifica el repositorio remoto.

### 🤔 Incluye en la documentación.\*\*

- **Separación de responsabilidades:** ¿Por qué crees que es mejor que las rutas como `VIEWS_DIR` estén en un archivo `config.php` en lugar de estar mezcladas con la lógica de inicialización del `bootstrap.php`?

  Esta separación responde al principio de separación de responsabilidades: `bootstrap.php` se encarga exclusivamente de la inicialización de la aplicación (autoloading, manejo de errores, carga de variables de entorno), mientras que `config.php` centraliza los valores de configuración del sistema. Esta organización facilita el mantenimiento, ya que los cambios en la configuración se realizan en un único archivo claramente identificable, sin necesidad de modificar la lógica de arranque de la aplicación.

- **Entorno de errores:** ¿Qué peligro tendría dejar la librería **Whoops** activada cuando el `APP_ENV` sea igual a `production`?

  La librería Whoops proporciona información de depuración extremadamente detallada: stack traces completos, fragmentos de código fuente, rutas absolutas del sistema de archivos, y valores de variables. Si bien esta información es invaluable durante el desarrollo, en producción representa un riesgo de seguridad crítico, ya que expone la arquitectura interna de la aplicación y potenciales vectores de ataque. En entornos de producción debe mostrarse únicamente un mensaje de error genérico, registrando los detalles técnicos en logs internos para diagnóstico posterior.

- **Automatización:** El bootstrap crea carpetas automáticamente. ¿Cómo ayuda esto a otro desarrollador que descargue tu proyecto por primera vez desde GitHub?

  La creación automática de directorios necesarios (`/logs`, `/cache`, `/uploads`) durante el proceso de bootstrap mejora significativamente la experiencia de configuración inicial. Un nuevo desarrollador puede clonar el repositorio y ejecutar la aplicación inmediatamente sin necesidad de realizar configuración manual de la estructura de directorios, evitando errores comunes de "directorio no encontrado" y reduciendo la barrera de entrada al proyecto.

- **Variables Críticas:** En el bloque `try-catch` del `Dotenv`, se usa el método `required()`. ¿Qué ocurre si borras la variable `DBNAME` de tu archivo `.env` e intentas arrancar la app?

  El método `required()` implementa una validación de configuración en tiempo de inicialización: si falta una variable crítica, la aplicación falla inmediatamente con una excepción clara y descriptiva ("Variable DBNAME requerida no encontrada"). Este enfoque de "fail-fast" es preferible a permitir que la aplicación arranque parcialmente y genere errores crípticos más adelante cuando intente utilizar la variable inexistente, facilitando la identificación y corrección rápida de problemas de configuración.

### 🚩 Hito 3: El Front Controller y el enrutamiento.

### Objetivo.

Implementar el **punto de entrada único** de la aplicación y definir el sistema de rutas. El Front Controller intercepta la URL, la compara con las rutas permitidas y delega la ejecución al controlador correspondiente mediante el componente `Dispatcher`.

### **Tareas.**

1. **Implementación del archivo:**
   - Copia el `front controller` en su correspondiente directorio en el proyecto.
   - Revisa el código entragado y realiza las tareas comentadas en el fichero.
2. **Análisis de las Rutas:**
   - Observa el bloque de "Definición de Rutas".
   - Identifica qué métodos HTTP (`GET` o `POST`) se están utilizando y a qué método de qué controlador apunta la ruta `/contactos/crear`.

3. **Prueba de error (Whoops en acción):**
   - Intenta acceder a una ruta que **no esté definida** en el archivo.
   - Comprueba que el sistema falla controladamente. Gracias a que en el Hito 2 configuraste **Whoops**, deberías ver una traza detallada del error indicando que el Router no encontró la ruta o que el Dispatcher no pudo ejecutarla.

4. **Git**
   - Realiza el commit del hito.
   - Actualiza el repositorio remoto.
   - Verifica el repositorio remoto.

### 🚩 Hito 4: El Núcleo. Router y Dispatcher.

### Objetivo.

Implementar la lógica interna que permite reconocer una URL y ejecutar el código correspondiente.

### Tareas.

1. **Lógica del Enrutador:**
   - Implementa el algoritmo de búsqueda dentro del método `match`. La tarea está comentada en el archivo.
2. **Lógica de Ejecución** .
   - Elabora un diagrama de flujo o secuencia que represente el camino de una petición a través de los distintos componentes software.
3. **Verificación con Whoops:**
   - Utiliza Whoops para depurar los errores de los ficheros del núcleo.
4. **Git**
   - Realiza el commit del hito.
   - Actualiza el repositorio remoto.
   - Verifica el repositorio remoto.

### 🤔 Incluir en la documentación.\*\*

- **Responsabilidades:** ¿Por qué dividimos el trabajo en dos clases? ¿Qué pasaría si el `Router` también se encargara de instanciar los controladores?

  Esta división responde al principio de responsabilidad única (Single Responsibility Principle): el `Router` se especializa en analizar la petición y determinar qué controlador debe manejarla, mientras que el `Dispatcher` se encarga exclusivamente de instanciar y ejecutar dicho controlador. Esta separación mejora la mantenibilidad y testabilidad del código, permitiendo modificar la lógica de enrutamiento sin afectar la ejecución de controladores, y viceversa. Si ambas responsabilidades estuvieran acopladas, cualquier cambio en una afectaría inevitablemente a la otra.

- **Dinamicidad:** El `Dispatcher` usa variables para crear objetos (`new $controller()`). ¿Qué ventaja tiene esto frente a usar un `switch` gigante con todos los controladores del proyecto?

  La instanciación dinámica mediante variables permite que el sistema sea completamente extensible sin modificar el código del `Dispatcher`. Agregar un nuevo controlador requiere únicamente crear la clase correspondiente y definir su ruta, sin necesidad de actualizar un `switch` con decenas o cientos de casos. Esta aproximación respeta el principio Open/Closed (abierto para extensión, cerrado para modificación) y facilita significativamente el escalamiento del proyecto.

- **Limpieza de URL:** Si el usuario entra en `/contactos/crear?origen=web`, ¿por qué es vital que el Router ignore la parte de `?origen=web` para encontrar la ruta?

  Los parámetros de query string (`?origen=web`) son datos adicionales de la petición, no forman parte del patrón de la ruta en sí. El Router debe trabajar exclusivamente con el path (`/contactos/crear`) para realizar el matching contra las rutas definidas, mientras que los parámetros GET permanecen disponibles en `$_GET` para su uso posterior por el controlador. Si el Router incluyera los parámetros en el matching, nunca encontraría coincidencias, ya que las rutas se definen sin considerar los valores variables que pueden enviarse en cada petición.

### 🚩 Hito 5: Controladores renderizado de vistas.

### Objetivo.

Implementar la lógica de control de la aplicación, gestionando las peticiones de usuario y utilizando los servicios de datos para devolver una respuesta visual procesando plantillas HTML.

### Tareas.

1. **Archivos**
   - Copiar al proyecto los archivos necesarios.
2. **El Motor de Vistas (`BaseController.php`):**
   - Completar las tareas incluidas en los comentarios de los archivos.
   - Estudia con detenimiento em método `renderHTML` para entender el proceso de renderizado y la necesidad de uso de los buffers de salida.
3. **Controladores de la aplicación**
   - Completa el controlador de inicio.
   - Completa el contralador responsable de los contactos.
4. **Git**
   - Realiza el commit del hito.
   - Actualiza el repositorio remoto.
   - Verifica el repositorio remoto.

### 🤔 Incluir en la documentación.

- **Herencia:** ¿Por qué es útil que todos los controladores hereden de `BaseController`? ¿Qué código nos estamos ahorrando repetir en `IndexController` y `ContactoController`?

  La herencia de `BaseController` centraliza funcionalidades comunes a todos los controladores (renderizado de vistas, redirecciones, manejo de parámetros) en una única clase base. Esto evita duplicar código en cada controlador específico, aplicando el principio DRY (Don't Repeat Yourself). Además, cualquier mejora o corrección implementada en `BaseController` se propaga automáticamente a todos los controladores que heredan de ella, facilitando el mantenimiento y evolución del código.

- **Buffers de salida:** ¿Para qué sirve `ob_start()`? ¿Qué pasaría si hiciéramos un `include` de la vista directamente sin usar el buffer?

  La función `ob_start()` activa el buffering de salida, capturando todo el contenido que normalmente se enviaría al navegador y almacenándolo temporalmente en memoria. Posteriormente, `ob_get_clean()` recupera este contenido como string y limpia el buffer. Este mecanismo es esencial para el sistema de layouts: permite renderizar una vista parcial, capturar su HTML resultante, e inyectarlo posteriormente en una plantilla base. Sin buffering, el contenido se enviaría directamente al cliente, imposibilitando su composición dentro de layouts.

- **Seguridad en POST:** ¿Por qué en métodos como `storeAction` o `updateAction` comprobamos obligatoriamente que el método de la petición sea `POST`?

  Esta validación implementa una capa de seguridad fundamental: las operaciones que modifican el estado del sistema (crear, actualizar, eliminar) deben utilizar exclusivamente métodos HTTP no idempotentes como POST. Permitir estas operaciones mediante GET expondría la aplicación a ataques CSRF (Cross-Site Request Forgery), donde un enlace malicioso podría ejecutar acciones destructivas simplemente al ser visitado. Además, previene modificaciones accidentales causadas por crawlers, prefetching de navegadores, o enlaces compartidos inadvertidamente.

- **Limpieza de datos:** El controlador usa un método llamado `sanitizeForOutput`. ¿Por qué no debemos mostrar directamente en el HTML lo que el usuario escribió en un formulario?

  Mostrar datos de usuario sin sanitización abre la aplicación a ataques de Cross-Site Scripting (XSS). Un atacante podría inyectar código JavaScript malicioso (como `<script>alert('XSS')</script>`) que se ejecutaría en el navegador de otros usuarios, permitiendo robo de sesiones, captura de credenciales, o manipulación del DOM. La función `htmlspecialchars()` convierte caracteres especiales HTML en sus entidades correspondientes, asegurando que el contenido se muestre como texto literal sin interpretación como código ejecutable.

### 🚩 Hito 6: Modelo de datos y servicios.

### Objetivo.

Implementar el acceso a datos mediante el patrón de **Modelos** y centralizar la lógica de negocio en **Servicios**.

### Tareas.

1. **Abstracción de Base de Datos**
   - Revisa la implementación del patrón **Singleton** para asegurar que solo exista una conexión activa.
   - Actualiza el archivo con las tareas comentadas.
2. **Excepciones personalisadas.**
   - Revisa y completa la excepción personalizada para los errores de bases de datos.
3. **Modelo contactos.**
   - Completa las tareas comentadas en el archivo.
   - Corrige los errores detectados.
   - Fuerza la generación de errores para ver el funcionamiento del sistema de logs.

4. **Servicios.**
   - Completa las tareas comentadas en el archivo.
   - Corrige los errores detectados.
   - Fuerza la generación de errores para ver el funcionamiento del sistema de logs.

5. **Git**
   - Realiza el commit del hito.
   - Actualiza el repositorio remoto.
   - Verifica el repositorio remoto.

### 🤔 Incluir en la documentación.\*\*

- **Seguridad (PDO):** ¿Por qué debemos usar `$stmt->prepare()` y pasar los parámetros en un array en lugar de concatenar las variables directamente en el string de la consulta?

  La concatenación directa de variables en consultas SQL crea vulnerabilidades críticas de SQL Injection, permitiendo que un atacante manipule la consulta insertando código SQL malicioso (por ejemplo, `' OR '1'='1`). Las prepared statements de PDO separan la estructura de la consulta de los datos, tratando los parámetros como valores literales que PDO escapa automáticamente. Esto elimina por completo la posibilidad de inyección SQL, además de mejorar el rendimiento mediante la reutilización de consultas compiladas.

- **Excepciones:** En `ContactoModel`, cuando ocurre un error, llamamos a `$error->logError()`. ¿Dónde podemos consulta ese log para saber qué ha fallado exactamente?

  Los logs de errores se almacenan en el directorio `/logs/` del proyecto. Estos archivos registran información detallada de cada error: timestamp, tipo de excepción, mensaje descriptivo, stack trace, y contexto relevante. Este sistema de logging permite diagnosticar problemas en producción sin exponer información sensible a los usuarios finales, manteniendo un registro histórico de incidencias para análisis y mejora continua del sistema.

- **Mapeo:** ¿Qué ventaja tiene que el `ContactoService` limpie y formatee los datos antes de enviarlos al controlador?

  La capa de servicios centraliza la lógica de negocio y transformación de datos, encapsulando operaciones como formateo de fechas, cálculos derivados, o normalización de valores. Los controladores reciben datos ya procesados y listos para presentación, liberándolos de responsabilidades de transformación. Esta arquitectura previene duplicación de lógica entre múltiples controladores, facilita el testing unitario de la lógica de negocio de forma aislada, y permite reutilizar transformaciones complejas en diferentes contextos de la aplicación.

- **Patrón Singleton:** ¿Qué pasaría con los recursos del servidor si cada vez que un modelo necesita una consulta creara una nueva conexión `new PDO()`?

  Crear una nueva conexión PDO para cada consulta consumiría recursos del servidor exponencialmente. Cada conexión implica overhead de red, autenticación, y asignación de recursos en el servidor de base de datos. En una aplicación real que ejecute múltiples consultas por petición, esto podría rápidamente exceder los límites del servidor ("Too many connections"), degradar el rendimiento drásticamente, y en casos extremos, causar denegación de servicio. El patrón Singleton asegura una única instancia de conexión reutilizada eficientemente durante todo el ciclo de vida de la petición.

### 🚩 Hito 7: Validación y sanitización de formularios.

### Objetivo.

Asegurar la integridad y seguridad de los datos que entran en la aplicación, implementando un sistema de validación que filtre los caracteres no deseados y verifique que los datos (nombre, email y teléfono) cumplen con los requisitos de negocio antes de ser procesados por el servicio.

### Tareas.

1. **Gestor de formularios.**
   - Revisa las clases que componen el gestor de formularios.
   - Completa las tareas comentadas en los archivos.
   - Corrige los errores detectados.

2. **Git**
   - Realiza el commit del hito.
   - Actualiza el repositorio remoto.
   - Verifica el repositorio remoto.

### 🤔 Incluir en la documentación.

- **Sanitización vs Validación:** ¿Cuál es la diferencia? ¿Por qué es necesario limpiar los datos (`Sanitizer`) antes de comprobar si son válidos (`Validator`)?

  La **sanitización** transforma y normaliza los datos de entrada (elimina espacios, tags HTML, caracteres especiales), preparándolos para su procesamiento. La **validación** verifica que los datos cumplan con las reglas de negocio establecidas sin modificarlos. El orden es crucial: primero se sanitiza para trabajar con datos normalizados (por ejemplo, eliminando espacios en blanco que podrían hacer fallar una validación de longitud), y posteriormente se valida para asegurar que cumplen los requisitos específicos (formato de email, longitud de teléfono, etc.).

- **XSS (Cross-Site Scripting):** ¿Qué ocurriría si no usáramos `htmlspecialchars` al mostrar los datos que el usuario escribió mal en el formulario?

  Al repoblar formularios con datos de entrada sin escapar, se crea una vulnerabilidad de XSS reflejado. Un atacante podría inyectar código JavaScript malicioso que se ejecutaría en el contexto del navegador de la víctima, permitiendo robo de cookies de sesión, captura de pulsaciones de teclas, redirección a sitios phishing, o modificación del contenido visible. La función `htmlspecialchars()` convierte caracteres especiales HTML (`<`, `>`, `&`, `"`, `'`) en sus entidades HTML correspondientes, asegurando que el contenido se renderice como texto literal sin interpretación como código.

- **Experiencia de Usuario:** ¿Por qué es importante devolver los datos originales al formulario cuando hay un error (repoblar el formulario) en lugar de dejar los campos vacíos?

  La repoblación de formularios mejora significativamente la experiencia de usuario al preservar los datos correctamente introducidos cuando ocurre un error de validación. Obligar al usuario a reintroducir toda la información por un error en un único campo genera frustración y aumenta la probabilidad de abandono del proceso. Devolver los datos junto con mensajes de error específicos y contextuales permite al usuario corregir únicamente los campos problemáticos, manteniendo el flujo de trabajo fluido y reduciendo la fricción en la interacción.

- **Responsabilidad:** ¿Por qué crees que es mejor tener la validación en clases separadas en lugar de escribir todos los `if` directamente dentro del Controlador?

  Extraer la lógica de validación a clases especializadas (`Validator`, `Sanitizer`) responde al principio de responsabilidad única: los controladores deben orquestar el flujo de la aplicación, no contener lógica de validación compleja. Esta separación proporciona múltiples beneficios: código reutilizable en diferentes contextos, facilidad para realizar testing unitario de las reglas de validación de forma aislada, y controladores más legibles y mantenibles. Además, permite modificar o extender las reglas de validación sin tocar la lógica de control.

### 🚩 Hito 8: Sistema de vistas, layouts y componentes.

### Objetivo.

Implementar la interfaz de usuario de la aplicación organizando las vistas de forma jerárquica, utilizando un **Layout Base** común y componentes reutilizables (partials) para mantener un diseño consistente y fácil de mantener.

### Tareas.

1. **Vistas:**
   - Utiliza los archivos descargados como base para el diseño de una interfaz personalizada.
   - Añade algún helper de vista a modo de ejemplo.

2. **Git**
   - Realiza el commit del hito.
   - Actualiza el repositorio remoto.
   - Verifica el repositorio remoto.

### 🤔 Incluir en la documentación\*\*

- **DRY (Don't Repeat Yourself):** ¿Qué ventaja tiene haber separado el `nav_view.php` del resto de las páginas si mañana decidimos cambiar el color de la barra de navegación?

  La separación de componentes reutilizables como la navegación en archivos independientes aplica el principio DRY, eliminando duplicación de código. Cualquier modificación en el diseño, estructura o funcionalidad de la navegación se realiza en un único archivo (`nav_view.php`), propagándose automáticamente a todas las páginas que lo incluyen. Esto reduce drásticamente el esfuerzo de mantenimiento, minimiza la posibilidad de inconsistencias, y facilita la evolución del diseño sin necesidad de actualizar manualmente decenas de archivos.

- **Seguridad en la Vista:** En los archivos entregados se usa `htmlspecialchars()`. ¿Por qué es obligatorio usarlo al imprimir variables como el nombre o el email del contacto?

  Las vistas renderan datos almacenados en la base de datos que originalmente fueron proporcionados por usuarios, potencialmente maliciosos. Si estos datos contienen código HTML o JavaScript y se muestran sin escapar, se ejecutarían en el navegador del usuario (ataque XSS almacenado o persistente). Aunque existan validaciones en la entrada, `htmlspecialchars()` en la capa de presentación actúa como defensa en profundidad, asegurando que cualquier contenido potencialmente peligroso se muestre como texto literal, protegiendo contra vectores de ataque que pudieran evadir las validaciones previas.

- **Inyección de contenido:** ¿Cómo sabe el archivo `base_view.php` qué contenido debe mostrar en la variable `$content`? (Relaciónalo con el Hito 5 y el Buffer de salida).

  El sistema utiliza output buffering para implementar el patrón de composición de vistas: 1) El controlador invoca `ob_start()` para iniciar la captura de salida, 2) Se incluye la vista específica (`include('contactos/listar_view.php')`), cuyo HTML se captura en el buffer en lugar de enviarse al navegador, 3) `ob_get_clean()` recupera el contenido capturado como string y lo asigna a `$content`, limpiando el buffer, 4) Finalmente se incluye el layout base (`base_view.php`) que recibe la variable `$content` e la inyecta en la posición apropiada de la estructura HTML. Este mecanismo permite una arquitectura de layouts jerárquica y reutilizable.

- **Interatividad:** Observa cómo se gestionan los mensajes de éxito (`success=created`). ¿Cómo ayudamos al usuario a saber que su acción ha funcionado sin que tenga que revisar la base de datos?

  El sistema implementa feedback inmediato mediante el patrón Post-Redirect-Get (PRG): tras una operación exitosa, el controlador realiza una redirección incluyendo un parámetro en la query string (`?success=created`). La vista de destino detecta este parámetro y renderiza un mensaje de confirmación apropiado. Este mecanismo proporciona retroalimentación clara e inmediata al usuario sobre el resultado de su acción, mejorando la usabilidad y confianza en el sistema. Además, la redirección previene el problema de reenvío de formularios al actualizar la página.
