<?php

declare(strict_types=1);

namespace Simple\Orm\DataMapper;

use Simple\Exception\BaseInvalidArgumentException;

class MapperFactory
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
     * $dbConfig get instantiated in the DataRepositoryFactory
     *
     * @param string $dbConnectionString
     * @param Object $dbConfig
     * @return DataMapperInterface
     * @throws Exception
     */
    public function create(string $dbConnectionString): MapperInterface
    {
        

        $config = new MapperCredentialConfiguration($credentials);

        // Create databaseConnection Object and pass the database credentials in
        $credentials = $config->getDatabaseCredentials();

        $dbConnectionObject = new $dbConnectionString($credentials);

        if (!$dbConnectionObject instanceof DatabaseInterface) {
            throw new BaseInvalidArgumentException($dbConnectionString . ' is not a valid database connection object');
        }

        $queryBuilder = new Mapper($dbConnectionObject);

        if (!($queryBuilder instanceof MapperInterface)) {
            throw new BaseInvalidArgumentException($queryBuilder . ' is not a valid database connection object');
        }

        return $queryBuilder;
    }


}