<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Event extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }


    function index()
    {
        $id_lang = id_lang();
        if ($data["seo_title"] == "") {
            $data["seo_title"] = "MJM | Partner for Quality";
        }

        $data["meta_description"] = preg_replace(
            "/<[^>]*>/",
            "",
            $data["meta_description"]
        );

        $data["active_event"] = "active";

        render("event", $data);
    }

    function detail($uri_path)
    {
        if ($data["seo_title"] == "") {
            $data["seo_title"] = "MJM | Partner for Quality";
        }

        $data["meta_description"] = preg_replace(
            "/<[^>]*>/",
            "",
            $data["meta_description"]
        );

        $data["active_event"] = "active";

        render("event_detail", $data);
    }

}
