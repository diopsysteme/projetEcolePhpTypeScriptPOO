<?php

namespace Model;

use Core\MysqlDatabase;
use Entity\ClientEntity;
use Core\Model;
use PDO;
class UserModel extends Model
{
    public function getUser($login) {
        $query = "SELECT * FROM utilisateurs  WHERE login = :login";
        return $this->database->query($query,["login" => $login], PDO::FETCH_CLASS, $this->getEntityClass());
    }
    public function conn($username, $password) {
        $user=$this->getUser($username,)[0];
        // var_dump($user);
        if($user&&password_verify($password, $user->password)){
                return $user;
        }
        return false;
    }
}
