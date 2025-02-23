<?php

return [
    'defaults' => [
        'guard' => 'api',  // Cambiado 'web' a 'api' para usar JWT por defecto
        'passwords' => 'usuarios',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'usuarios',
        ],

        'api' => [
            'driver' => 'jwt',  // Usar el driver JWT para autenticación de API
            'provider' => 'usuarios',
            'hash' => false,  // No es necesario si usas JWT
        ],
    ],

    'providers' => [
        'usuarios' => [
            'driver' => 'eloquent',
            'model' => App\Models\Usuario::class,
        ],
    ],

    'passwords' => [
        'usuarios' => [
            'provider' => 'usuarios',
            'table' => 'password_resets',
            'expire' => 60, 
            'throttle' => 60,  
        ],
    ],

    'password_timeout' => 10800,  
];
