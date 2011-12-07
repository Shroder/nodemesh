<?php

class ChainUtils {
    /*
     * Extracts select columns.
     * array(
     *     "name"       => ,
     *     "path"       => ,
     *     "duplicated" => ,
     *     "columns"    => array(
     *         "name"  => ,
     *         "alias" =>
     d      )
     * )
     */

    public function extractSelectColumns(Call $call, $path = "/", &$tables = array())
    {
        $table["name"]       = $call->table;
        $table["path"]       = $path;
        $table["duplicated"] = false;
        $table["columns"]    = array();
        $table['duplicated'] = self::tableIsDuplicated($call->table, $tables);

        if (empty($call->columns) && $path == "/")
        {
            $table['columns'][0]['name'] = "*";
        }
        elseif (!empty($call->columns))
        {
            foreach ($call->columns as $expression)
            {
                $columns = $expression->getColumns();
                $alias   = $expression->getAlias();


                foreach ($columns as $column)
                {
                    $c['name'] = $column->getColumn();
                    if ($alias == $c['name'])
                    {
                        $c['alias'] = '';
                    }
                    else
                    {
                        $c['alias']  = $alias;
                    }
                    $table['columns'][] = $c;
                }
            }
        }
        $tables[] = $table;
        foreach ($call->_calls as $i => $subcall)
        {
            self::extractSelectColumns($subcall, "$path$i/", &$tables);
        }
        return $tables;
    }

    /*
     * Handles extracting join information from chain
     *
     */
    public function extractJoins() {
        
    }

    /*
     * Handles extracting conditional information from chain.
     * Records unique path and marks tables as duplicated so that external
     * function may generate a unique table-column name.
     * Returns:
     * array(
     *     "conditions" => array(
     *         array(
     *             "table"     => array(
     *                 "name"       => ,
     *                 "duplicated" =>
     *             ),
     *             "path"      => ,
     *             "column"    => ,
     *             "andor"     => ,
     *             "value"     => ,
     *             "query"     =>
     *         )
     *     )
     * )
     */
    public function extractConditions($chain, $path = "", &$conditions = array())
    {
        /*
        * Current implementation for duplicates is SQL specific. Would be better if
         * this was taken out.
        */
        foreach ($chain->_calls as $key => $call) {
            $condition = array();
            $condition['path'] = $path."/".$key;
            $condition['table']['name'] = $call->table;
            $condition['table']['duplicated'] = self::tableIsDuplicated($call->table, $conditions);

            $call_conditions = array();
            for ($i=0; $i < count($call->where); $i++) {
                $columns = $call->where[$i]['column']->getColumns();
                /**
                 * @Todo This shouldn't be empty. For some reason a dummy call
                 * is being added.
                 */
                if (!empty($columns[0]))
                {
                    $condition['column'] = $columns[0]->getColumn();
                    $condition['andor']  = $call->where[$i]['andor'];
                    $condition['value']  = $call->where[$i]['value'];
                    $condition['query']  = $call->where[$i]['query'];
                    $call_conditions[] = $condition;
                }
            }
            if (!empty($call_conditions))
            {
                $conditions[] = $call_conditions;
            }

            self::extractConditions($call, $path."/".$key, &$conditions);
        }
        return $conditions;
    }

    public function extractOrder($chain, $path = "", &$collection = array())
    {
        if (empty($chain->_calls))
        {
            return $collection;
        }
        foreach ($chain->_calls as $key => $call) {
            $duplicated = self::tableIsDuplicated($call->table, $collection);
            foreach ($call->order as $plugin)
            {
                foreach($plugin['column']->getColumns() as $column) {
                    $collection[] = array(
                        "path"  => $path."/".$key,
                        "table" => array(
                            "name" => $call->table,
                            "duplicated" => $duplicated
                        ),
                        "column" => $column->getColumn(),
                        "query"  => $plugin['query'],
                        "value"  => $plugin['value']
                    );
                }
            }
            self::extractOrder($call, $path."/".$key, &$collection);
        }
        return $collection;
    }

    public function extractLimit($chain)
    {
        if (empty($chain->_calls))
        {
            return null;
        }
        $call = $chain->_calls[0];

        if (empty($call->limit))
        {
            return null;
        }
        return array(
            "limit" => $call->limit,
            "start" => $call->start
        );
    }

    public function tableIsDuplicated($needle, $haystack)
    {
        for ($i=0; $i < count($haystack); $i++)
        {
            if ($haystack[$i]['table']['name'] == $needle) {
                return true;
            }
        }
        return false;
    }
}

?>
