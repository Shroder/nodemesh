<?php
require_once("classes/TruncateDatabaseOperation.php");

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of testNodeChaining
 *
 * @author josterholt_2
 */
class NodeChainingTest extends PHPUnit_Extensions_Database_TestCase {
    protected function getSetUpOperation() {
      return new PHPUnit_Extensions_Database_Operation_Composite(array(
            new TruncateDatabaseOperation(),
            PHPUnit_Extensions_Database_Operation_Factory::INSERT()
        ));
    }

    public function getConnection()
    {
        $pdo = new PDO('mysql:host=localhost;dbname=properties', 'user', 'password');
        return $this->createDefaultDBConnection($pdo, "mysql:");
    }
    
    public function getDataSet()
    {
        return $this->createFlatXMLDataSet(dirname(__FILE__).'/_files/test_constraints.xml');
    }
    
    public function testNodeChaining() {
        $node = new Node("properties", 1);
        $this->assertEquals(1, $node->pk, "Error fetching pk");
        $this->assertEquals(5, $this->getConnection()->getRowCount('properties'), "Inserting failed");
    }
}

?>
