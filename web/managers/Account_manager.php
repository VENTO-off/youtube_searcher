<?php

/*
=====================================================
 YouTube Searcher
-----------------------------------------------------
 Powered by VENTO
-----------------------------------------------------
 Target: Manager user's session
=====================================================
*/

define("ROOT_DIR", dirname(__DIR__));

include(ROOT_DIR . "/managers/Mysql_manager.php");

class Account_manager
{
    private $user_id;
    private $login;

    public function __construct()
    {
        session_start();
    }

    /**
     * Check if user logged
     */
    public function is_logged_in()
    {
        if (isset($_SESSION['user_id']) and isset($_SESSION['login'])) {
            $this->user_id = $_SESSION['user_id'];
            $this->login = $_SESSION['login'];
            return true;
        }

        return false;
    }

    /**
     * Get user id
     */
    public function get_user_id()
    {
        return $this->user_id;
    }

    /**
     * Get user login
     */
    public function get_login()
    {
        return $this->login;
    }

    /**
     * Auth user
     */
    public function auth($user_id, $login)
    {
        $_SESSION['user_id'] = $user_id;
        $this->user_id = $user_id;
        $_SESSION['login'] = $login;
        $this->login = $login;
    }

    /**
     * Logout user
     */
    public function logout()
    {
        session_destroy();
    }
}