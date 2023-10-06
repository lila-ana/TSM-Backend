<?php

return [

    /*
     * When using the "artisan permission:cache" command, your permissions and roles will
     * be cached to increase the performance of your application. By default, the cache
     * file is stored in the bootstrap/cache directory. However, you may change the
     * directory here.
     */
    'cache' => [
        'store' => 'default',
        'expiration' => null,
    ],

    /*
     * When using the "artisan permission:import" command, new permissions will be
     * created. If the permission already exists in the database, it will be updated
     * with the new data. Here you can specify a class that should be used to import
     * the permissions.
     */
    'importer' => \Spatie\Permission\PermissionRegistrar::class,

    /*
     * By default, permission names are automatically formatted as "viewAny posts"
     * or "create users". Here, you can specify a custom formatter to format the
     * permission names.
     */
    'permission_name_formatter' => null,

    /*
     * By default, permission and role names are cached for one day (24 hours).
     * You may change the cache time duration (in minutes) here.
     */
    'cache_duration_in_minutes' => 1440,

];
