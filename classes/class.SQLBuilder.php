<?
/*
 *  Copyright 2010 Enleap, LLC
 *
 *  This file is part of the Node Mesh.
 *
 *  The Node Mesh is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The Node Mesh is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with the Node Mesh.  If not, see <http://www.gnu.org/licenses/>.
 */

/*
 * $this->join($table1, $table2)
 * $this->leftJoin($table1, $table2)
 * $this->rightJoin($table1, $table2)
 * $this->on($joinTable)[$innerColumn] = $table->column; //
 */
class SQLBuilder
{
    private $_callChain;
    private $_sql = array();
    private static $_ContextTables;
    private static $_JoinTableProperties;

    public function __construct(Callchain $callChain = null)
    {
        if ($callChain != null)
        {
            $callChain->orderByOwnership();
            $this->_callChain = &$callChain;
        }
    }

    public function buildSQL(&$callChain = null)
    {
        if ($callChain == null)
        {
            $callChain = $this->_callChain;
        }
        else
        {
            $callChain->orderByOwnership();
            $this->_callChain = &$callChain;
        }

        $this->select()
            ->from()
            ->join()
            ->where()
            ->order()
            ->group()
            ->limit();
    }

    public function addSQL($sql = null)
    {
        if ($sql == null)
        {
            return;
        }

        $this->_sql[] = $sql;
    }

    public function getSQL()
    {
        return implode("\n", $this->_sql);
    }

    public function select($callChain = null)
    {
        if ($callChain == null)
        {
            $callChain = $this->_callChain;
        }
        $sql = "SELECT DISTINCT ";

        $tables = ChainUtils::extractSelectColumns($callChain->_calls[0]);

        foreach ($tables as $table)
        {
            $unique_table_name = $this->getTableName($table['name'], $table['duplicated'], $table['path']);
            foreach ($table['columns'] as $column)
            {
                $field = $this->formatTable($unique_table_name).'.'.$this->formatColumn($column['name']);
                if (!empty($column['alias']))
                {
                    $field .= " as ".$this->formatColumn($column['alias']);
                }
                $sql_columns[] = $field;
            }
        }
        $sql .= implode(", ", $sql_columns);
        $this->addSQL($sql);
        return $this;
    }


    public function from($chain = null)
    {
        if ($chain == null)
        {
            $chain = $this->_callChain;
        }
        $call = $chain->_calls[0];
        $sql = "FROM ".$this->formatTable($call->table);
        $this->addSQL($sql);
        return $this;
    }

    public function join($chain = null)
    {
        if ($chain == null)
        {
            $chain = $this->_callChain;
        }

        $chain = $chain->_calls[0];

        if (!empty($chain->_calls))
        {
            foreach ($chain->_calls as $call)
            {
                list($joinTable, $outerColumn, $innerColumn, $link_properties_sql) = $this->_getJoinDetails($call->_parent->table, $call->table);
                $this->addSQL('LEFT JOIN '.$this->formatTable($joinTable));
                $this->addSQL('LEFT JOIN '.$this->formatTable($call->table));
            }

            if (!empty($call->_calls))
            {
                $this->join($call);
            }
            $this->addSQL('ON '.$this->formatTable($joinTable).'.'.$this->formatColumn($innerColumn).' = '.$this->formatTable($call->table).'.'.$this->formatColumn('pk'));
            $this->addSQL('ON '.$this->formatTable($joinTable).'.'.$this->formatColumn($outerColumn).' = '.$this->formatTable($call->_parent->table).'.'.$this->formatColumn('pk'));
        }
        return $this;
    }

