<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Careers extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('careermodel');
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

        $data["active_careers"] = "active";

        $filter_career['id_lang'] = $id_lang;
        $sort = $_GET['sort'];
        if($sort==3){
            $data['selected_3'] = 'selected';
            $this->db->order_by('a.title','desc');
        } else if($sort==2){
            $data['selected_2'] = 'selected';
            $this->db->order_by('a.title','asc');
        } else {
            $data['selected_1'] = 'selected';
            $this->db->order_by('a.id','desc');
        }
        $data['list_careers'] = $this->careermodel->findBy($filter_career);	
        foreach ($data['list_careers'] as $key => $value) {
            $data['list_careers'][$key]['img'] = getImgLink($value['img'], 'large');
        }   

        render("careers", $data);
    }

    function detail($uri_path)
    {
        $id_lang = id_lang();
        $filter_career['id_lang'] = $id_lang;
        $filter_career['uri_path'] = $uri_path;
        $data = $this->careermodel->findBy($filter_career, 1);
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

        $data["active_careers"] = "active";

        render("careers_detail", $data);
    }

}
