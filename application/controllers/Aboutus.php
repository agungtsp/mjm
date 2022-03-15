<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Aboutus extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }


    function index()
    {
        $id_lang = id_lang();
        $this->load->model('pagesmodel');
        $filter_pages['id_lang'] = $id_lang;
        $data = $this->pagesmodel->findBy($filter_pages);	
        foreach ($data as $key => $value) {
            $data['img'] = getImgLink($value['img'], 'large');
            $data['description'] = $value['description'];
            $data['sub_title'] = $value['sub_title'];
        }
        if ($data["seo_title"] == "") {
            $data["seo_title"] = "MJM | Partner for Quality";
        }

        $data["meta_description"] = preg_replace(
            "/<[^>]*>/",
            "",
            $data["meta_description"]
        );

        $data["active_aboutus"] = "active";

        render("aboutus", $data);
    }

}
