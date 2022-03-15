<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Product extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('productmodel');
    }


    function index(){
        $id_lang = id_lang();
        if ($data["seo_title"] == "") {
            $data["seo_title"] = "MJM | Partner for Quality";
        }

        $data["meta_description"] = preg_replace(
            "/<[^>]*>/",
            "",
            $data["meta_description"]
        );

        $data["active_product"] = "active";

        $filter_product['id_lang'] = $id_lang;
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
        $data['list_product'] = $this->productmodel->findBy($filter_product);	
        foreach ($data['list_product'] as $key => $value) {
            $data['list_product'][$key]['img'] = getImgLink($value['img'], 'large');
        }   

        render("product", $data);
    }

    function detail($uri_path){
        $id_lang = id_lang();
        $filter_product['id_lang'] = $id_lang;
        $filter_product['uri_path'] = $uri_path;
        $data = $this->productmodel->findBy($filter_product, 1);
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

        $data["active_product"] = "active";
        
        $filter_recomendation['id_lang'] = $id_lang;
        $filter_recomendation['id !='] = $data['id'];
        $data['list_recomendation'] = $this->productmodel->findBy($filter_recomendation);	
        foreach ($data['list_recomendation'] as $key => $value) {
            $data['list_recomendation'][$key]['img_recomendation'] = getImgLink($value['img'], 'large');
        }    

        render("product_detail", $data);
    }

}
