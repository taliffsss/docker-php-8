<?php

declare(strict_type=1);

namespace Simple\Orm\DataMapper;

use Simple\Database\DatabaseInterface;
use Simple\Orm\DataMapper\Exception\DataMapperException;
use PDOStatement;
use Throwable;
use PDO;

/**
 * summary
 */
class DataMapper implements DataMapperInterface
{

	/**
	 * @var DatabaseInterface
	 */
    private DatabaseInterface $dbh;

    /**
	 * @var DatabaseInterface
	 */
    private PDOStatement $stmt;

    public function __construct(DatabaseInterface $db)
    {
    	$this->dbh = $db;
    }

    /**
     * Check the incoming $valis isn't empty else throw an exception
     * 
     * @param mixed $value
     * @param string|null $errorMessage
     * @return void
     * @throws DataMapperException
     */
    private function isEmpty($value, string $errorMessage = null)
    {
    	if (empty($value)) {
    		throw new DataMapperException($errorMessage);
    	}
    }

    private function isArray(array $value)
    {
    	if (!is_array($value)) {
    		throw new DataMapperException('Your argument needs to be an array');
    	}
    }

    /**
     * Prepares a statement for execution and returns a statement object
     * 
     * @param string $sqlQuery
     * @return self
     */
    public function prepare(string $sqlQuery): self
    {
    	$this->stmt = $this->dbh->open()->prepare($sqlQuery);

    	return $this;
    }

    /**
     * the variable is bound as a reference and will only be evaluated at the time that PDOStatement::execute()
     * @param $value is the actual value that we want to bind to the placeholder
     * @param $type is the datatype of the parameter
     */
    public function bind($value)
    {
    	try {
    		
			switch (true) {
				case is_int($value):
					$dataType = PDO::PARAM_INT;
					break;
				case is_bool($value):
					$dataType = PDO::PARAM_BOOL;
					break;
				case is_null($value):
					$dataType = PDO::PARAM_NULL;
					break;
				default:
					$dataType = PDO::PARAM_STR;
					break;
			}

			return $dataType;

    	} catch (DataMapperException $e) {
    		throw $e;
    	}
    }

    /**
     * Binds a parameter to the specified variable name
     * 
     * @param array $fields
     * @param bool $isSearch
     * @return self
     */
    public function bindParameters((array $fileds, bool $isSearch = false): self
    {
    	if (is_array($fileds)) {
    		$type = ($isSearch === false) ? $this->bindValue($fields) : $this->bindSearchValues($fields);

    		if ($type) {
    			return $this;
    		}
    	}

    	return false;
    }

    /**
     * Binds a value to a parameter
     * 
     * @param array $fields
     * @return void
     */
    protected function bindValue(array $fields)
    {
    	$this->isArray($fields);

    	foreach ($fields as $key => $value) {
    		$this->stmt->bindValue(':' . $key, $value, $this->bind($value));
    	}

    	return $this->stmt;
    }

    /**
     *
     */
    protected function bindSearchValues(array $fields)
    {
    	$this->isArray($fields);

    	foreach ($fields as $key => $value) {
    		$this->stmt->bindValue(':' . $key, '%' . $value . '%', $this->bind($value));
    	}

    	return $this->stmt;
    }

    /**
     * The execute method executes the prepared statement.
     * 
     * @return bool
     */
    public function execute(): bool
    {
    	
    	if ($this->stmt) return $this->stmt->execute();
    }

    /**
     *
     */
    public function numRows(): int
    {
    	
    	if ($this->stmt) return $this->stmt->rowCount();
    }

    /**
     * Returns a single database row as an object
     * 
     * @return Object
     */
    public function result(): object
    {
    	
    	if ($this->stmt) return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Returns all the rows within the database as an array
     * 
     * @return array
     */
    public function results(): array
    {
    	
    	if ($this->stmt) return $this->stmt->fetchAll();
    }

    /**
     * Transactions allows you to run multiple changes to a database
     *
     * @return bool
     */
    public function startTransaction(): bool
    {
    	if ($this->stmt) return $this->stmt->beginTransaction();
    }

    /**
     * End a transaction and commit your changes
     *
     * @return bool
     */
    public function startTransaction(): bool
    {
    	if ($this->stmt) return $this->stmt->commit();
    }

    /**
     * Cancel a transaction and roll back your changes
     *
     * @return bool
     */
    public function cancelTransaction(): bool
    {
    	if ($this->stmt) return $this->stmt->rollBack();
    }

    /**
     * returns an array of PDO driver names.
     *
     * @return array
     */
    public function getDatabaseDrivers(): array
    {
    	return PDO::getAvailableDrivers();
    }

    /**
     * Returns a single column from the next row of a result set
     * 
     * @return mixed
     */
    public function column()
    {
        if ($this->stmt) return $this->stmt->fetchColumn();
    }

    /**
     * Returns a single column from the next row of a result set
     *
     * @return string
     */
    public function dump()
    {
        if ($this->stmt) return $this->stmt->debugDumpParams();
    }

    /**
     * Returns a single column from the next row of a result set
     *
     * @param string $rawSql
     * @return mixed
     */
    public function rawQuery(string $rawSql)
    {
        return $this->stmt->query($rawSql);
    }

    /**
     * returns the last inserted Id as a string
     */
    public function getLastId(): int
    {
    	try {
    		if ($this->dbh->open()) {
    			$lastInsertId = $this->dbh->open()->lastInsertId();

    			if (!empty($lastInsertId)) {
    				return intval($lastInsertId);
    			}
    		}
    	} catch (Throwable $e) {
    		throw $e;
    	}
    }
}
?>