<?php

use Illuminate\Support\Str;

return [

    /*
    |----------------------------------------------------------------------
    | Default Session Driver
    |----------------------------------------------------------------------
    |
    | Este opción determina el driver de sesión por defecto que se utilizará
    | para las solicitudes entrantes. Laravel soporta varias opciones de
    | almacenamiento para persistir los datos de sesión.
    |
    | Opciones soportadas: "file", "cookie", "database", "apc", "memcached",
    | "redis", "dynamodb", "array"
    |
    */

    'driver' => env('SESSION_DRIVER', 'database'),

    /*
    |----------------------------------------------------------------------
    | Session Lifetime
    |----------------------------------------------------------------------
    |
    | Aquí puedes especificar la cantidad de minutos que deseas que dure
    | la sesión antes de que expire. Si deseas que expire inmediatamente
    | cuando se cierre el navegador, puedes indicar que expire en el
    | archivo .env con la opción expire_on_close.
    |
    */

    'lifetime' => (int) env('SESSION_LIFETIME', 120),

    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),

    /*
    |----------------------------------------------------------------------
    | Session Encryption
    |----------------------------------------------------------------------
    |
    | Esta opción permite que todos los datos de sesión sean encriptados
    | antes de ser almacenados. Laravel se encarga de la encriptación de
    | forma automática.
    |
    */

    'encrypt' => env('SESSION_ENCRYPT', false),

    /*
    |----------------------------------------------------------------------
    | Session File Location
    |----------------------------------------------------------------------
    |
    | Si utilizas el driver "file", los archivos de sesión se colocarán en
    | el disco. Aquí se define la ubicación por defecto, pero puedes
    | proporcionar otra ubicación si lo deseas.
    |
    */

    'files' => storage_path('framework/sessions'),

    /*
    |----------------------------------------------------------------------
    | Session Database Connection
    |----------------------------------------------------------------------
    |
    | Si usas los drivers "database" o "redis" para almacenar las sesiones,
    | puedes especificar una conexión que debe ser utilizada para manejar
    | estas sesiones. Debe corresponder con una conexión en las opciones
    | de configuración de la base de datos.
    |
    */

    'connection' => env('SESSION_CONNECTION', 'mysql'),

    /*
    |----------------------------------------------------------------------
    | Session Database Table
    |----------------------------------------------------------------------
    |
    | Cuando se utiliza el driver "database" para las sesiones, puedes
    | especificar la tabla que se debe usar para almacenar las sesiones.
    | Si no especificas una, se usará la tabla por defecto 'sessions'.
    |
    */

    'table' => env('SESSION_TABLE', 'sessions'),

    /*
    |----------------------------------------------------------------------
    | Session Cache Store
    |----------------------------------------------------------------------
    |
    | Si usas un backend de caché para manejar las sesiones, puedes
    | especificar el store de caché que debe utilizarse. Debe coincidir
    | con una de las tiendas de caché definidas en la configuración.
    |
    | Afecta: "apc", "dynamodb", "memcached", "redis"
    |
    */

    'store' => env('SESSION_STORE'),

    /*
    |----------------------------------------------------------------------
    | Session Sweeping Lottery
    |----------------------------------------------------------------------
    |
    | Algunos drivers de sesión necesitan barrer manualmente su ubicación
    | de almacenamiento para eliminar sesiones antiguas. Aquí se define
    | la probabilidad de que esto ocurra en cada solicitud.
    |
    | El valor por defecto es 2 de cada 100 solicitudes.
    |
    */

    'lottery' => [2, 100],

    /*
    |----------------------------------------------------------------------
    | Session Cookie Name
    |----------------------------------------------------------------------
    |
    | Aquí puedes cambiar el nombre de la cookie de sesión que Laravel
    | crea. Generalmente no necesitas cambiar esto, ya que no mejora
    | la seguridad.
    |
    */

    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug(env('APP_NAME', 'laravel'), '_').'_session'
    ),

    /*
    |----------------------------------------------------------------------
    | Session Cookie Path
    |----------------------------------------------------------------------
    |
    | La ruta del cookie de sesión determina para qué ruta estará
    | disponible la cookie. Por lo general, esto será el path raíz
    | de tu aplicación, pero puedes cambiarlo si lo necesitas.
    |
    */

    'path' => env('SESSION_PATH', '/'),

    /*
    |----------------------------------------------------------------------
    | Session Cookie Domain
    |----------------------------------------------------------------------
    |
    | Esta opción define el dominio y subdominios a los que la cookie
    | de sesión estará disponible. Por defecto, la cookie estará
    | disponible para el dominio raíz y todos los subdominios.
    |
    */

    'domain' => env('SESSION_DOMAIN'),

    /*
    |----------------------------------------------------------------------
    | HTTPS Only Cookies
    |----------------------------------------------------------------------
    |
    | Al configurar esto a 'true', las cookies de sesión solo se enviarán
    | al servidor si la conexión es por HTTPS. Esto garantiza que la
    | cookie no sea enviada de forma insegura.
    |
    */

    'secure' => env('SESSION_SECURE_COOKIE', false),

    /*
    |----------------------------------------------------------------------
    | HTTP Access Only
    |----------------------------------------------------------------------
    |
    | Configurando esto a 'true', la cookie solo será accesible a través
    | del protocolo HTTP, evitando que sea accesible por JavaScript.
    | Esto ayuda a proteger contra ataques XSS.
    |
    */

    'http_only' => env('SESSION_HTTP_ONLY', true),

    /*
    |----------------------------------------------------------------------
    | Same-Site Cookies
    |----------------------------------------------------------------------
    |
    | Esta opción controla el comportamiento de las cookies en solicitudes
    | de terceros, lo que puede ayudar a mitigar ataques CSRF.
    | El valor por defecto es 'lax', lo que permite solicitudes seguras
    | entre sitios.
    |
    | Soportado: "lax", "strict", "none", null
    |
    */

    'same_site' => env('SESSION_SAME_SITE', 'lax'),

    /*
    |----------------------------------------------------------------------
    | Partitioned Cookies
    |----------------------------------------------------------------------
    |
    | Configurando esto a 'true', la cookie estará atada al sitio de nivel
    | superior en un contexto de terceros. Aceptado por navegadores cuando
    | la cookie está marcada como "secure" y "SameSite" es "none".
    |
    */

    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),

];
