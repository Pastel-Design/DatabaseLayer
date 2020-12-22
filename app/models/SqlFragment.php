<?php


namespace app\models;


class SqlFragment
{
    public string $sql;
    public array $vars;

    /**
     * SqlFragment constructor.
     *
     * @param string $sql
     * @param array  $vars
     */
    public function __construct(string $sql, array $vars)
    {
        $this->sql = $sql;
        $this->vars = $vars;
    }

    public function getSql(){
        return $this->sql;
    }

    public function getVars(){
        return $this->vars;
    }
}