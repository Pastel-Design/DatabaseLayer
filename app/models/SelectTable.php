<?php


namespace app\models;

use app\models\SqlFragment;

class SelectTable
{
    public string $table;

    public function __construct($table)
    {
        $this->table = $table;
    }
    public function generateSql(){
        return new SqlFragment("SELECT * FROM ".$this->table." ",[]);
    }
}