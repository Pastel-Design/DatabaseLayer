<?php


namespace app\models;


abstract class Command
{
    public SqlFragment $sqlFragment;
    public abstract function generateSql();
}