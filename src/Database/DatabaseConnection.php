<?php 

declare(strict_type=1);

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
    protected PDO $dbh;

    /**
     * @var array
     */
    private static $_instance = null;

    /**
    * Initialize constructor
    * @return void
    */
    private function __construct()
    {
    	$this->open();
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
    			getenv('HOSTNAME'),
    			getenv('USERNAME'),
    			getenv('PASSWORD'),
    			$options
    		);

            return $this->dbh;

    	} catch (PDOException $e) {
    		throw new DatabaseConnectionException($e->getMessage(), (int) $e->getCode());
    	}
    }

    /**
     * Create an instance
     */
    public static function createIntance()
    {

        $dotenv = new Dotenv\Dotenv();

        if (getenv('APP_ENV') === 'development') {

            $dotenv->load(__DIR__);

            self::$_config = true;
        }

        if (self::$_config == true) {
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