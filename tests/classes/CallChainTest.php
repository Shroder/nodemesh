<?php
/*
CallChain
[0] Call
    JoinedCalls
    [1] Call
        [2] Call (Jump)
    [3] Call
        JoinedCalls
        [4] Call

CallChain
    _calls
    [0] Call
        _callChains
            X [0] CallChain
                _calls
                    [1] Call
                        _callChain
                            X [0] CallChain
                                _calls
                                    [2] Call (Jump)
                    [3] Call
                        _callChain
                            X [0] CallChain
                                _calls
                                    [4] Call
*/

class CallChainTest extends PHPUnit_Framework_TestCase {
    public function testCanBuildBasicChain() {
        $chain = new CallChain();
        $chain->push("Node1");
        $chain->push("Node2");
        $chain->push("Node3");
        $chain->push("Node4");
        $chain->push("Node5");
        $this->assertObjectHasAttribute("_callChain", $chain->_calls[0]);
        $this->assertObjectNotHasAttribute("_calls", $chain->_calls[0]);

        $this->assertEquals(1, count($chain->_calls));
        $this->assertEquals(1, count($chain->_calls[0]->_callChain));
    }

    public function testCanBuildBranchedChain() {
        $chain = new CallChain();
        $chain->push("Node1", array("set:HERE"));
        $chain->push("Node2");
        $call = new Call();
        $call->jump = "HERE";
        $chain->push($call);
        $chain->push("Node3");
        $chain->push("Node4");
        $chain->push("Node5");

        $this->assertObjectHasAttribute("_callChain", $chain->_calls[0]);
        $this->assertObjectNotHasAttribute("_calls", $chain->_calls[0]);
        $this->assertEquals(1, count($chain->_calls));
        $this->assertEquals(2, count($chain->_calls[0]->_callChain->_calls));
        $this->assertEquals("Node1", $chain->_calls[0]->table);
        $this->assertEquals("Node2", $chain->_calls[0]->_callChain->_calls[0]->table);
    }
}
?>
