<?php


namespace app\models;


class LeftJoin extends Join
{

    public function generateSql()
    {
        $sql = "LEFT JOIN " . $this->table . " ON " . $this->parameter1 . " = " . $this->parameter2." ";
        return new SqlFragment($sql, []);
    }
}