<?php


namespace app\models;


class SelectColumnsDistinct extends SelectColumns
{

    /**
     * @return SqlFragment
     */
    public function generateSql(){
        $sql = "SELECT DISTINCT ";
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