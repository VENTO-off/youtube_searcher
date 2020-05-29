<?php

/*
=====================================================
 YouTube Searcher
-----------------------------------------------------
 Powered by VENTO
-----------------------------------------------------
 Target: Render videos
=====================================================
*/

define("ROOT_DIR", dirname(__DIR__));

include(ROOT_DIR . "/renderer/Template.php");
include(ROOT_DIR . "/config/messages.php");
include(ROOT_DIR . "/config/config.php");

class Video_renderer
{
    private $video_tpl;
    private $navigation_tpl;

    public function __construct()
    {
        $this->video_tpl = new Template(video_tpl);
        $this->navigation_tpl = new Template(navigation_tpl);
    }

    /**
     * Convert video to HTML
     */
    public function convert_video($title, $video_id, $preview, $publishedAt, $isFavorite)
    {
        $videoHTML = clone $this->video_tpl;

        $favoriteRequest = array("title" => $title, "video_id" => $video_id, "preview" => $preview, "date" => $publishedAt);
        $favoriteRequest = base64_encode(json_encode($favoriteRequest));

        $videoHTML->set("{IMAGE_URL}", $preview);
        $videoHTML->set("{TITLE}", $title);
        $videoHTML->set("{VIDEO_ID}", $video_id);
        $videoHTML->set("{SELECTED}", ($isFavorite ? "selected" : ""));
        $videoHTML->set("{FAVORITE_REQUEST}", $favoriteRequest);
        $videoHTML->set("{PUBLISHED_AT}", PUBLISHED_AT_GUI);

        try {
            $date = new DateTime($publishedAt);
            $videoHTML->set("{DATE}", $date->format(DATE_FORMAT));
        } catch (Exception $e) {
            $videoHTML->set("{DATE}", $publishedAt);
        }

        return $videoHTML;
    }

    /**
     * Convert navigation buttons to HTML
     */
    public function convert_navigation($page, $totalPages, $search)
    {
        $navigationHTML = clone $this->navigation_tpl;

        $navigationHTML->set("{HIDE_PREV}", ($page == 1 ? "hide" : ""));
        $navigationHTML->set("{HIDE_NEXT}", ($page >= $totalPages ? "hide" : ""));
        $navigationHTML->set("{PAGE}", $page . " / " . $totalPages);
        $navigationHTML->set("{SEARCH}", $search);
        $navigationHTML->set("{PREV_PAGE}", $page - 1);
        $navigationHTML->set("{NEXT_PAGE}", $page + 1);

        return $navigationHTML;
    }
}