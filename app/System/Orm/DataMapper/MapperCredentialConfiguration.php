<?php

declare(strict_types=1);

namespace Simple\Orm\DataMapper;

use Simple\Orm\Exception\DatabaseConnectionException;
use Simple\Exception\BaseException;

class MapperCredentialConfiguration
{

    /**
     * @var array
     */
    private $credentials = [];

    /**
     * Main construct class
     * 
     * @param array $credentials
     * @return void
     */
    private function __construct()
    {
        $this->$credentials = $this->databaseDriverConfig();
    }

    public function environment()
    {
        if (!file_exists(BASEPATH.'.env')) {
            throw new BaseException('Missing .env file, kindly setup your environement file.');
        }

        $repository = Dotenv\Repository\RepositoryBuilder::createWithNoAdapters()
                    ->addAdapter(Dotenv\Repository\Adapter\EnvConstAdapter::class)
                    ->addWriter(Dotenv\Repository\Adapter\PutenvAdapter::class)
                    ->immutable()
                    ->make();

        $dotenv = Dotenv\Dotenv::create($repository, BASEPATH);
        $dotenv->load();
    }

    /**
     * Checks credentials for validity
     * 
     * @return array
     */
    private function databaseDriverConfig(): array
    {
        $driver = getenv('DB_DRIVER');

        $drivers = [
            'driver' => [
                'mysql' => [
                    'host'      => getenv('DB_HOST'),
                    'host'      => 'mysql:host='.$_SERVER['DB_HOST_POSTGRES'].';port='.$_SERVER['DB_PORT_POSTGRES'].';dbname=' . getenv('DB_NAME') . ';sslmode=require',
                    'dbname'    => getenv('DB_NAME'),
                    'username'  => getenv('DB_USER'),
                    'password'  => getenv('DB_PASSWORD'),
                    'port'      => getenv('DB_PORT') ?? 3306,
                    'ssl'       => ';sslmode=require'
                ],
                'pgsql' => [
                    'host'      => getenv('DB_HOST'),
                    'dsn'       => 'pgsql:host='.$_SERVER['DB_HOST_POSTGRES'].';port='.$_SERVER['DB_PORT_POSTGRES'].';dbname=' . getenv('DB_NAME') . ';',
                    'dbname'    => getenv('DB_NAME'),
                    'username'  => getenv('DB_USER'),
                    'password'  => getenv('DB_PASSWORD'),
                    'port'      => getenv('DB_PORT') ?? 5432,
                    'ssl'       => ';sslmode=require'
                ]
            ]
        ];

        if (!in_array($driver, array_keys($drivers['driver']))) {
            throw new DatabaseConnectionException('Core Error: Your selected database driver is not supported. Please see the .env file for all support driver. Or specify a supported driver from your app.yaml configuration file');
        }

        $k = $drivers['driver'][$driver];

        $ssl = (getenv('DB_SSL')) ? $k['ssl'] : null;

        $credentials = [
            'dns'       => $driver . ':host='. $k['host'] . ';port=' . $k['port'] . ';dbname=' . $k['dbname'] . ';charset=UTF8' . $ssl,
            'username'  => $k['username'],
            'password'  => $k['password']
        ];

        return $credentials;
    }

    /**
     * Get the user defined database connection array
     *
     * @return array
     */
    public function getDatabaseCredentials(): array
    {
        return $this->credentials;
    }

}