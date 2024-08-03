<?php

namespace Core;

abstract class Database
{
    protected $pdo;
 
    abstract public function connect();

    public function query($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}
