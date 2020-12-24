<?php

namespace app\models;

use app\exceptions\DatabaseLayerException;
use app\models\DatabaseStatement as DatabaseStatement;
use app\models\SelectColumns as SelectColumns;
use app\models\SelectTables as SelectTables;
use app\models\Where as Where;
use app\config\DbConfig;
use app\router\Router;
use Exception;
use PDO;
use PDOException as PDOException;

/**
 * Manager DatabaseLayer
 *
 * @package app\models
 */
class DatabaseLayer extends DatabaseLayerCore
{
    /**
     * DatabaseLayer constructor.
     *
     * @param array $credentials
     * @param array $pdoSettings
     *
     * @throws DatabaseLayerException
     */
    public function __construct(array $credentials = [], array $pdoSettings = [])
    {
        if (!empty($credentials)) {
            if (!empty($pdoSettings)) {
                $this->addSettings($pdoSettings);
            }
            $this->credentials = $this->initCredentials($credentials);
            $this->connect();
            $this->statement = new DatabaseStatement;
        } else {
            if (isset($_ENV["credentials"])) {
                /**
                 * @var $host
                 * @var $database
                 * @var $user
                 * @var $password
                 */
                extract($_ENV["credentials"]);
                if (isset($_ENV["pdoSettings"])) {
                    if (!empty($_ENV["pdoSettings"])) {
                        $this->addSettings($_ENV["pdoSettings"]);
                    }
                }
                $this->credentials = $this->initCredentials($credentials);
                $this->connect();
                $this->statement = new DatabaseStatement;
            }
            else{
                throw new DatabaseLayerException("You must set credentials either in constructor or php environment");
            }
        }
    }

    /**
     * @param array $tables
     *
     * @return $this
     */
    public function selectTables(array $tables)
    {
        $this->statement->pushCommands(new SelectTables($tables));
        return $this;
    }

    /**
     * @param array $tables
     *
     * @param array $columns
     *
     * @return $this
     */
    public function selectColumns(array $tables, array $columns)
    {
        $this->statement->pushCommands(new SelectColumns($tables, $columns));
        return $this;
    }

    /**
     * @return $this
     * @throws DatabaseLayerException
     */
    public function distinct()
    {
        $lastCommand = end($this->statement->body);
        $lastCommandName = (array_reverse(explode("\\", get_class($lastCommand)))[0]);
        switch ($lastCommandName) {
            case "SelectColumns":
                $newCommand = new SelectColumnsDistinct($lastCommand->tables, $lastCommand->columns);
                array_pop($this->statement->body);
                $this->statement->pushCommands($newCommand);
                return $this;
            case "SelectTables":
                $newCommand = new SelectTablesDistinct($lastCommand->tables);
                array_pop($this->statement->body);
                $this->statement->pushCommands($newCommand);
                return $this;
            default:
                throw new DatabaseLayerException("Not function must be called only after where function");
        }
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

    /**
     * @return $this
     * @throws DatabaseLayerException
     */
    public function not()
    {
        $lastCommand = end($this->statement->body);
        $lastCommandName = (array_reverse(explode("\\", get_class($lastCommand)))[0]);
        if ($lastCommandName === "Where") {
            $newCommand = new NotWhere($lastCommand->column, $lastCommand->parameter, $lastCommand->operator);
            array_pop($this->statement->body);
            $this->statement->pushCommands($newCommand);
            return $this;
        } else {
            throw new DatabaseLayerException("Not function must be called only after where function");
        }
    }

    /**
     * @param string $column
     * @param string $parameter
     * @param string $operator
     *
     * @return $this
     */
    public function and(string $column, string $parameter, string $operator = "")
    {
        $this->statement->pushCommands(new AndWhere($column, $parameter, $operator));
        return $this;
    }

    /**
     * @param string $column
     * @param string $parameter
     * @param string $operator
     *
     * @return $this
     */
    public function or(string $column, string $parameter, string $operator = "")
    {
        $this->statement->pushCommands(new OrWhere($column, $parameter, $operator));
        return $this;
    }

    /**
     * @param string $table
     * @param string $parameter1
     * @param string $parameter2
     *
     * @return $this
     */
    public function join(string $table, string $parameter1, string $parameter2)
    {
        $this->statement->pushCommands(new Join($table, $parameter1, $parameter2));
        return $this;
    }

    /**
     * @param string $table
     * @param string $parameter1
     * @param string $parameter2
     *
     * @return $this
     */
    public function leftJoin(string $table, string $parameter1, string $parameter2)
    {
        $this->statement->pushCommands(new LeftJoin($table, $parameter1, $parameter2));
        return $this;
    }

    /**
     * @param string $table
     * @param string $parameter1
     * @param string $parameter2
     *
     * @return $this
     */
    public function rightJoin(string $table, string $parameter1, string $parameter2)
    {
        $this->statement->pushCommands(new RightJoin($table, $parameter1, $parameter2));
        return $this;
    }

    /**
     * @param string $table
     * @param string $parameter1
     * @param string $parameter2
     *
     * @return $this
     */
    public function outerJoin(string $table, string $parameter1, string $parameter2)
    {
        $this->statement->pushCommands(new OuterJoin($table, $parameter1, $parameter2));
        return $this;
    }

    /**
     * @param $fetchMethod
     *
     * @throws DatabaseLayerException
     */
    public function setFetchMethod($fetchMethod)
    {
        $this->statement->setFetchMethod($fetchMethod);
    }
    /**
     * @param $fetchMode
     *
     */
    public function setFetchMode($fetchMode)
    {
        $this->statement->setFetchMode($fetchMode);
    }

    /**
     * @return array|false|mixed
     */
    public function execute()
    {
        var_dump($this->statement->body);

        $preparedStmtBody = "";
        $preparedStmtVars = [];
        foreach ($this->statement->body as $command) {
            $SqlFragment = $command->generateSql();
            $preparedStmtBody .= $SqlFragment->getSql();
            $vars = $SqlFragment->getVars();
            foreach ($vars as $var) {
                array_push($preparedStmtVars, $var);
            }
        }
        $result = $this->connection->prepare($preparedStmtBody);
        $result->execute($preparedStmtVars);
        switch ($this->statement->fetchMethod) {
            case "fetch":
                return $result->fetch($this->statement->fetchMode);
            case "fetchAll":
                return $result->fetchAll($this->statement->fetchMode);
            default:
                return false;
        }
    }
}