<?php 

namespace Cartrack\Database;

use \PDOStatement;
use \Throwable;
use \PDO;

class QueryBuilder implements QueryBuilderInterface
{

    /** @var DatabaseInterface */
    private $db;

    /** @var PDOStatement */
    private $statement;

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
}