<?php 

namespace Simple\Orm\QueryBuilder;

use \PDOStatement;
use \Throwable;
use \PDO;

class QueryBuilder implements QueryBuilderInterface
{

    /** @var DatabaseInterface */
    private $db;

    /**
     * @var $select
     */
    private string $select;

    /**
     * @var $from
     */
    private string $from;

    /**
     * @var $get
     */
    private string $get;

    /**
     * @var $join
     */
    private string $join;

    /**
     * @var $where
     */
    private array $where = [
        'where'     => null,
        'original'  => []
    ];

    /**
     * @var $table
     */
    public string $table;

    /**
     * @var $primary_key
     */
    public string $primary_key = 'id';

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
        $this->db = $dbh;
    }

    /**
     * Save Data
     *
     * @param $table TableName
     * @param $data FieldName & FieldValue
     */
    public function insert(string $table, array $data): bool
    {

        ksort($data);

        $fieldsName = implode(",", array_keys($data));

        $fieldValue = ':'.implode(", :", array_keys($data));

        $query = "INSERT INTO $table ($fieldsName) VALUES ($fieldValue)";

        $this->query($query);

        foreach ($data as $key => $value) {
            $this->bind(":$key", $value);
        }

        return $this->execute();

    }

    /**
     * Save Data
     *
     * @param $table TableName
     * @param $data FieldName & FieldValue
     */
    public function save(array $data): bool
    {

        if (!in_array($this->primary_key, $data)) {
            return $this->insert($this->table, $data);
        }

        $where = [
            $this->primary_key => $data[$this->primary_key]
        ];

        // remove primary key
        unset($data[$this->primary_key]);

        return $this->update($this->table, $data, $where);

    }

    public function select(string $select): string
    {
        $this->select = "SELECT {$select} ";

        return $this->select;
    }

    public function get(string $table = null): bool
    {
        if (empty($this->select)) {
            $this->select = "SELECT * ";
        }

        if (empty($this->from)) {
            $this->from = "From {$table}"
        }

        $sqlQuery = "{$this->select} {$this->from} {$this->join} {$this->where['where']}";

        $this->query($sqlQuery);

        $this->isArray($this->where['original']);

        foreach ($this->where['original'] as $key => $value) {
            $this->bind(":$key", $value);
        }

        return $this->execute();
    }

    public function from(string $table): string
    {
        $this->from = "From {$table} ";

        return $this->from;
    }

    public function join(array $join): string
    {

        // check if multidimentional array
        $isMultidimentional = array_filter($join, 'is_array');

        if (count($isMultidimentional) > 0) {
            foreach ($join as $key => $val) {

                $prefix = strtoupper($val[2]);

                $this->join .= "{$prefix} JOIN {$val[0]} ON {$val[1]} ";
            }
        } else {

            $prefix = strtoupper($join);

            $this->join = "{$prefix} JOIN {$join[0]} ON {$join[1]} ";
        }

        return $this->join;
    }

    public function where(array $where): string
    {

        $where = '';

        foreach ($where as $key => $val) {

            $where .= "$key = :$key AND ";
        }

        $this->where = [
            'where'     => rtrim($where,' AND '),
            'original'  => $where
        ];

        return $this->where;
    }

    /**
     * Update data
     * @param $table TableName
     * @param Array $data FieldName & FieldData
     * @param $where Where clause
     */
    public function update(string $table, array $data, array $where): bool
    {

        $this->isArray($data);
        $this->isArray($where);

        ksort($data);
        $fieldsDetails = NULL;

        foreach ($data as $key => $value) {
            $fieldsDetails .= "$key = :$key,";
        }

        $fieldsDetails = rtrim($fieldsDetails,',');

        $this->where($where);

        $query = "UPDATE $table SET $fieldsDetails WHERE {$this->where['where']}";

        $this->query($query);

        foreach ($data as $key => $value) {
            $this->bind(":$key", $value);
        }

        $this->isArray($this->where['original']);

        foreach ($this->where['original'] as $key => $value) {
            $this->bind(":$key", $value);
        }

        return $this->execute();
    }

    /**
     * the variable is bound as a reference and will only be evaluated at the time that PDOStatement::execute()
     * @param $param is the placeholder value that we will be using in our SQL statement :param
     * @param $value is the actual value that we want to bind to the placeholder
     */
    public function bind($param, $value)
    {  
        if ($this->stmt) {
            switch ($value) {
                case is_int($value):
                $type = PDO::PARAM_INT;
                break;
            case is_bool($value):
                $type = PDO::PARAM_BOOL;
                break;
            case is_null($value):
                $type = PDO::PARAM_NULL;
                break;
            default:
                $type = PDO::PARAM_STR;
            }
            $this->stmt->bindValue($param, $value);
        }
    }

    /**
     * Check the incoming $valis isn't empty else throw an exception
     * 
     * @param mixed $value
     * @param string|null $errorMessage
     * @return void
     * @throws Exception
     */
    private function isEmpty($value)
    {
        if (empty($value)) {
            throw new Exception('Argument must not empty');
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

        $this->isEmpty($value);

        if (!is_array($value)) {
            throw new Exception('Your argument needs to be an array');
        }
    }

    private function query(string $sqlQuery)
    {
        $this->stmt = $this->db->prepare($sqlQuery);
    }

    public function results(): object 
    {
        if ($this->stmt) return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function row(): object
    {
        if ($this->stmt) return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    public function rowArray(): array
    {
        if ($this->stmt) return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function resultsArray(): array
    {
        if ($this->stmt) return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
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
     * returns the number of effected rows
     */
    public function numRows(): int
    {
        if ($this->stmt) return $this->stmt->rowCount();
    }

    /**
     * returns the last inserted Id as a string
     */
    public function lastInsertId(): int
    {
        if ($this->stmt) return $this->stmt->lastInsertId();
    }

    /**
     * Transactions allows you to run multiple changes to a database
     */
    public function beginTransaction(): bool
    {
        if ($this->stmt) return $this->stmt->beginTransaction();
    }

    /**
     * End a transaction and commit your changes
     */
    public function commit(): bool
    {
        if ($this->stmt) return $this->stmt->commit();
    }

    /**
     * Cancel a transaction and roll back your changes
     */
    public function rollBack(): bool
    {
        if ($this->stmt) return $this->stmt->rollBack();
    }

    /**
     * dumps the the information that was contained in the Prepared Statement
     */
    public function dumpParams()
    {
        if ($this->stmt) return $this->stmt->debugDumpParams();
    }
}