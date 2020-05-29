<?php

/*
=====================================================
 YouTube Searcher
-----------------------------------------------------
 Powered by VENTO
-----------------------------------------------------
 Target: Logout user
=====================================================
*/

define("ROOT_DIR", dirname(__DIR__));

include(ROOT_DIR . "/managers/Account_manager.php");

/**
 * Logout user
 */
$accountManager = new Account_manager();
if ($accountManager->is_logged_in()) {
    $accountManager->logout();
}

//redirect to main page
header("Location: /");
die();