<?php


namespace app\models;

use app\models\SqlFragment;

class SelectTable extends Command
{
    public string $table;

    public function __construct($table)
    {
        $this->table = $table;
        $this->sqlFragment = $this->generateSql();
    }
    public function generateSql(){
        return new SqlFragment("SELECT * FROM ".$this->table." ",[]);
    }
}