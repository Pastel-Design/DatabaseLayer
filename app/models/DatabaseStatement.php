<?php


namespace app\models;


use app\exceptions\DatabaseLayerException;
use app\router\Router;
use PDO;
use ReflectionClass;
use ReflectionException;

class DatabaseStatement
{
    public array $body;
    public int $commandsCounter;
    public string $fetchMethod;
    public int $fetchMode = PDO::FETCH_BOTH;

    /**
     * DatabaseStatement constructor.
     */
    public function __construct()
    {
        $this->body = [];
        $this->commandsCounter = 0;
        $this->fetchMethod="fetch";
    }

    /**
     * @param Object $command
     */
    public function pushCommands(object $command): void
    {
        array_push($this->body, $command);
        $this->commandsCounter++;
    }

    /**
     * @param $fetchMethod
     *
     * @throws DatabaseLayerException
     */
    public function setFetchMethod($fetchMethod)
    {
        if (is_numeric($fetchMethod)) {
            switch ($fetchMethod) {
                case 1:
                    $this->fetchMethod = "fetch";
                    break;
                case 2:
                    $this->fetchMethod = "fetchAll";
                    break;
                default:
                    throw new DatabaseLayerException("Passed invalid fetch style");
            }
        } else {
            switch ($fetchMethod) {
                case "fetch":
                    $this->fetchMethod = "fetch";
                    break;
                case "fetchAll":
                    $this->fetchMethod = "fetchAll";
                    break;
                default:
                    throw new DatabaseLayerException("Passed invalid fetch style");
            }
        }
    }

    public function setFetchMode($fetchMode)
    {
        $this->fetchMode=$fetchMode;
    }
}