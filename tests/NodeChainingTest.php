<?php
/**
 * Description of testNodeChaining
 *
 * @author josterholt
 */
require_once "PHPUnit/Extensions/Database/DataSet/YamlDataSet.php";
class NodeChainingTest extends Generic_DatabaseTestCase {
    public function setUp()
    {
        $sqlDebugger = SQLDebugger::getInstance();
        $sqlDebugger->enable();
        parent::setUp();
    }
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
        $this->assertNotEmpty($nodes->Me[0]->pk);
    }
    
    // Multiple lines of chaining
    function testCanChainFromNode1()
    {
        $node = new Node("properties", 1);
        $tags = $node->tags();
        $this->assertType("Cluster", $tags);
        $this->assertEquals(2, count($tags));
    }

    // Multiple lines of chaining
    function testCanChainFromNode2()
    {
        $node = new Node("properties", 1);
        $tags = $node->tags("eq:subtype:AREA");
        $this->assertType("Cluster", $tags);
        $this->assertEquals(1, count($tags));
    }

    public function testCanChainFromCluster1() {
        $node = new Cluster("properties");
        $tags = $node->tags();
        DebugUtils::getChain($node);
        DebugUtils::getChain($tags);
        print_r($tags->toArray());
        $this->assertType("Cluster", $tags);
        $this->assertEquals(4, count($tags));
    }

    public function testCanChainFromCluster2() {
        $node = new Cluster("properties", "select:price>>properties_price");
        $tags = $node->tags("eq:subtype:AREA");
        $this->assertType("Cluster", $tags);
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
        $node = new Node("properties", 1, "select: description>>foo");
        $this->assertNotEmpty($node->foo);
    }

    public function testCanOrder() {
        $cluster = new Cluster("properties", "order:address:ASC");
        print_r($cluster->toArray());
        $cluster = new Cluster("properties", "order:address:DESC");
        print_r($cluster->toArray());
    }

    function testCanLimitResult()
    {
        $cluster = new Cluster("properties", "top:1");
        $this->assertEquals(1, count($cluster));
    }

    function testCanSetStartPosition()
    {
        $cluster = new Cluster("properties", "start:2");
        print_r($cluster->toArray());
        $this->assertEquals("2", $cluster->Me[0]->pk);
    }
    
    // Use alias on cluster of nodes
    function testAlias2()
    {
        $cluster = new Cluster("properties", "select: description>>foo");
        $this->assertNotEmpty($cluster->Me[0]->foo);
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
        $node->description = "Test Insert";
        $node->price = 100;
        $node->Commit();
        
        $node = new Node("properties", "eq:description:Test Insert", "eq:price:100");
        //$this->assertAttributeNotEmpty("pk", $node);
        $this->assertNotEmpty($node->pk);
    }
    
    // Insert multiple nodes
    function testInsertNodes()
    {
        
    }

    function testDisplayDebugInfo()
    {
        $sqlDebugger = SQLDebugger::getInstance();
        //print_r($sqlDebugger->getQueries());
    }
}

?>
