<?php

namespace Core;

use PDO;

class SecurityDatabase
{
    private $database;

    public function __construct(MysqlDatabase $database)
    {
        $this->database = $database;
    }
   
        public function getUser($login) {

            $query = "SELECT * FROM users s join role r on u.idrole=r.id WHERE login = :login";
            return $this->database->query($query,["login" => $login], PDO::FETCH_CLASS, "userEntity");
        }
    
    
    
    public function isLogged()
    {
        return Session::get('user') !== null;
    }

    public function getUserLogged()
    {
        return Session::get('user');
    }

    public function getRoles($userId)
    {
        $sql = "SELECT r.libelle 
                FROM role r 
                JOIN utilisateur u ON r.id = u.idrole 
                WHERE u.id = :userId";
        $params = ['userId' => $userId];
        return $this->database->query($sql, $params, PDO::FETCH_ASSOC);
    }
}
