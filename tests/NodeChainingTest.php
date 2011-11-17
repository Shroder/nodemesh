<?php
/**
 * Description of testNodeChaining
 *
 * @author josterholt
 */
require_once "PHPUnit/Extensions/Database/DataSet/YamlDataSet.php";
class NodeChainingTest extends Generic_DatabaseTestCase {
    public function getDataSet()
    {
        return new PHPUnit_Extensions_Database_Dataset_YamlDataSet(dirname(__FILE__).'/_files/test_constraints.yml');
    }
    
    // Retrieve single node
    public function testGetNode()
    {
        $node = new Node("properties", 1);
        $this->assertEquals(1, $node->pk);
        $this->assertEquals("Test 1", $node->description);
    }
    
    // Retrieve cluster of nodes
    function testGetNodes()
    {
        $nodes = new Cluster("properties");
        $this->assertEquals(6, count($nodes));
    }
    
    // Retrieving node form cluster with Me
    function testGetNodeFromCluster()
    {
        $nodes = new Cluster("properties");
        $this->assertObjectHasAttribute("pk", $nodes->Me[0]);
    }
    
    // Multiple lines of chaining
    function testChaining1()
    {
        $node = new Node("properties", 1);
        $tags = $node->tags("eq:subtype:AREA");
        $this->assertType("Cluster", $tags); //May need to pass in type of object for second param
        $this->assertEquals(1, count($tags));
    }

    public function testChaining2() {
        $node = new Cluster("properties");
        $tags = $node->tags("eq:subtype:AREA");
        $this->assertEquals(2, count($tags));
    }    

    public function testNodeRetrieval() {
        $node = new Node("properties", 1);
        $this->assertEquals(1, $node->pk, "Error fetching pk");
        $this->assertEquals(6, $this->getConnection()->getRowCount('properties'), "Inserting failed");
    }    
    
    // Use alias on single node
    function testAlias()
    {
        $foo = (object) array("test" => "test");
        $this->assertObjectHasAttribute("test", $foo);
        $node = new Node("properties", 1, "select: description>>foo");
        $this->assertObjectHasAttribute("foo", $node);
    }
    
    // Use alias on cluster of nodes
    function testAlias2()
    {
        $cluster = new Cluster("properties", "select: description>>foo");
        $this->assertObjectHasAttribute("foo", $cluster->Me[0]);
    }
    
    // Use jump
    function testJump()
    {
        // Need to establish jumpable data
    }
    
    // Insert node
    function testInsertNode()
    {
        $node = new Node("properties");
        $node->title = "Test Insert";
        $node->price = 100;
        $node->Commit();
        
        $node = new Node("properties", "eq:title:Test Insert", "eq:price:100");
        $this->assertAttributeNotEmpty("pk", $node);
    }
    
    // Insert multiple nodes
    function testInsertNodes()
    {
        
    }
}

?>
