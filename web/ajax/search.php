<?php

/*
=====================================================
 YouTube Searcher
-----------------------------------------------------
 Powered by VENTO
-----------------------------------------------------
 Target: Search videos
=====================================================
*/

define("ROOT_DIR", dirname(__DIR__));

include(ROOT_DIR . "/config/config.php");
include(ROOT_DIR . "/api/Youtube_api.php");

/**
 * Get POST request with search string and page number
 */
if (isset($_POST['q']) and isset($_POST['page'])) {
    $q = $_POST['q'];
    $page = $_POST['page'];

    //check for empty values
    if (is_null($q) or is_null($page)) {
        return;
    }

    //check for numeric page
    if (!is_numeric($page)) {
        return;
    }
    //convert page to integer
    $page = (int)$page;
    //page must be positive
    if ($page <= 0) {
        return;
    }

    try {
        $api = new Youtube_api($q, $page);
        //if page not in cache
        if (!$api->load_from_cache()) {

            //get nextPageToken from previous page
            $nextPageToken = "";
            try {
                $tokenApi = new Youtube_api($q, $page - 1);
                $tokenApi->load_from_cache();
                $nextPageToken = $tokenApi->get_next_page_token();
            } catch (Exception $ex) {
                sendError($ex);
            }

            //load new page using API
            $api->send_request($nextPageToken);
        }
        //render videos
        $api->get_result();
    } catch (Exception $e) {
        sendError($e);
    }
/**
 * Get POST request with search string
 */
} else if (isset($_POST['q'])) {
    $q = $_POST['q'];

    //check for empty value
    if (is_null($q)) {
        return;
    }

    try {
        $api = new Youtube_api($q);
        //if not in cache
        if (!$api->load_from_cache()) {
            //load using API
            $api->send_request();
        }
        //render videos
        $api->get_result();
    } catch (Exception $e) {
        sendError($e);
    }
}

/**
 * Send error message to user
 */
function sendError($e)
{
    die(json_encode(
        array(
            "error" => $e->getMessage()
        )
    ));
}