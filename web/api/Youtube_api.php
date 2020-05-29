<?php

/*
=====================================================
 YouTube Searcher
-----------------------------------------------------
 Powered by VENTO
-----------------------------------------------------
 Target: Request YouTube API to list videos
=====================================================
*/

define("ROOT_DIR", dirname(__DIR__));

include(ROOT_DIR . "/managers/Mysql_manager.php");
include(ROOT_DIR . "/managers/Account_manager.php");
include(ROOT_DIR . "/renderer/Video_renderer.php");
include(ROOT_DIR . "/config/messages.php");

class Youtube_api
{
    private $search;
    private $page;
    private $json;
    private $db;
    private $videoRenderer;
    private $accountManager;

    public function __construct($search, $page = 1)
    {
        $this->search = strtolower($search);
        $this->page = $page;
        $this->db = new Mysql_manager();
        $this->videoRenderer = new Video_renderer();
        $this->accountManager = new Account_manager();
    }

    /**
     * Load page from database
     */
    public function load_from_cache()
    {
        $this->json = $this->db->get_search_results($this->search, $this->page);
        return !is_null($this->json);
    }

    /**
     * Get videos via YouTube API
     */
    public function send_request($nextPageToken = null)
    {
        //build params for request
        $params = array(
            "part" => "snippet",
            "maxResults" => 50,
            "q" => $this->search,
            "type" => "video",
            "fields" => "nextPageToken,pageInfo,items(id(videoId),snippet(publishedAt,title,thumbnails(medium(url))))",
            "key" => API_KEY
        );
        if (!is_null($nextPageToken)) {
            $params["pageToken"] = $nextPageToken;
        }

        //run request
        $ch = curl_init(API_URL . "?" . http_build_query($params, '', '&'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json"));
        $this->json = json_decode(curl_exec($ch));
        curl_close($ch);

        //check for errors
        if (array_key_exists("error", $this->json)) {
            $this->db->close_connection();
            //throw Exception with error message
            throw new Exception($this->json->error->message . " (error code: " . $this->json->error->code . ")");
        }

        //save page to database
        $this->db->save_search_results($this->search, $this->json, $this->page);
    }

    /**
     * Convert all videos to HTML
     */
    private function _convert_to_html()
    {
        //check for existence videos
        if (count($this->json->items) == 0) {
            $this->db->close_connection();
            //throw Exception with nothing found message
            throw new Exception(NOTHING_FOUND_MSG);
        }

        $render_html = "";

        foreach ($this->json->items as $item) {
            $title = $item->snippet->title;
            $video_id = $item->id->videoId;
            $preview = $item->snippet->thumbnails->medium->url;
            $publishedAt = $item->snippet->publishedAt;
            $isFavorite = false;
            if ($this->accountManager->is_logged_in()) {
                $isFavorite = $this->db->has_favorite_video($this->accountManager->get_user_id(), $video_id);
            }

            $render_html .= $this->videoRenderer->convert_video($title, $video_id, $preview, $publishedAt, $isFavorite);
        }

        $this->db->close_connection();

        return $render_html;
    }

    /**
     * Render videos
     */
    public function get_result()
    {
        die(json_encode(
            array(
                "html" => $this->_convert_to_html() . $this->videoRenderer->convert_navigation($this->page, $this->_get_total_pages(), $this->search)
            )
        ));
    }

    /**
     * Get token for next page
     */
    public function get_next_page_token()
    {
        return $this->json->nextPageToken;
    }

    /**
     * Get total pages amount
     */
    private function _get_total_pages()
    {
        return ceil($this->json->pageInfo->totalResults / $this->json->pageInfo->resultsPerPage);
    }
}