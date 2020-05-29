<?php

/*
=====================================================
 YouTube Searcher
-----------------------------------------------------
 Powered by VENTO
-----------------------------------------------------
 Target: Manage MySQL database
=====================================================
*/

define("ROOT_DIR", dirname(__DIR__));

include(ROOT_DIR . "/config/database.php");

class Mysql_manager
{
    private $mysqli;

    /**
     * Open database connection
     */
    public function __construct()
    {
        $this->mysqli = new mysqli(HOST, USER, PASS, DB);
        if ($this->mysqli->connect_errno) {
            throw new Exception("Error connecting to MySQL: " . $this->mysqli->connect_error . " (error code: " . $this->mysqli->connect_errno . ")");
        }
    }

    /**
     * Close database connection
     */
    public function close_connection()
    {
        $this->mysqli->close();
    }

    /**
     * Load cached page from database
     */
    public function get_search_results($search, $page = 1)
    {
        $search = $this->mysqli->real_escape_string($search);
        $result = $this->mysqli->query("SELECT * FROM `" . CACHE_TABLE . "` WHERE `search` = '$search' AND `page` = '$page'");
        if (mysqli_num_rows($result) == 0) {
            return null;
        }

        $row = $result->fetch_assoc();
        return json_decode($row["json"]);
    }

    /**
     * Save page to database
     */
    public function save_search_results($search, $json, $page = 1)
    {
        $search = $this->mysqli->real_escape_string($search);
        $json = json_encode($json, JSON_UNESCAPED_UNICODE);
        $this->mysqli->query("INSERT INTO `" . CACHE_TABLE . "` (`search`, `json`, `page`) VALUES ('$search', '$json', '$page')");
    }

    /**
     * Check if user has a favorite video
     */
    public function has_favorite_video($user_id, $video_id)
    {
        $video_id = $this->mysqli->real_escape_string($video_id);
        $result = $this->mysqli->query("SELECT * FROM `" . FAVORITES_TABLE . "` WHERE `user_id` = '$user_id' AND `video_id` = '$video_id'");
        return mysqli_num_rows($result) >= 1;
    }

    /**
     * Add video to favorites
     */
    public function add_favorite_video($user_id, $video_id, $title, $preview, $date)
    {
        $video_id = $this->mysqli->real_escape_string($video_id);
        $this->mysqli->query("INSERT INTO `" . FAVORITES_TABLE . "` (`user_id`, `video_id`, `title`, `preview`, `date`) VALUES ('$user_id', '$video_id', '$title', '$preview', '$date')");
    }

    /**
     * Remove video from favorites
     */
    public function remove_favorite_video($user_id, $video_id)
    {
        $video_id = $this->mysqli->real_escape_string($video_id);
        $this->mysqli->query("DELETE FROM `" . FAVORITES_TABLE . "` WHERE `user_id` = '$user_id' AND `video_id` = '$video_id'");
    }

    /**
     * Get user's favorite videos
     */
    public function get_favorite_videos($user_id)
    {
        return $this->mysqli->query("SELECT * FROM `" . FAVORITES_TABLE . "` WHERE `user_id` = '$user_id'");
    }

    /**
     * Check if login registered
     */
    public function is_login_taken($login)
    {
        $result = $this->mysqli->query("SELECT * FROM `" . USERS_TABLE . "` WHERE `login` = '$login'");
        return mysqli_num_rows($result) >= 1;
    }

    /**
     * Register new user
     */
    public function register_user($login, $password)
    {
        $password = md5(md5($password));
        $this->mysqli->query("INSERT INTO `" . USERS_TABLE . "` (`login`, `password`) VALUES ('$login', '$password')");
    }

    /**
     * Auth user (validate login and password)
     */
    public function auth_user($login, $password)
    {
        $password = md5(md5($password));
        $result = $this->mysqli->query("SELECT * FROM `" . USERS_TABLE . "` WHERE `login` = '$login' AND `password` = '$password'");
        return mysqli_num_rows($result) >= 1;
    }

    /**
     * Get id from login
     */
    public function get_user_id($login)
    {
        $result = $this->mysqli->query("SELECT * FROM `" . USERS_TABLE . "` WHERE `login` = '$login'");
        if (mysqli_num_rows($result) == 0) {
            return null;
        }

        $row = $result->fetch_assoc();
        return $row["id"];
    }
}