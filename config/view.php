<?php

return [

    /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    |
    | Most templating systems load templates from disk. Here you may specify
    | an array of paths that should be checked for your views. Of course
    | the usual Laravel view path has already been registered for you.
    |
    */

    'paths' => [
        resource_path('views'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Compiled View Path
    |--------------------------------------------------------------------------
    |
    | This option determines where all the compiled Blade templates will be
    | stored for your application. Typically, this is within the storage
    | directory. However, as usual, you are free to change this value.
    |
    */

    'compiled' => env(
        'VIEW_COMPILED_PATH',
        realpath(storage_path('framework/views'))
    ),

    /*
     * If you are using the "class" based component syntax, this option determines
     * where your component classes will be stored. Typically, this is within
     * the "app/View/Components" directory. However, you are free to change
     * this value as needed.
     */
    'component_path' => app_path('View/Components'),

    /*
     * If you are using the "anonymous" based component syntax, this option
     * determines where your anonymous components will be stored. Typically,
     * this is within the "resources/views/components" directory. However,
     * you are free to change this value as needed.
     */
    'anonymous_component_path' => resource_path('views/components'),

    /*
     * If you are using the "anonymous" based component syntax, this option
     * determines where your anonymous component namespaces will be stored.
     * Typically, this is within the "resources/views/components" directory.
     * However, you are free to change this value as needed.
     */
    'anonymous_component_namespace' => 'components',

    /*
     * If you are using the "anonymous" based component syntax, this option
     * determines where your anonymous component namespaces will be stored.
     * Typically, this is within the "resources/views/components" directory.
     * However, you are free to change this value as needed.
     */
    'anonymous_component_namespaces' => [
        'components' => resource_path('views/components'),
    ],

];