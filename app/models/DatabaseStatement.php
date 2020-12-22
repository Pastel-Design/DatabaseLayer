<?php


namespace app\models;


use app\exceptions\DatabaseLayerException;
use app\router\Router;
use ReflectionClass;
use ReflectionException;

class DatabaseStatement
{
    public array $body;
    public int $commandsCounter;
    public string $fetchMethod;

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
                    break;
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
                    break;
            }
        }
    }
}