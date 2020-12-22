<?php


namespace app\models;

use app\models\SqlFragment as SqlFragment;

class AndWhere extends Where
{
    /**
     * @return SqlFragment
     */
    public function generateSql()
    {
        if ($this->operator != "") {
            return new SqlFragment("AND " . $this->column . " " . $this->operator . " ? ", [$this->parameter]);
        } else {
            return new SqlFragment("AND " . $this->column . " = ? ", [$this->parameter]);
        }
    }
}