    // con1
    // (con1 and con2)
    // (con1 AND con2 OR con3)
    /**
     * @todo Handle context filtering
     * @param type $chain
     * @return SQLBuilder
     */
    public function where($chain = null)
    {
        if ($chain == null)
        {
            $chain = $this->_callChain;
        }

        $condition_sets = ChainUtils::extractConditions($chain);

        $sql_condition_set_conditions = array();
        $sql_set_conditions = array();

        if (empty($condition_sets))
        {
            return $this;
        }

        for ($i=0; $i < count($condition_sets); $i++)
        {
            $condition_set = $condition_sets[$i];
            $condition_set_sql = "";
            $sql_columns       = array();
            if (count($condition_set) > 1)
            {
                $condition_set_sql .= "(";
            }

            foreach ($condition_set as $condition)
            {
                $sql_condition = "";
                $condition['table']['unique_name']  = $this->getTableName($condition['table']['name'], $condition['duplicated'], $condition['path']);
                if (count($sql_columns) > 0)
                {
                    $sql_condition .= $condition['andor'];                    
                }
                $column = $this->formatTable($condition['table']['unique_name']).'.'.$this->formatColumn($condition['column']);
                $sql_condition .= str_replace(array('%col', '%val'), array($column, $condition['value']), $condition['query']);
                $sql_columns[] = $sql_condition;
            }
            $condition_set_sql .= implode("", $sql_columns);

            if (count($condition_set) > 1)
            {
                $condition_set_sql .= ")";
            }

            if ($i == 0)
            {
                $this->addSQL("WHERE ".$condition_set_sql);
            }
            else
            {
                $this->addSQL("AND ".$condition_set_sql);
            }
        }
        return $this;
    }

    public function order($chain = null)
    {
        if ($chain == null)
        {
            $chain = $this->_callChain;
        }
        $collection = ChainUtils::extractOrder($chain);

        if (empty($collection))
        {
            return $this;
        }

        $sql = "ORDER BY ";
        foreach ($collection as $order)
        {
            $order['table']['unique_name']  = $this->getTableName($order['table']['name'], $order['duplicated'], $order['path']);
            $column = $this->formatTable($order['table']['unique_name']).'.'.$this->formatColumn($order['column']);
            $conditions[] = str_replace(array('%col', '%val'), array($column, $order['value']), $order['query']);
        }
        $sql .= implode(", ", $conditions);
        $this->addSQL($sql);
        return $this;
    }

    public function group($chain = null)
    {
        return $this;
    }

    public function limit($chain = null)
    {
        if ($chain == null)
        {
            $chain = $this->_callChain;
        }
        $condition = ChainUtils::extractLimit($chain);
        if (empty($condition))
        {
            return $this;
        }
        $sql = "LIMIT ";
        if (!empty($condition['start']))
        {
            $conditions[] = $condition['start'];
        }
        $conditions[] = $condition['limit'];
        $sql .= implode(", ", $conditions);
        $this->addSQL($sql);
    }

    public function getTableName($name, $duplicated, $path)
    {
        if ($duplicated && $path != "/")
        {
            $table = $name."#".str_replace("/", "", $path);
        }
        else
        {
            $table = $name;
        }
        return $table;
    }

    public function formatTable($table)
    {
        return"`$table`";
    }

    public function formatColumn($column)
    {
        if ($column == '*')
        {
            return $column;
        }
        return"`$column`";
    }


    public function getDebugCount()
    {
        return self::$_debug_count;
    }

    /*
     * @todo Merge the caching portion of this function with _getLinkProperties()
     */
    private function _isContextSupported($table)
    {
        if (! isset(self::$_ContextTables))
        {
            $query = "SELECT TABLE_NAME
                      FROM INFORMATION_SCHEMA.COLUMNS
                      WHERE COLUMN_NAME = 'context'";
            $result = mysql_query($query);

            self::$_ContextTables = array();        // prevent isset() test above from occurring again

            while ($row = mysql_fetch_assoc($result))
            {
                self::$_ContextTables[$row['TABLE_NAME']] = $row['TABLE_NAME'];
            }
        }

        return array_key_exists($table, self::$_ContextTables);
    }

    /*
     * Putting this in a separate method allows us to abstract it out later
     */
    private function _getJoinDetails($outerTable, $innerTable)
    {
        if ($outerTable < $innerTable)
        {
            $joinTable   = "$outerTable#$innerTable";
            $outerColumn = 'pk1';
            $innerColumn = 'pk2';
        }
        else
        {
            $joinTable   = "$innerTable#$outerTable";
            $outerColumn = 'pk2';
            $innerColumn = 'pk1';
        }

        //$link_properties = $this->_getLinkProperties($joinTable);
        $link_properties = array();
        $sql             = '';


        foreach ($link_properties as $property)
        {
            if ('direction' == $property)
            {
                list($forward, $reverse) = ('pk1' == $innerColumn) ? array('LTR', 'RTL') : array('RTL', 'LTR');

                $sql .= ", (CASE WHEN direction = '$forward' THEN 'forward' WHEN direction = '$reverse' THEN 'reverse' ELSE NULL END) AS direction";
            }
            else
            {
                $sql .= ", `$joinTable`.$property AS $property";
            }
        }

        return array($joinTable, $outerColumn, $innerColumn, $sql);
    }

