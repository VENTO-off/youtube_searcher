<?php

/*
=====================================================
 YouTube Searcher
-----------------------------------------------------
 Powered by VENTO
-----------------------------------------------------
 Target: Render pages
=====================================================
*/

define("ROOT_DIR", dirname(__DIR__));

include(ROOT_DIR . "/managers/Mysql_manager.php");
include(ROOT_DIR . "/managers/Account_manager.php");
include(ROOT_DIR . "/renderer/Video_renderer.php");
include(ROOT_DIR . "/config/messages.php");
include(ROOT_DIR . "/renderer/Template.php");

class Page_renderer
{
    private $html;

    /**
     * Render main page
     */
    public function init_main_page()
    {
        $main_tpl = new Template(main_tpl);
        $search_tpl = new Template(search_tpl);
        $cabinet_tpl = new Template(cabinet_tpl);
        $videos_tpl = new Template(videos_tpl);
        $footer_tpl = new Template(footer_tpl);

        $search_tpl->set("{PLACEHOLDER_SEARCH}", SEARCH_PLACEHOLDER_GUI);

        $accountManager = new Account_manager();
        if ($accountManager->is_logged_in()) {
            $cabinet_tpl->set("{URL1}", "logout");
            $cabinet_tpl->set("{CAPTION1}", $accountManager->get_login() . " " . LOGOUT_GUI);
            $cabinet_tpl->set("{URL2}", "favorites");
            $cabinet_tpl->set("{CAPTION2}", FAVORITE_VIDEOS_GUI);
        } else {
            $cabinet_tpl->set("{URL1}", "login");
            $cabinet_tpl->set("{CAPTION1}", LOGIN_BUTTON_GUI);
            $cabinet_tpl->set("{URL2}", "register");
            $cabinet_tpl->set("{CAPTION2}", REGISTER_BUTTON_GUI);
        }

        $videos_tpl->remove("{VIDEOS_LIST}");

        $main_tpl->set("{SEARCH_BAR}", $search_tpl);
        $main_tpl->set("{CABINET}", $cabinet_tpl);
        $main_tpl->set("{VIDEOS}", $videos_tpl);
        $main_tpl->set("{FOOTER}", $footer_tpl);

        $this->html = $main_tpl;
    }

    /**
     * Render favorites page
     */
    public function init_favorites_page()
    {
        $main_tpl = new Template(main_tpl);
        $cabinet_tpl = new Template(cabinet_tpl);
        $videos_tpl = new Template(videos_tpl);
        $footer_tpl = new Template(footer_tpl);

        $accountManager = new Account_manager();
        if ($accountManager->is_logged_in()) {
            $cabinet_tpl->set("{URL1}", "logout");
            $cabinet_tpl->set("{CAPTION1}", $accountManager->get_login() . " " . LOGOUT_GUI);
            $cabinet_tpl->remove("{URL2}");
            $cabinet_tpl->set("{CAPTION2}", SEARCH_VIDEOS_GUI);
        } else {
            //if user not logged in -> redirect
            $this->_redirect_to_homepage();
        }

        $render_html = "";
        $db = new Mysql_manager();
        $videoRenderer = new Video_renderer();

        //load favorite videos
        $favoriteVideos = $db->get_favorite_videos($accountManager->get_user_id());
        while ($row = $favoriteVideos->fetch_assoc()) {
            $title = $row["title"];
            $video_id = $row["video_id"];
            $preview = $row["preview"];
            $publishedAt = $row["date"];
            $isFavorite = true;

            $render_html .= $videoRenderer->convert_video($title, $video_id, $preview, $publishedAt, $isFavorite);
        }
        $db->close_connection();

        $videos_tpl->set("{VIDEOS_LIST}", $render_html);

        $main_tpl->remove("{SEARCH_BAR}");
        $main_tpl->set("{CABINET}", $cabinet_tpl);
        $main_tpl->set("{VIDEOS}", $videos_tpl);
        $main_tpl->set("{FOOTER}", $footer_tpl);

        $this->html = $main_tpl;
    }

    /**
     * Render register page
     */
    public function init_register_page()
    {
        $main_tpl = new Template(main_tpl);
        $cabinet_tpl = new Template(cabinet_tpl);
        $register_tpl = new Template(register_tpl);
        $footer_tpl = new Template(footer_tpl);

        $accountManager = new Account_manager();
        if ($accountManager->is_logged_in()) {
            //if user logged in -> redirect
            $this->_redirect_to_homepage();
        } else {
            $cabinet_tpl->set("{URL1}", "login");
            $cabinet_tpl->set("{CAPTION1}", LOGIN_BUTTON_GUI);
            $cabinet_tpl->remove("{URL2}");
            $cabinet_tpl->set("{CAPTION2}", SEARCH_VIDEOS_GUI);
        }

        $register_tpl->set("{REGISTER_TITLE}", REGISTER_GUI);
        $register_tpl->set("{PLACEHOLDER_LOGIN}", LOGIN_PLACEHOLDER_GUI);
        $register_tpl->set("{PLACEHOLDER_PASSWORD}", PASSWORD_PLACEHOLDER_GUI);
        $register_tpl->set("{REGISTER_BUTTON}", REGISTER_BUTTON_GUI);

        $main_tpl->remove("{SEARCH_BAR}");
        $main_tpl->set("{CABINET}", $cabinet_tpl);
        $main_tpl->set("{VIDEOS}", $register_tpl);
        $main_tpl->set("{FOOTER}", $footer_tpl);

        $this->html = $main_tpl;
    }

    /**
     * Render login page
     */
    public function init_login_page()
    {
        $main_tpl = new Template(main_tpl);
        $cabinet_tpl = new Template(cabinet_tpl);
        $login_tpl = new Template(login_tpl);
        $footer_tpl = new Template(footer_tpl);

        $accountManager = new Account_manager();
        if ($accountManager->is_logged_in()) {
            //if user logged in -> redirect
            $this->_redirect_to_homepage();
        } else {
            $cabinet_tpl->set("{URL1}", "register");
            $cabinet_tpl->set("{CAPTION1}", REGISTER_BUTTON_GUI);
            $cabinet_tpl->remove("{URL2}");
            $cabinet_tpl->set("{CAPTION2}", SEARCH_VIDEOS_GUI);
        }

        $login_tpl->set("{LOGIN_TITLE}", LOGIN_GUI);
        $login_tpl->set("{PLACEHOLDER_LOGIN}", LOGIN_PLACEHOLDER_GUI);
        $login_tpl->set("{PLACEHOLDER_PASSWORD}", PASSWORD_PLACEHOLDER_GUI);
        $login_tpl->set("{LOGIN_BUTTON}", LOGIN_BUTTON_GUI);

        $main_tpl->remove("{SEARCH_BAR}");
        $main_tpl->set("{CABINET}", $cabinet_tpl);
        $main_tpl->set("{VIDEOS}", $login_tpl);
        $main_tpl->set("{FOOTER}", $footer_tpl);

        $this->html = $main_tpl;
    }

    /**
     * Redirect user to main page
     */
    private function _redirect_to_homepage()
    {
        header("Location: /");
        die();
    }

    /**
     * Render HTML
     */
    public function render_html()
    {
        die($this->html);
    }
}