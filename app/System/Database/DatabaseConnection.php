<?php 

namespace Simple\Database;

use Simple\Database\Exception\DatabaseConnectionException;
use Simple\Database\DatabaseInterface;
use \PDO;

/**
 * summary
 */
class DatabaseConnection implements DatabaseInterface
{
    /**
     * @var PDO
     */
    protected $dbh;

    /**
     * @var array
     */
    protected $credentials = [];

    /**
    * Initialize constructor
    * @return void
    */
    public function __construct(array $credentials)
    {
        $this->credentials = $credentials;
    }

    /**
    * Create new Connetion
    */
    public function open(): PDO
    {
        try {

            $options = [
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];

            $this->dbh = new PDO(
                $this->credentials['dsn'],
                $this->credentials['username'],
                $this->credentials['password'],
                $options
            );
        } catch (PDOException $e) {
            throw new DatabaseConnectionException($e->getMessage(), (int) $e->getCode());
        }

        return $this->dbh;
    }

    /**
     * Close Connetion
     */
    public function close(): void
    {
        $this->dbh = null;
    }
}
?>