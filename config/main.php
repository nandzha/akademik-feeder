<?php

return [

    'defaultController' => 'Home',

    // Just put null value if you has enable .htaccess file
    'indexFile' => null,

    'module' => [
        'path' => APP,
        'domainMapping' => [],
    ],

    'vendor' => [
        'path' => 'vendor/'
    ],

    /*'alias' => [
        'controller' => [
            'class' => 'Home',
            'method' => 'index'
        ),
        'method' => 'alias'
    ),*/

    'alias' => [
        'controller' => [
            'class' => 'Alias',
            'method' => 'index'
            ]
    ],
];
