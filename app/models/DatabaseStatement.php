<?php


namespace app\models;


use app\router\Router;
use ReflectionClass;
use ReflectionException;

class DatabaseStatement
{
    public array $body;
    public int $commandsCounter;
    public string $fetchMethod;
    public function __construct()
    {
        $this->body = [];
        $this->commandsCounter = 0;
    }

    /**
     * @param Object $command
     */
    public function pushCommands(object $command): void
    {
            array_push($this->body, $command);
            $this->commandsCounter++;
    }
}