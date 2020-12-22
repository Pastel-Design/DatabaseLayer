<?php


namespace app\models;

use app\models\SqlFragment;

class SelectTables extends Command
{
    public array $tables;

    /**
     * SelectTables constructor.
     *
     * @param $tables
     */
    public function __construct($tables)
    {
        $this->tables = $tables;
        $this->sqlFragment = $this->generateSql();
    }

    /**
     * @return \app\models\SqlFragment
     */
    public function generateSql(){
        $sql = "SELECT * FROM ";
        foreach ($this->tables as $table){
            $sql.= $table.",";
        }
        $sql = substr($sql, 0, -1);
        $sql.=" ";
        return new SqlFragment($sql,[]);
    }
}