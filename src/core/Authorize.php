<?php

namespace Core;

class Authorize
{
    private $session;

    public function setSesion($session)
    {
        $this->session = $session;
    }

    public function saveUser($user)
    {
        $this->session::set('user', $user);
    }

    public function getUserLogged()
    {
        return $this->session::get('user');
    }

    public function isLogged()
    {
        return $this->session::get('user');
    }

    public function hasRole($role)
    {
        $user = $this->getUserLogged();
        return $user && in_array($role, $user['roles']);
    }
}
