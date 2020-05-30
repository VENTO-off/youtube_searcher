<?php

/*
=====================================================
 YouTube Searcher
-----------------------------------------------------
 Powered by VENTO
-----------------------------------------------------
 Target: Register user
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
if (isset($_POST['login']) and isset($_POST['password']) and isset($_POST['password2'])) {
    $login = $_POST['login'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];

    //check for empty values
    if (is_null($login) or is_null($password) or is_null($password2)) {
        return;
    }

    //check for letters and numbers
    if (!preg_match("/^[a-zA-Z0-9]+$/", $login)) {
        //send message
        sendMessage(REGISTER_LOGIN_REGEX_MSG, true);
        return;
    }

    //check for allowed length of login
    if (strlen($login) < LOGIN_MIN or strlen($login) > LOGIN_MAX) {
        //send message
        sendMessage(REGISTER_LOGIN_LENGTH_MSG, true);
        return;
    }

    //check for allowed length of password
    if (strlen($password) < PASSWORD_MIN or strlen($password) > PASSWORD_MAX) {
        sendMessage(REGISTER_PASS_LENGTH_MSG, true);
        return;
    }

    //check for equal passwords
    if (strcmp($password, $password2) !== 0) {
        //send message
        sendMessage(REGISTER_PASSWORDS_MSG, true);
        return;
    }

    $db = new Mysql_manager();

    //check for login occupation
    if ($db->is_login_taken($login)) {
        //send message
        sendMessage(REGISTER_LOGIN_TAKEN_MSG, true);
        $db->close_connection();
        return;
    }

    //register user
    $db->register_user($login, $password);
    //auth user
    $accountManager = new Account_manager();
    $accountManager->auth($db->get_user_id($login), $login);
    $db->close_connection();

    //send message
    sendMessage(REGISTER_SUCCESS_MSG);
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