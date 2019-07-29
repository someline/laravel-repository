<?php

return [


    /*
    |--------------------------------------------------------------------------
    | Repository Pagination Limit Default
    |--------------------------------------------------------------------------
    |
    */
    'pagination' => [
        'limit' => 15
    ],

    /*
    |--------------------------------------------------------------------------
    | Situation Config
    |--------------------------------------------------------------------------
    |
    | Settings of request parameters names that will be used by Situation
    |
    */
    'situation' => [
        'request_params' => [
            'search' => 'search',
            'searchFields' => 'searchFields',
            'filter' => 'filter',
            'orderBy' => 'orderBy',
            'sortedBy' => 'sortedBy',
            'with' => 'with'
        ]
    ],
    /*
    |--------------------------------------------------------------------------
    | Generator Config
    |--------------------------------------------------------------------------
    |
    */
    'generator' => [
        'basePath' => app_path(),
        'rootNamespace' => 'App\\',
        'paths' => [
            'models' => 'Models',
            'repositories' => 'Repositories',
            'transformers' => 'Transformers',
            'controllers' => 'Api/Controllers',
            'provider' => 'RepositoryServiceProvider',
        ]
    ]

];
