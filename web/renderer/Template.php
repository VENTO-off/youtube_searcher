<?php

/*
=====================================================
 YouTube Searcher
-----------------------------------------------------
 Powered by VENTO
-----------------------------------------------------
 Target: Manage template files
=====================================================
*/

/**
 * List of template files
 */
const main_tpl          = "/template/main.tpl";
const search_tpl        = "/template/search.tpl";
const cabinet_tpl       = "/template/cabinet.tpl";
const videos_tpl        = "/template/videos.tpl";
const video_tpl         = "/template/video.tpl";
const navigation_tpl    = "/template/navigation.tpl";
const footer_tpl        = "/template/footer.tpl";
const register_tpl      = "/template/register.tpl";
const login_tpl         = "/template/login.tpl";

class Template
{
    private $html;

    public function __construct($tpl_path)
    {
        $this->html = file_get_contents(dirname(__DIR__) . $tpl_path);
    }

    public function __toString()
    {
        return $this->html;
    }

    /**
     * Replace all variables with value in template
     */
    public function set($variable, $value)
    {
        $this->html = str_replace($variable, $value, $this->html);
    }

    /**
     * Remove all variables in template
     */
    public function remove($variable)
    {
        $this->html = str_replace($variable, "", $this->html);
    }
}