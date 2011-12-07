<?php


class CallTest extends PHPUnit_Framework_TestCase {
    public function setUp() {
        
    }

    public function testCanApply1() {
        $call = new Call();
        $call->apply("select:pk, foo, bar");
        $call->apply("eq:foo:1324");
        $this->markTestIncomplete();
    }
}

?>
