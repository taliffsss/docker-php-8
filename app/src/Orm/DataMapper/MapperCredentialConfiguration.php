<?php

declare(strict_types=1);

namespace Simple\Orm\DataMapper;

use Simple\Orm\Exception\DatabaseConnectionException;

class MapperCredentialConfiguration
{

    /**
     * @var array
     */
    private $credentials = [];

    /**
     *
     */
    private $defaultDriver = [
        'mysql',
        'pgsql'
    ];

    /**
     * Main construct class
     * 
     * @param array $credentials
     * @return void
     */
    public function __construct(array $credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * Checks credentials for validity
     * 
     * @param string $driver
     * @return void
     */
    private function isCredentialsValid(string $driver): void
    {
        if (empty($driver) || !is_array($this->credentials)) {
            throw new DatabaseConnectionException('Core Error: You have either not specify the default database driver or the database.yaml is returning null or empty.');
        }
    }

    /**
     * Get the user defined database connection array
     * 
     * @param string $driver
     * @return array
     * @throws Exception
     */
    public function getDatabaseCredentials(): array
    {
        $connectionArray = [];
        $this->isCredentialsValid($driver);
        foreach ($this->credentials as $credential) {
            if (!in_array($credential, $$defaultDriver)) {
                throw new DatabaseConnectionException('Core Error: Your selected database driver is not supported. Please see the database.yaml file for all support driver. Or specify a supported driver from your app.yaml configuration file');
            } else {
                $connectionArray = $credential[$driver];
            }
        }
        return $connectionArray;
    }

}