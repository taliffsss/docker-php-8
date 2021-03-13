<?php

declare(strict_type=1);

namespace Simple\Orm\DataMapper;


class DataMapperFactory
{
    /**
     * Initialize main constructor
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    public function create(string $databaseConnectionString, string $dataMapperEnvironmentConfiguration): DataMapperInterface
    {
    	$credentials = (new $dataMapperEnvironmentConfiguration([]))->getDatabaseCredentials();

    	$databaseConnectionObject = new $databaseConnectionString($credentials);

    	if (!$databaseConnectionObject instanceof DatabaseInterface) {
    		throw new DataMapperException($databaseConnectionString . ' invalid database connection');
    	}

    	return new DataMapper($databaseConnectionObject);
    }
}
?>