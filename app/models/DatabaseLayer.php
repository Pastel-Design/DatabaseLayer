<?php

namespace app\models;

use app\models\DatabaseStatement as DatabaseStatement;
use app\models\SelectColumns as SelectColumns;
use app\models\SelectTable as SelectTable;
use app\config\DbConfig;
use app\router\Router;
use PDO;
use PDOException as PDOException;
use ReflectionException;

/**
 * Manager DatabaseLayer
 *
 * @package app\models
 */
class DatabaseLayer
{
    public function __construct()
    {
        $credentials = $this->initCredentials();
        $this->connect($credentials);
        $this->statement = new DatabaseStatement;
    }

    /**
     * @var PDO $connection
     */
    public PDO $connection;

    /**
     * @var DatabaseStatement $statement
     */
    public DatabaseStatement $statement;

    /**
     * @var array $settings
     */
    protected array $settings = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    /**
     * @return array
     */
    protected function initCredentials(): array
    {
        return ["database" => DbConfig::$database, "host" => DbConfig::$host, "password" => DbConfig::$pass, "user" => DbConfig::$username];
    }

    /**
     * @param $credentials
     *
     * @return bool
     */
    protected function connect($credentials): bool
    {
        /**
         * @var string $host
         * @var string $database
         * @var string $user
         * @var string $password
         */
        extract($credentials);
        try {
            $this->connection = new PDO(
                "mysql:host=$host;dbname=$database",
                $user,
                $password,
                $this->settings
            );
        } catch (PDOException $exception) {
            return false;
        }
        return true;
    }

    /**
     * @param $tableName
     *
     * @return $this
     */
    public function selectTable(string $tableName)
    {
        $this->statement->pushCommands(new SelectTable($tableName));
        return $this;
    }

    /**
     * @param string $tableName
     *
     * @param array  $columns
     *
     * @return $this
     */
    public function selectColumns(string $tableName, array $columns)
    {
        $this->statement->pushCommands(new SelectColumns($tableName, $columns));
        return $this;
    }

    /**
     * @param string $column
     * @param string $parameter
     * @param string $operator
     *
     * @return $this
     */
    public function where(string $column, string $parameter, string $operator = "")
    {
        $this->statement->pushCommands(new Where($column, $parameter, $operator));
        return $this;
    }

    public function execute()
    {
        var_dump($this->statement->body);
        /*
        $preparedStmtBody = "";
        $preparedStmtVars = [];
        foreach ($this->statement->body as $command) {
            $SqlFragment = $command->generateSql();
            $preparedStmtBody .= $SqlFragment->render();
            $vars = $SqlFragment->getVars();
            foreach ($vars as $var){
                array_push($preparedStmtVars, $var);
            }
        }
        $result = $this->connection->prepare($preparedStmtBody);
        $result->execute($preparedStmtVars);
        return $result->fetchAll();
        */
    }
}