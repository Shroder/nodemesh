<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class
 *
 * @author josterholt_2
 */
class SQLDebugger {
    protected $enabled = false;
    protected $queries = array();
    protected $currentQuery = null;
    protected $start = null;
    protected $index = 0;

    private static $_sInstance = null;

    private function __construction() {

    }

    public function __clone()
    {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

    public function __wakeup()
    {
        trigger_error('Unserializing is not allowed.', E_USER_ERROR);
    }

    public static function getInstance() {
        if (self::$_sInstance == null) {
            self::$_sInstance = new SQLDebugger();
        }
        return self::$_sInstance;
    }

    public function enable() {
        $this->enabled = true;
    }

    public function start($sql) {
        if ($this->enabled == true) {
            $this->start = microtime(true);
            $this->queries[++$this->index] = array('sql' => $sql, 'executionTime' => 0);
        }
    }

    public function stop() {
        $this->queries[$this->index]['executionTime'] = microtime(true) - $this->start;
        
    }

    public function getQueries() {
        return $this->queries;
    }

    public function reset() {
        self::$_sInstance = null;
    }

    // Analyze queries being run, output in list
    // - Include hot spots, index comparison

    // Analyze "hot spots"

    // Compare indexes against hotspots
}
?>
