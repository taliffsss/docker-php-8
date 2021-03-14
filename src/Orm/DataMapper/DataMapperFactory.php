<?php

declare(strict_types=1);

namespace Simple\Orm\DataMapper;

use Simple\Base\Exception\BaseUnexpectedValueException;
use Simple\DatabaseConnection\DatabaseConnectionInterface;
use Simple\Yaml\YamlConfig;

class DataMapperFactory
{

    /**
     * Main constructor class
     * 
     * @return void
     */
    public function __construct()
    { }

    /**
     * Creates the data mapper object and inject the dependency for this object. We are also
     * creating the DatabaseConnection Object
     * $dataMapperEnvironmentConfiguration get instantiated in the DataRepositoryFactory
     *
     * @param string $databaseConnectionString
     * @param Object $dataMapperEnvironmentConfiguration
     * @return DataMapperInterface
     * @throws BaseUnexpectedValueException
     */
    public function create(string $databaseConnectionString, Object $dataMapperEnvironmentConfiguration) : DataMapperInterface
    {
        // Create databaseConnection Object and pass the database credentials in
        $credentials = $dataMapperEnvironmentConfiguration->getDatabaseCredentials(YamlConfig::file('app')['pdo_driver']);
        $databaseConnectionObject = new $databaseConnectionString($credentials);
        if (!$databaseConnectionObject instanceof DatabaseConnectionInterface) {
            throw new BaseUnexpectedValueException($databaseConnectionString . ' is not a valid database connection object');
        }
        return new DataMapper($databaseConnectionObject);
    }


}