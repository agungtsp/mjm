<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Services extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('servicemodel');
    }


    function index()
    {
        $id_lang = id_lang();
        $this->load->model('pagesmodel');
        $filter_pages['id_lang'] = $id_lang;
        $filter_pages['title'] = 'Services';
        $data = $this->pagesmodel->findBy($filter_pages, 1);
        $data['description'] = str_replace("{base_url}", base_url(), $data['description']);	
        $data['img'] = getImgLink($data['img'], 'large');
        if ($data["seo_title"] == "") {
            $data["seo_title"] = "MJM | Partner for Quality";
        }

        $data["meta_description"] = preg_replace(
            "/<[^>]*>/",
            "",
            $data["meta_description"]
        );

        $data["active_services"] = "active";

        

        render("services", $data);
    }
}
