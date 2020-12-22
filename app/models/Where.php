<?php


namespace app\models;

use app\models\SqlFragment;

class Where extends Command
{

    public string $column;
    public string $parameter;
    public string $operator;

    /**
     * Where constructor.
     *
     * @param string $column
     * @param string $parameter
     * @param string $operator
     */
    public function __construct(string $column, string $parameter, string $operator)
    {
        $this->column = $column;
        $this->parameter = $parameter;
        $this->operator = $operator;
        $this->sqlFragment = $this->generateSql();
    }

    public function generateSql()
    {
        if ($this->operator != "") {
            return new SqlFragment("WHERE " . $this->column . " " . $this->operator . " ? ", [$this->parameter]);
        } else {
            return new SqlFragment("WHERE " . $this->column . " = ? ", [$this->parameter]);
        }
    }
}