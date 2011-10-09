<?php
/**
 * Description of testNodeChaining
 *
 * @author josterholt
 */
class NodeChainingTest extends PHPUnit_Extensions_Database_TestCase {
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
