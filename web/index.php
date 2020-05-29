<?php

/*
=====================================================
 YouTube Searcher
-----------------------------------------------------
 Powered by VENTO
-----------------------------------------------------
 Target: Main page
=====================================================
*/

include("renderer/Page_renderer.php");

$renderer = new Page_renderer();

if (isset($_GET['favorites'])) {
    //render favorites page
    $renderer->init_favorites_page();
} else if (isset($_GET['register'])) {
    //render register page
    $renderer->init_register_page();
} else if (isset($_GET['login'])) {
    //render login page
    $renderer->init_login_page();
} else if (isset($_GET['logout'])) {
    //goto logout script
    header("Location: /ajax/logout.php");
} else {
    //render main page
    $renderer->init_main_page();
}

$renderer->render_html();
