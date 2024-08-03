<?php

namespace Core;

use Core\MysqlDatabase;
use PDO;
use ReflectionClass;

class Model
{
    protected $table;
    protected $database;
    protected $joined=[];
    protected $filters = [];
    protected $clauses = [];

    public function __construct(MysqlDatabase $database)
    {
        $this->database = $database;
        $this->table = $this->getTableName();
    }

    protected function getTableName()
    {
        $class = (new ReflectionClass($this))->getShortName();
        return strtolower(str_replace('Model', '', $class) . 's');
    }

    public function getEntityClass()
    {
        $class = (new ReflectionClass($this))->getShortName();
        $entityClass = "Entity\\" . str_replace('Model', 'Entity', $class);

        if (!class_exists($entityClass)) {
            throw new \Exception("Entity class $entityClass not found.");
        }

        return $entityClass;
    }

    public function query($sql, $params = [], $entityClass = null)
    {
        if ($entityClass) {
            return $this->database->query($sql, $params, PDO::FETCH_CLASS, $entityClass);
        }
        return $this->database->query($sql, $params);
    }

    public function all()
    {
        $entityClass = $this->getEntityClass();
        $sql="SELECT * FROM {$this->table} where ";
        foreach ($this->clauses as $clause) {
            $sql .= "  $clause";
        }
      if(!$this->clauses){
        $sql.= " 1=1 ";
      }
        $params = array_merge($this->filters);
        return $this->query($sql, $params, $entityClass);
    }

    public function prepare($sql)
    {
        return $this->database->prepare($sql);
    }

    public function searchByAttribute($attribute, $value)
    {
        $entityClass = $this->getEntityClass();
        return $this->query("SELECT * FROM {$this->table} WHERE $attribute = :value", ['value' => $value], $entityClass);
    }

    public function save($data)
    {
        $id = $data['id'] ?? null;
        if ($id) {
            return $this->update($id, $data);
        } else {
            return $this->insert($data);
        }
    }

    protected function insert($data)
    {
        $columns = implode(", ", array_keys($data));
        $values = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($values)";
        return $this->query($sql, $data);
    }

    protected function update($id, $data)
    {
        $columns = '';
        foreach ($data as $key => $value) {
            $columns .= "$key = :$key, ";
        }
        $columns = rtrim($columns, ', ');
        $sql = "UPDATE {$this->table} SET $columns WHERE id = :id";
        $data['id'] = $id;
        return $this->query($sql, $data);
    }

    public function delete($id)
    {
        return $this->query("DELETE FROM {$this->table} WHERE id = :id", ['id' => $id]);
    }

    protected function instantiateClass($className)
    {
        try {
            $reflectionClass = new \ReflectionClass($className);
            return $reflectionClass->newInstance($this->database);
        } catch (\ReflectionException $e) {
            throw new \Exception("Class $className not found or not instantiable");
        }
    }

    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    public function setClauses($clauses)
    {
        $this->clauses = $clauses;
    }

    public function hasMany($entityClass, $foreignKey, $localKey, $offset = null, $limit = null)
    {
        $entity = $this->instantiateClass($entityClass);
        $table = $entity->getTableName();
        $entityClass = $entity->getEntityClass();

        $sql = "SELECT * FROM $table WHERE $foreignKey = :localKey";
        
        foreach ($this->joined as $join) {
            $sql .= " $join";
        }

        foreach ($this->clauses as $clause) {
            $sql .= " AND $clause";
        }
        
        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit;
        }
        if ($offset !== null) {
            $sql .= " OFFSET " . (int)$offset;
        }
        $params = array_merge(['localKey' => $localKey], $this->filters);

        return $this->query($sql, $params, $entityClass);

    }

    public function belongsTo($entityClass, $foreignKey, $localKey)
    {
        $entity = $this->instantiateClass($entityClass);
        $table = $entity->getTableName();
        $entityClass = $entity->getEntityClass();
        $sql = "SELECT * FROM $table WHERE $foreignKey = :localKey ";
        foreach ($this->clauses as $clause) {
            $sql .= " AND $clause";
        }
      
        $params = array_merge(['localKey' => $localKey], $this->filters);


        return $this->query($sql,$params, $entityClass);
    }

    public function belongsToMany($entityClass, $foreignKey, $localKey, $pivotTable, $foreignKey2, $offset = null, $limit = null)
    {
        $entity = $this->instantiateClass($entityClass);
        $table = $entity->getTableName();
        $entityClass = $entity->getEntityClass();
        $pivotEntity = $this->instantiateClass($pivotTable);
        $pivotTable = $pivotEntity->getTableName();

        $sql = "SELECT $table.* FROM $table INNER JOIN $pivotTable ON $pivotTable.{$foreignKey} = $table.id ";

        foreach ($this->joined as $join) {
            $sql .= " $join";
        }

        $sql.=" WHERE $pivotTable.$foreignKey2 = :localKey";

        foreach ($this->clauses as $clause) {
            $sql .= " AND $clause";
        }

        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit;
        }
        if ($offset !== null) {
            $sql .= " OFFSET " . (int)$offset;
        }

        $params = array_merge(['localKey' => $localKey], $this->filters);
        return $this->query($sql, $params, $entityClass);
    }

    public function lastInsertId()
    {
        return $this->database->lastInsertId();
    }

    public function transaction($callback)
    {
        try {
            $this->database->beginTransaction();
            $callback($this);
            $this->database->commit();
        } catch (\Exception $e) {
            $this->database->rollBack();
            throw $e;
        }
    }

    // public function filterAndPaginate($filter, $id, $offset = null, $pageSize = null)
    // {
    //     $sql = "SELECT * FROM $this->table WHERE idclient = :idclient";
    //     if ($filter == "unpaid") {
    //         $sql .= " AND montant > montantverse";
    //     } elseif ($filter == "paid") {
    //         $sql .= " AND montant = montantverse";
    //     }

    //     if ($offset !== null && $pageSize !== null) {
    //         $sql .= " LIMIT :offset, :pageSize";
    //     }

    //     $params = ['idclient' => $id];
    //     if ($offset !== null && $pageSize !== null) {
    //         $params['offset'] = intval($offset);
    //         $params['pageSize'] = intval($pageSize);
    //     }

    //     return $this->query($sql, $params, $this->getEntityClass());
    // }
}
