<?php
/**
 * Description of testNodeChaining
 *
 * @author josterholt
 */
require_once "PHPUnit/Extensions/Database/DataSet/YamlDataSet.php";
class NodeLinkingTest extends Generic_DatabaseTestCase {
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

    public function testMultiLink() {
        $node = new Node("properties", "eq:description:Test Insert", "eq:price:100");
        $node2 = new Node("properties", "eq:description:Test Insert", "eq:price:100");
        $node->link($node2, array("label" => "test"));
        $node2->link($node, array("label" => "test2"));
    }
}

?>
