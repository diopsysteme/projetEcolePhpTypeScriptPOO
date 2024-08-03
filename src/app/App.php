<?php

namespace App;

use Core\Container;
use Core\ServiceProvider;
use Core\MysqlDatabase;

class App
{
    private static $instance = null;
    private $database;
    private $container;
    private function __construct()
    {
        $this->database = $this->getDatabase();
        $this->container = new Container();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new App();
        }
        return self::$instance;
    }

    public function getDatabase()
    {
        if ($this->database === null) {
            $host = $_ENV["DB_HOST"];
            $dbname = $_ENV["DB_NAME"];
            $username = $_ENV["DB_USER"];
            $password = $_ENV["DB_PASSWORD"];
            $port = $_ENV["DB_PORT"];
            $sgbd = $_ENV["DB_CONNECTION"];

            $dsn = "$sgbd:host=$host;port=$port;dbname=$dbname;charset=utf8";
            $this->database = new MysqlDatabase($dsn, $username, $password);
        }
        return $this->database;
    }

    public function getModel($model)
    {
        $class = "Model\\" . ucfirst($model) . "Model";

        try {
            $reflectionClass = new \ReflectionClass($class);
            if ($reflectionClass->isInstantiable()) {
                return $reflectionClass->newInstance($this->database);
            } else {
                throw new \Exception("Class $class is not instantiable.");
            }
        } catch (\ReflectionException $e) {
            throw new \Exception("Model $model not found: " . $e->getMessage());
        }
    }
    public function getContainer()
    {
        return $this->container;
    }

    public function initialize($configFile)
    {
        ServiceProvider::register($this->container, $configFile);
    }











}