    private function _getLinkProperties($joinTable)
    {
        if (! isset(self::$_JoinTableProperties))
        {
            $query = "SELECT TABLE_NAME, COLUMN_NAME
                      FROM INFORMATION_SCHEMA.COLUMNS
                      WHERE TABLE_NAME LIKE '%#%' AND COLUMN_NAME != 'pk1' AND COLUMN_NAME != 'pk2'";
            $result = mysql_query($query);

            self::$_JoinTableProperties = array();        // prevent isset() test above from occurring again

            while ($row = mysql_fetch_assoc($result))
            {
                $table  = $row['TABLE_NAME'];
                $column = $row['COLUMN_NAME'];
                self::$_JoinTableProperties[$table][$column] = $column;
            }
        }

        return self::$_JoinTableProperties[$joinTable];
    }

    private function _formatOrder($order)
    {
        $conditions = array();

        foreach ($order as $o)
        {
            $column         = $o['column']->toString(null, true);       // @todo Check aliases, then columns
            $condition      = str_replace(array('%col', '%val'), array($column, $o['value']), $o['query']);
            $conditions[]   = $condition;
        }

        return $conditions;
    }

    private function _formatGroup($group)
    {
        $conditions = array();

        foreach ($group as $g)
        {
            $column         = $g['column']->toString(null, true);       // @todo Check columns, then aliases
            $condition      = str_replace(array('%col', '%val'), array($column, $g['value']), $g['query']);
            $conditions[]   = $condition;
        }

        return $conditions;
    }

    private function _formatLimit($format, $start, $limit)
    {
        if ($limit)
        {
            return sprintf($format, $start, $limit);
        }
        else
        {
            return '';
        }
    }

    private function _format($format)
    {
        $args = func_get_args();

        if (count(array_filter($args)) > 1)     // if more than one argument is set (first argument will always be set)
        {
            return call_user_func_array('sprintf', $args);
        }
        else
        {
            return '';
        }
    }

    public function execute()
    {
        if (!$this->getSQL())
        {
            $this->buildSQL();
        }
        // @todo Select the proper database from Config
        if (MeshTools::$GlobalDebug)
        {
            MeshTools::$DebugCounter++;
            echo 'Q' . MeshTools::$DebugCounter . '==========>' . $this->_sql;
            error_log('Q' . MeshTools::$DebugCounter . '==========>' . $this->_sql);
        }

        $dbc = new DatabaseConnection();
        return $dbc->query($this->getSQL());
    }

    public function commit($changes)
    {
        $table = $this->_getTable();
        $pk    = $this->_getPk();

        if ($pk && empty($changes))     // nothing to update
        {
            return array();
        }
        elseif ($pk)        // update
        {
            $updates = array();

            foreach ($changes as $col => $v)
            {
                // @todo Use database-specific quoting and escaping
                $updates[] = "`$col` = " . '"' . mysql_escape_string($v) . '"';
            }

            $updates = join(', ', $updates);

            $this->_sql = "UPDATE $table SET $updates WHERE pk = $pk";
        }
        elseif (empty($changes))        // simple insert
        {
            $this->_sql = "INSERT INTO $table (pk) VALUES (NULL); SELECT LAST_INSERT_ID()";     // @todo Not portable!
        }
        else        // full insert
        {
            $columns = array();
            $values  = array();

            foreach ($changes as $col => $v)
            {
                $columns[] = "`$col`";                                  // @todo Use database-specific quoting
                $values[]  = '"' . mysql_escape_string($v) . '"';       // @todo Use database-specific escaping
            }

            $columns = join(', ', $columns);
            $values  = join(', ', $values);

            $this->_sql = "INSERT INTO $table ($columns) VALUES ($values); SELECT LAST_INSERT_ID()";    // @todo Not portable!
        }

        return $this->execute();
    }
}
