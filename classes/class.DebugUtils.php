<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DebugUtils
 *
 * @author josterholt_2
 */
class DebugUtils {
    /*
     * Accepts CallChain, NodeCore instances
    */
    public function getChain($object)
    {
        if ($object instanceof CallChain)
        {
            $chain = $object->_condensed();
            return $chain;
        }
        elseif ($object instanceof NodeCore)
        {
            $debug = $object->_debugInfo();
            return $debug['callchain'];
        }
        throw new Error("Invalid Object passed as parameter");
    }
}

?>
