<?php
class TruncateDatabaseOperation implements PHPUnit_Extensions_Database_Operation_IDatabaseOperation
{
    public function execute(PHPUnit_Extensions_Database_DB_IDatabaseConnection $connection, PHPUnit_Extensions_Database_DataSet_IDataSet $dataSet)
    {
        /* @var $table PHPUnit_Extensions_Database_DataSet_ITable */
        foreach ($dataSet->getReverseIterator() as $table) {
            $query = "
                DELETE FROM {$connection->quoteSchemaObject($table->getTableMetaData()->getTableName())}
            ";

            try {
                $connection->getConnection()->query($query);
            } catch (PDOException $e) {
                throw new PHPUnit_Extensions_Database_Operation_Exception('CUSTOM_TRUNCATE', $query, array(), $table, $e->getMessage());
            }

            $query = "
                ALTER TABLE {$connection->quoteSchemaObject($table->getTableMetaData()->getTableName())} AUTO_INCREMENT=1
            ";

            try {
                $connection->getConnection()->query($query);
            } catch (PDOException $e) {
                throw new PHPUnit_Extensions_Database_Operation_Exception('CUSTOM_TRUNCATE', $query, array(), $table, $e->getMessage());
            }
        }
    }
}
