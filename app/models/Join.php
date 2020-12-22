<?php


namespace app\models;


class Join extends Command
{

    public string $table;
    public string $parameter1;
    public string $parameter2;
    /**
     * Join constructor.
     *
     * @param string $table
     * @param string $parameter1
     * @param string $parameter2
     */
    public function __construct(string $table, string $parameter1, string $parameter2)
    {
        $this->table = $table;
        $this->parameter1 = $parameter1;
        $this->parameter2 = $parameter2;
        $this->sqlFragment = $this->generateSql();
    }

    /**
     * @return SqlFragment
     */
    public function generateSql()
    {
        $sql = "JOIN ".$this->table." ON ".$this->parameter1." = ".$this->parameter2." ";
        return new SqlFragment($sql,[]);
    }
}