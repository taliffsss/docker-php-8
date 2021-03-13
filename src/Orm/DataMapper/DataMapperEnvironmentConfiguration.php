<?php
	
declare(strict_type=1);

namespace Simple\Orm\DataMapper;

use Simple\Orm\DataMapper\Exception\DataMapperInvalidArgumentException;

class DataMapperEnvironmentConfiguration
{
    
    /**
     * @var array
     */
    private array $credentials = [];

    /**
     * Initialize main constructor
     *
     * @param array $credential
     * @return void
     */
    public function __construct(array $credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * Get database credential in environment file
     *
     * @param string $driver
     * @return array
     */
    public function getDatabaseCredentials(string $driver): array 
    {
    	$connection = [];

    	foreach ($this->credentials as $credential) {
    		if (array_key_exists($driver, $credential)) {
    			$connection = $credential[$driver];
    		}
    	}

    	return $connection;
    }

    /**
     * Check if database credential is valid
     *
     * @param string $driver
     * @return void
     */
    private function isCredentialValid(string $driver)
    {
    	if (empty($driver) || !is_string($driver)) {
    		throw new DataMapperInvalidArgumentException('Invalid argument database credentials');
    	}

    	if (!is_array($this->credentials)) {
    		throw new DataMapperInvalidArgumentException('Invalid argument database credentials must be array');
    	}

    	if (!in_array($driver, array_keys($this->credentials))) {
    		throw new DataMapperInvalidArgumentException('Database driver not supported');
    	}
    }
}
?>