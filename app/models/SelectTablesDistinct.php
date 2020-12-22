<?php


namespace app\models;


class SelectTablesDistinct extends SelectTables
{
    /**
     * @return SqlFragment
     */
    public function generateSql(){
        $sql = "SELECT DISTINCT * FROM ";
        foreach ($this->tables as $table){
            $sql.= $table.",";
        }
        $sql = substr($sql, 0, -1);
        $sql.=" ";
        return new SqlFragment($sql,[]);
    }
}