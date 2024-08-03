<?php

namespace Core;

class Session
{
    public static function start()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value,$key2=null)
    {
        if($key2!=null){
            $_SESSION[$key][$key2] = $value;
        }else{
            $_SESSION[$key] = $value;
        }
    }

    public static function get($key,$key2=null)
    {
        if($key2!=null){
            return isset($_SESSION[$key][$key2])? $_SESSION[$key][$key2] : null;
        }
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public static function destroy()
    {
        session_destroy();
    }
    public static function unset($key,$key1=null){
        if($key1!=null){
            unset($_SESSION[$key][$key1]);
        }else{
            unset($_SESSION[$key]);
        }
    }
}
