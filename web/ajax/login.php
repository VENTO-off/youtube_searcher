<?php

/*
=====================================================
 YouTube Searcher
-----------------------------------------------------
 Powered by VENTO
-----------------------------------------------------
 Target: Login user
=====================================================
*/

define("ROOT_DIR", dirname(__DIR__));

include(ROOT_DIR . "/config/config.php");
include(ROOT_DIR . "/config/messages.php");
include(ROOT_DIR . "/managers/Mysql_manager.php");
include(ROOT_DIR . "/managers/Account_manager.php");

/**
 * Get POST request with login and password
 */
if (isset($_POST['login']) and isset($_POST['password'])) {
    $login = $_POST['login'];
    $password = $_POST['password'];

    //check for empty values
    if (is_null($login) or is_null($password)) {
        return;
    }

    $db = new Mysql_manager();

    //validate login and password
    if (!$db->auth_user($login, $password)) {
        //send message if login/password incorrect
        sendMessage(LOGIN_WRONG_MSG, true);
        $db->close_connection();
        return;
    }

    //auth user
    $accountManager = new Account_manager();
    $accountManager->auth($db->get_user_id($login), $login);
    $db->close_connection();

    //send message
    sendMessage(LOGIN_SUCCESS_MSG);
}

/**
 * Send message to user
 */
function sendMessage($message, $isError = false)
{
    die(json_encode(
        array(
            "error" => $isError,
            "message" => $message
        )
    ));
}