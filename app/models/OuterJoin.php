<?php


namespace app\models;


class OuterJoin extends Join
{
    /**
     * @return SqlFragment
     */
    public function generateSql()
    {
        $sql = "OUTER JOIN " . $this->table . " ON " . $this->parameter1 . " = " . $this->parameter2 . " ";
        return new SqlFragment($sql, []);
    }
}