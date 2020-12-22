<?php


namespace app\models;


class RightJoin extends Join
{
    /**
     * @return SqlFragment
     */
    public function generateSql()
    {
        $sql = "RIGHT JOIN " . $this->table . " ON " . $this->parameter1 . " = " . $this->parameter2." ";
        return new SqlFragment($sql, []);
    }
}