<?php
/**
 * Description of testNodeChaining
 *
 * @author josterholt
 */
class NodeChainingTest extends Generic_DatabaseTestCase {
    public function getDataSet()
    {
        return $this->createFlatXMLDataSet(dirname(__FILE__).'/_files/test_constraints.xml');
    }
    
    public function testNodeChaining() {
        $node = new Node("properties", 1);
        $this->assertEquals(1, $node->pk, "Error fetching pk");
        $this->assertEquals(6, $this->getConnection()->getRowCount('properties'), "Inserting failed");
    }
}

?>
