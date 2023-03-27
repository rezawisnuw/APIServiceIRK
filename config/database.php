<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DATABASE_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => 'prefer',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            // 'encrypt' => env('DB_ENCRYPT', 'yes'),
            // 'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'false'),
        ],

        'sqlsrv93_dev' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_HOST_SQLSRV93_DEV', '101.234.116.253'), //public
            //'host' => env('DB_HOST_SQLSRV93_DEV', '172.31.2.93'), //local
            'port' => env('DB_PORT_SQLSRV93_DEV', '11433'), //public
            //'port' => env('DB_PORT_SQLSRV93_DEV', '1433'), //local
            'database' => env('DB_DATABASE_SQLSRV93_DEV', 'DBMOBILE_DEV'),
            'username' => env('DB_USERNAME_SQLSRV93_DEV', 'sa'),
            'password' => env('DB_PASSWORD_SQLSRV93_DEV', 'J063r5sd2'),
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],

        'sqlsrv93_stag' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_HOST_SQLSRV93_STAG', '101.234.116.253'), //public
            //'host' => env('DB_HOST_SQLSRV93_STAG', '172.31.2.93'), //local
            'port' => env('DB_PORT_SQLSRV93_STAG', '11433'), //public
            //'port' => env('DB_PORT_SQLSRV93_STAG', '1433'), //local
            'database' => env('DB_DATABASE_SQLSRV93_STAG', 'DBMOBILE_STAG'),
            'username' => env('DB_USERNAME_SQLSRV93_STAG', 'sa'),
            'password' => env('DB_PASSWORD_SQLSRV93_STAG', 'J063r5sd2'),
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],

        'sqlsrv93' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_HOST_SQLSRV93', '101.234.116.253'), //public
            //'host' => env('DB_HOST_SQLSRV93', '172.31.2.93'), //local
            'port' => env('DB_PORT_SQLSRV93', '11433'), //public
            //'port' => env('DB_PORT_SQLSRV93', '1433'), //local
            'database' => env('DB_DATABASE_SQLSRV93', 'DBMOBILE'),
            'username' => env('DB_USERNAME_SQLSRV93', 'sa'),
            'password' => env('DB_PASSWORD_SQLSRV93', 'J063r5sd2'),
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],

        'pgsqllocal' =>[
            'driver' => 'pgsql',
            'host' => env('DB_HOST_PGSQLLOCAL', '127.0.0.1'),
            'port' => env('DB_PORT_PGSQLLOCAL', '5432'),
            'database' => env('DB_DATABASE_PGSQLLOCAL', 'DB_IRK'),
            'username' => env('DB_USERNAME_PGSQLLOCAL', 'postgres'),
            'password' => env('DB_PASSWORD_PGSQLLOCAL', '1q2w3e4r'),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'pgsqlgcp_irk' =>[
            'driver' => 'pgsql',
            'host' => env('DB_HOST_PGSQLGCP_IRK', '34.128.72.151'),
            'port' => env('DB_PORT_PGSQLGCP_IRK', '5432'),
            'database' => env('DB_DATABASE_PGSQLGCP_IRK', 'irk'),
            'username' => env('DB_USERNAME_PGSQLGCP_IRK', 'usersd2'),
            'password' => env('DB_PASSWORD_PGSQLGCP_IRK', '5D2k0c4x'),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],

    ],

];
