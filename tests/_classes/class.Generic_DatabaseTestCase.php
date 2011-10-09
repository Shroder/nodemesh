<?
abstract class Generic_DatabaseTestCase extends PHPUnit_Extensions_Database_TestCase
{
    static private $pdo = null;
    private $conn = null;

    protected function getSetUpOperation() {
      return new PHPUnit_Extensions_Database_Operation_Composite(array(
            new TruncateDatabaseOperation(),
            PHPUnit_Extensions_Database_Operation_Factory::INSERT()
        ));
    }

    final public function getConnection()
    {
        if ($this->conn === null) 
        {
            if (self::$pdo == null) 
            {
                self::$pdo = new PDO( $GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'] );
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);
        }

        return $this->conn;
    }
}