<?php 

declare(strict_types=1);

namespace Simple\Orm\DataMapper;

use Simple\Exception\BaseInvalidArgumentException;
use Simple\Exception\BaseNoValueException;
use Simple\Exception\BaseException;
use \PDOStatement;
use \Throwable;
use \PDO;

class Mapper implements MapperInterface
{

    /** @var DatabaseInterface */
    private $dbh;

    /** @var PDOStatement */
    private $stmt;

    /**
     * Main constructor class
     * 
     * @param DatabaseInterface
     * @return void
     */
    public function __construct(DatabaseInterface $dbh)
    {
        $this->dbh = $dbh;
    }

    /**
     * Check the incoming $valis isn't empty else throw an exception
     * 
     * @param mixed $value
     * @param string|null $errorMessage
     * @return void
     * @throws Exception
     */
    private function isEmpty($value, string $errorMessage = null)
    {
        if (empty($value)) {
            throw new BaseNoValueException($errorMessage);
        }
    }

    /**
     * Check the incoming argument $value is an array else throw an exception
     * 
     * @param array $value
     * @return void
     * @throws Exception
     */
    private function isArray(array $value)
    {
        if (!is_array($value)) {
            throw new BaseInvalidArgumentException('Your argument needs to be an array');
        }
    }

    /**
     * Prepare the query string
     * 
     * @param string $sqlQuery
     * @return self
     */
    public function query(string $sqlQuery): PDOStatement
    {
        $this->isEmpty($sqlQuery);
        $this->stmt = $this->dbh->open()->prepare($sqlQuery);
        return $this;
    }

    /**
     * @inheritDoc
     *
     * @param [type] $value
     * @return void
     */
    public function bind($value)
    {
        try {
            switch($value) {
                case is_bool($value) :
                case intval($value) :
                    $dataType = PDO::PARAM_INT;
                    break;
                case is_null($value) :
                    $dataType = PDO::PARAM_NULL;
                    break;
                default :
                    $dataType = PDO::PARAM_STR;
                    break;
            }
            return $dataType;
        } catch(BaseException $exception) {
            throw $exception;
        }
    }

    /**
     * @inheritDoc
     *
     * @param array $fields
     * @param boolean $isSearch
     * @return self
     */
    public function bindParameters(array $fields, bool $isSearch = false)
    {
        $this->isArray($fields);
        if (is_array($fields)) {
            $type = ($isSearch === false) ? $this->bindValues($fields) : $this->bindSearchValues($fields);
            if ($type) {
                return $this;
            }
        }
        return false;
    }

    /**
     * Binds a value to a corresponding name or question mark placeholder in the SQL
     * statement that was used to prepare the statement
     * 
     * @param array $fields
     * @return PDOStatement
     * @throws Exception
     */
    protected function bindValues(array $fields) : PDOStatement
    {
        $this->isArray($fields); // don't need
        foreach ($fields as $key => $value) {
            $this->stmt->bindValue(':' . $key, $value, $this->bind($value));
        }
        return $this->stmt;
    }

    /**
     * Binds a value to a corresponding name or question mark placeholder
     * in the SQL statement that was used to prepare the statement. Similar to
     * above but optimised for search queries
     * 
     * @param array $fields
     * @return mixed
     * @throws Exception
     */
    protected function bindSearchValues(array $fields):  PDOStatement
    {
        $this->isArray($fields); // don't need
        foreach ($fields as $key => $value) {
            $this->stmt->bindValue(':' . $key,  '%' . $value . '%', $this->bind($value));
        }
        return $this->stmt;
    }

    /**
     * @inheritDoc
     *
     * @return bool
     */
    public function execute(): bool
    {
        if ($this->stmt) return $this->stmt->execute();
    }
    /**
     * @inheritDoc
     *
     * @return integer
     */
    public function numRows(): int
    {
        if ($this->stmt) return $this->stmt->rowCount();
    }
    /**
     * @inheritDoc
     *
     * @return Object
     */
    public function result(): Object
    {
        if ($this->stmt) return $this->stmt->fetch(PDO::FETCH_OBJ);
    }
    /**
     * @inheritDoc
     *
     * @return array
     */
    public function results(): array
    {
        if ($this->stmt) return $this->stmt->fetchAll();
    }

    /**
     * @inheritDoc
     */
    public function column()
    {
        if ($this->stmt) return $this->stmt->fetchColumn();
    }

    /**
     * @inheritDoc
     */
    public function column()
    {
        if ($this->stmt) return $this->stmt->fetchColumn();
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
     * Transactions allows you to run multiple changes to a database
     * 
     * @return bool
     */
    public function endTransaction(): bool
    {
        if ($this->stmt) return $this->stmt->commit();
    }

    /**
     * Transactions allows you to run multiple changes to a database
     * 
     * @return bool
     */
    public function cancelTransaction(): bool
    {
        if ($this->stmt) return $this->stmt->rollBack();
    }

    /**
     * dumps the the information that was contained in the Prepared Statement
     * 
     * @return mixed
     */
    public function dumpParams(): void
    {
        if ($this->stmt) return $this->stmt->debugDumpParams();
    }

    /**
     * @inheritDoc
     *
     * @return integer
     */
    public function getLastId() : int
    {
        try {
            if ($this->dbh->open()) {
                $lastID = $this->dbh->open()->lastInsertId();
                if (!empty($lastID)) {
                    return intval($lastID);
                }
            }
        }catch(Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * Returns the query condition merged with the query parameters
     * 
     * @param array $conditions
     * @param array $parameters
     * @return array
     */
    public function buildQueryParameters(array $conditions = [], array $parameters = []) : array
    {
        return (!empty($parameters) || (!empty($conditions)) ? array_merge($conditions, $parameters) : $parameters);
    }

    /**
     * Persist queries to database
     * 
     * @param string $query
     * @param array $parameters
     * @return mixed
     * @throws Throwable
     */
    public function persist(string $sqlQuery, array $parameters)
    {
        try {
            return $this->prepare($sqlQuery)->bindParameters($parameters)->execute();
        } catch(Throwable $throwable){
            throw $throwable;
        }
    }
}