<?php


namespace app\models;

use app\models\SqlFragment as SqlFragment;

class OrWhere extends Where
{
    /**
     * @return SqlFragment
     */
    public function generateSql()
    {
        if ($this->operator != "") {
            return new SqlFragment("OR " . $this->column . " " . $this->operator . " ? ", [$this->parameter]);
        } else {
            return new SqlFragment("OR " . $this->column . " = ? ", [$this->parameter]);
        }
    }
}