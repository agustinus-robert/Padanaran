<?php

return [

    'name' => 'Autentikasi - ' . config('app.name'),

    'signup' => [

        /**
         * Set sign up availability
         */
        'enabled' => false,
    ],

    'signin' => [

        /**
         * Default route name for sign in
         */
        'route' => 'auth::signin'

    ],

    'signout' => [

        /**
         * Default route name for sign out and clearing session
         */
        'route' => 'auth::signout'

    ],

    'empower' => [

        /**
         * Set sign up availability
         */
        'enabled' => true,
    ],

];
