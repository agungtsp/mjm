<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Home extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('pagesmodel');
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

        $data["active_home"] = "active";

        $filter_choose['id_lang'] = $id_lang;
        $filter_choose['lower(title)'] = 'why choose';
        $choose = $this->pagesmodel->findBy($filter_choose, 1);
        $data['choose_sub_title'] = $choose['sub_title'];	
        $data['choose_teaser'] = $choose['teaser'];	
        $data['choose_description'] = str_replace("{base_url}", base_url(), $choose['description']);	
        $data['choose_img'] = getImgLink($choose['img'], 'large');

        $this->load->model('choosemodel');
        $filter_list_choose['id_lang'] = $id_lang;
        $this->db->order_by('a.id','asc');
        $data['list_choose'] = $this->choosemodel->findBy($filter_list_choose);	
        foreach ($data['list_choose'] as $key => $value) {
            $data['list_choose'][$key]['img'] = getImgLink($value['img'], 'large');
        }     
        
        $filter_find_out['id_lang'] = $id_lang;
        $filter_find_out['lower(title)'] = 'find out more about us';
        $find_out = $this->pagesmodel->findBy($filter_find_out, 1);
        $data['find_out_sub_title'] = $find_out['sub_title'];	
        $data['find_out_teaser'] = $find_out['teaser'];	
        $data['find_out_description'] = str_replace("{base_url}", base_url(), $find_out['description']);	
        $data['find_out_img'] = getImgLink($find_out['img'], 'large');

        $filter_process['id_lang'] = $id_lang;
        $filter_process['lower(title)'] = 'process';
        $process = $this->pagesmodel->findBy($filter_process, 1);
        $data['process_sub_title'] = $process['sub_title'];	
        $data['process_teaser'] = $process['teaser'];	
        $data['process_img'] = getImgLink($process['img'], 'large');

        $this->load->model('processmodel');
        $filter_list_process['id_lang'] = $id_lang;
        $this->db->order_by('a.id','asc');
        $data['list_process'] = $this->processmodel->findBy($filter_list_process);	
        foreach ($data['list_process'] as $key => $value) {
            $data['list_process'][$key]['img'] = getImgLink($value['img'], 'large');
            $data['list_process'][$key]['icon'] = getImgLink($value['icon'], 'large');
        }

        $filter_choose_detail['id_lang'] = $id_lang;
        $filter_choose_detail['lower(title)'] = 'why choose detail';
        $choose_detail = $this->pagesmodel->findBy($filter_choose_detail, 1);
        $data['choose_detail_sub_title'] = $choose_detail['sub_title'];	
        $data['choose_detail_teaser'] = $choose_detail['teaser'];	
        $data['choose_detail_description'] = str_replace("{base_url}", base_url(), $choose_detail['description']);	
        $data['choose_detail_img'] = getImgLink($choose_detail['img'], 'large');

        $filter_certifications['id_lang'] = $id_lang;
        $filter_certifications['lower(title)'] = 'certifications';
        $certifications = $this->pagesmodel->findBy($filter_certifications, 1);
        $data['certifications_sub_title'] = $certifications['sub_title'];	
        $data['certifications_teaser'] = $certifications['teaser'];	
        $data['certifications_description'] = str_replace("{base_url}", base_url(), $certifications['description']);	
        $data['certifications_img'] = getImgLink($certifications['img'], 'large');

        $this->load->model('certificationmodel');
        $filter_list_certification['id_lang'] = $id_lang;
        $this->db->order_by('a.id','asc');
        $data['list_certification'] = $this->certificationmodel->findBy($filter_list_certification);	
        foreach ($data['list_certification'] as $key => $value) {
            $data['list_certification'][$key]['img'] = getImgLink($value['img'], 'large');
        }

        $this->load->model('productmodel');
        $filter_product['id_lang'] = $id_lang;
        $list_product = $this->productmodel->findBy($filter_product);
        $data['show_load_more'] = "d-none";
        foreach ($list_product as $key => $value) {
            $value['img'] = getImgLink($value['img'], 'large');
            $value['show_hide'] = "";
            if($key>=6){
                $data['show_load_more'] = "";
                $value['show_hide'] = "d-none";
            }
            $data['list_product'][] = $value;
        }

        render("home", $data);
    }

}
