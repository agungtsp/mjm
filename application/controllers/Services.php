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
        if ($data["seo_title"] == "") {
            $data["seo_title"] = "MJM | Partner for Quality";
        }

        $data["meta_description"] = preg_replace(
            "/<[^>]*>/",
            "",
            $data["meta_description"]
        );

        $data["active_services"] = "active";

        $filter_services['id_lang'] = $id_lang;
        $data['list_services'] = $this->servicemodel->findBy($filter_services);	
        foreach ($data['list_services'] as $key => $value) {
            $data['list_services'][$key]['img'] = getImgLink($value['img'], 'large');
        }   

        render("services", $data);
    }

    function detail($uri_path)
    {
        $id_lang = id_lang();
        $filter_service['id_lang'] = $id_lang;
        $filter_service['uri_path'] = $uri_path;
        $data = $this->servicemodel->findBy($filter_service, 1);
        $data['img'] = getImgLink($data['img'], 'large');
        if(!$data){
            redirect(base_url().'tidakditemukan');
        }
        if ($data["seo_title"] == "") {
            $data["seo_title"] = "MJM | Partner for Quality";
        }

        $data["meta_description"] = preg_replace(
            "/<[^>]*>/",
            "",
            $data["meta_description"]
        );

        $data["active_services"] = "active";

        render("services_detail", $data);
    }

}
