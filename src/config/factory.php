<?php

return [
    'nanoid' => [
        'default_prefix' => env('NANOID_DEFAULT_PREFIX', 'T'),
        'allowed_chars'  => env('NANOID_ALLOWED_CHARS', '0123456789ABCDEFGHJKMNPQRSTUVWXYZ'),
    ],
];
