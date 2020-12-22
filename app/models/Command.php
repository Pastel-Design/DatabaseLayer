<?php


namespace app\models;


use app\models\SqlFragment as SqlFragment;

abstract class Command
{
    /**
     * @var SqlFragment
     */
    public SqlFragment $sqlFragment;
    /**
     * @return SqlFragment
     */
    public abstract function generateSql();
}