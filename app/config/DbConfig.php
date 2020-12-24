<?php

namespace app\config;

use PDO;

/**
 * Config DbConfig
 *
 * @package app\config
 */
class DbConfig
{
    public static array $credentials = [
        "host" => '127.0.0.1',
        "user" => 'root',
        "password" => '',
        "database" => 'mydb'
    ];
    public static array $settings = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_EMULATE_PREPARES => false
    ];
}