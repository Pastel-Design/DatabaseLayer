<?php


namespace app\models;

use app\models\SqlFragment;

class SelectColumns extends Command
{
    public array $tables;
    public array $columns;

    /**
     * SelectColumns constructor.
     *
     * @param $tables
     * @param $columns
     */
    public function __construct($tables, $columns)
    {
        $this->tables = $tables;
        $this->columns = $columns;
        $this->sqlFragment = $this->generateSql();
    }

    /**
     * @return \app\models\SqlFragment
     */
    public function generateSql(){
        $sql = "SELECT ";
        foreach ($this->columns as $column){
            $sql.= $column.",";
        }
        $sql = substr($sql, 0, -1);
        $sql .= " FROM ";
        foreach ($this->tables as $table){
            $sql.= $table.",";
        }
        $sql = substr($sql, 0, -1);
        $sql.=" ";
        return new SqlFragment($sql,[]);
    }
}