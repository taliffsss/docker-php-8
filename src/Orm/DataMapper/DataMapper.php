<?php

declare(strict_type=1);

namespace Simple\Orm\DataMapper;

use Simple\Database\DatabaseInterface;
use Simple\Orm\DataMapper\Exception\DataMapperException;
use PDOStatement;

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
     *
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
     *
     */
    public function prepare(string $sqlQuery): self
    {
    	$this->stmt = $this->dbh->open()->prepare($sqlQuery);

    	return $this;
    }

    /**
     *
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
     *
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
     *
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
     *
     */
    public function execute(): void
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
     *
     */
    public function result(): object
    {
    	
    	if ($this->stmt) return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     *
     */
    public function results(): array
    {
    	
    	if ($this->stmt) return $this->stmt->fetchAll();
    }
}
?>