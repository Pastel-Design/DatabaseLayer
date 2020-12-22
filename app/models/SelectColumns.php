<?php


namespace app\models;

use app\models\SqlFragment;

class SelectColumns extends Command
{
    public string $table;
    public array $columns;

    public function __construct($tableName, $columns)
    {
        $this->table = $tableName;
        $this->columns = $columns;
        $this->sqlFragment = $this->generateSql();
    }

    public function generateSql(){
        $sql = "SELECT ";
        foreach ($this->columns as $column){
            $sql.= $column.",";
        }
        $sql = substr($sql, 0, -1);
        $sql .= " FROM ".$this->table." ";
        return new SqlFragment($sql,[]);
    }
}