<?php


namespace app\models;


use app\exceptions\DatabaseLayerException;
use app\models\DatabaseStatement as DatabaseStatement;
use PDO;
use PDOException;

class DatabaseLayerCore
{

    /**
     * @var array $credentials
     */
    protected array $credentials;

    /**
     * @return array
     */
    public function getCredentials()
    {
        return $this->credentials;
    }

    /**
     * @var PDO $connection
     */
    protected PDO $connection;

    /**
     * @var DatabaseStatement $statement
     */
    protected DatabaseStatement $statement;

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
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @param array $pdoSettings
     */
    protected function addSettings(array $pdoSettings)
    {
        foreach ($pdoSettings as $key => $setting) {
            if (key_exists($key, $this->settings)) {
                $this->settings[$key] = $setting;
            } else {
                array_push($this->settings, $setting);
            }
        }
    }

    /**
     * @param $credentials
     *
     * @return array
     * @throws DatabaseLayerException
     */
    protected function initCredentials($credentials): array
    {
        /**
         * @var $host
         * @var $database
         * @var $user
         * @var $password
         */
        extract($credentials);
        if (!isset($host, $database, $user, $password)) {
            throw new DatabaseLayerException("Credentials variable names must be set in correct format");
        }
        try {
            $this->connection = new PDO(
                "mysql:host=$host;dbname=$database",
                $user,
                $password,
                $this->settings
            );
        } catch (PDOException $exception) {
            throw new DatabaseLayerException("Invalid database credentials or settings");
        }
        return ["database" => $database, "host" => $host, "password" => $password, "user" => $user];
    }

    /**
     * @return bool
     * @throws DatabaseLayerException
     */
    protected function connect(): bool
    {
        /**
         * @var string $host
         * @var string $database
         * @var string $user
         * @var string $password
         */
        extract($this->credentials);
        try {
            $this->connection = new PDO(
                "mysql:host=$host;dbname=$database",
                $user,
                $password,
                $this->settings
            );
        } catch (PDOException $exception) {
            throw new DatabaseLayerException("Invalid database credentials");
        }
        return true;
    }
}