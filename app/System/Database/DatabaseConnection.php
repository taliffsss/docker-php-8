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
     * @var instance
     */
    private static $instance = null;

    /**
    * Initialize constructor
    * @return void
    */
    private function __construct()
    {
        $this->credentials = $credentials;
    }

    /**
    * Create new Connetion
    */
    private function open(): PDO
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

            $this->error = $e->getMessage();

        }

        return $this->dbh;
    }

    /**
     * Create an instance
     */
    public static function getInstance() 
    {

        self::$_config = true;

        if (self::$_config == TRUE) {
            if (!isset(self::$_instance)) {
                self::$_instance = new Database();
            }

            return self::$_instance;
        }

        return false;
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