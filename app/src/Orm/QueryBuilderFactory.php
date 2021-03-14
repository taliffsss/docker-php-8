<?php

namespace Simple\Orm;

class QueryBuilderFactory
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
    public function create(string $dbConnectionString): DatabaseQueryBuilderInterface
    {

        $credentials = [
            'driver' => [
                'mysql' => [
                    'dsn'       => 'pgsql:host='.$_SERVER['DB_HOST_POSTGRES'].';port='.$_SERVER['DB_PORT_POSTGRES'].';dbname='.$_SERVER['DB_NAME_POSTGRES'].';sslmode=require',
                    'username'  => $_SERVER['DB_USERNAME_POSTGRES'],
                    'password'  => $_SERVER['DB_PASSWORD_POSTGRES']
                ]
            ]
        ];

        $config = new DatabaseCredentialConfiguration($credentials);

        // Create databaseConnection Object and pass the database credentials in
        $credentials = $config->getDatabaseCredentials('mysql');

        $dbConnectionObject = new $dbConnectionString($credentials);

        if (!$dbConnectionObject instanceof DatabaseInterface) {
            throw new InvalidArgumentException($dbConnectionString . ' is not a valid database connection object');
        }

        $queryBuilder = new DatabaseQueryBuilder($dbConnectionObject);

        if (!($queryBuilder instanceof DatabaseQueryBuilderInterface)) {
            throw new InvalidArgumentException($queryBuilder . ' is not a valid database connection object');
        }

        return $queryBuilder;
    }


}