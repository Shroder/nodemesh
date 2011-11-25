<?php
class QueryTest extends PHPUnit_Framework_TestCase {
    public function testCanProcessBasicChain() {
        $chain = new CallChain();
        $chain->push("Node1");
        $chain->push("Node2");
        $chain->push("Node3");
        $chain->push("Node4");
        $chain->push("Node5");

        $query = new Query($chain);
        
    }

    public function testCanProcessBranchedChain() {
        
    }
}

?>
