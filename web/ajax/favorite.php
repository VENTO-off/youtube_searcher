<?php

/*
=====================================================
 YouTube Searcher
-----------------------------------------------------
 Powered by VENTO
-----------------------------------------------------
 Target: Manage favorite videos
=====================================================
*/

define("ROOT_DIR", dirname(__DIR__));

include(ROOT_DIR . "/config/config.php");
include(ROOT_DIR . "/managers/Mysql_manager.php");
include(ROOT_DIR . "/managers/Account_manager.php");
include(ROOT_DIR . "/config/messages.php");

/**
 * Get POST request to add/remove video from favorites
 */
if (isset($_POST['video'])) {
    $video = $_POST['video'];
    if (is_null($video)) {
        return;
    }

    //convert data from base64 to json
    $json = json_decode(base64_decode($video, true));

    //check if user logged in
    $accountManager = new Account_manager();
    if (!$accountManager->is_logged_in()) {
        sendMessage(ONLY_AUTH_MSG, true);
        return;
    }

    try {
        $db = new Mysql_manager();
        if (!$db->has_favorite_video($accountManager->get_user_id(), $json->video_id)) {
            //add video to favorites
            $db->add_favorite_video($accountManager->get_user_id(), $json->video_id, $json->title, $json->preview, $json->date);
            //notify user
            sendMessage(ADDED_FAVORITES_MSG);
        } else {
            //remove video from favorites
            $db->remove_favorite_video($accountManager->get_user_id(), $json->video_id);
            //notify user
            sendMessage(REMOVED_FAVORITES_MSG);
        }
    } catch (Exception $e) {
        sendMessage($e->getMessage(), true);
    }
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