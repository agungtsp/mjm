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
        $this->db->order_by('a.is_featured','desc');
        $this->db->order_by('a.id','desc');
        $list_product = $this->productmodel->findBy($filter_product);	
        foreach ($list_product as $key => $value) {
            $value['img'] = getImgLink($value['img'], 'large');
            if($key==0){
                $data['list_product_1'][] = $value;
            } else {
                $data['list_product'][] = $value;
            }
        }   

        render("product", $data);
    }

    function detail($uri_path){
        $id_lang = id_lang();
        $filter_product['id_lang'] = $id_lang;
        $filter_product['uri_path'] = $uri_path;
        $data = $this->productmodel->findBy($filter_product, 1);
        $data['img'] = getImgLink($data['img'], 'large');
        $data['img_2'] = getImgLink($data['img_2'], 'large');
        $data['img_3'] = getImgLink($data['img_3'], 'large');
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
        
        $list_image = "";
        if($data['img']){
            $list_image .= '<div class="item"><img src="'.$data['img'].'" alt=""></div>';
        }
        if($data['img_2']){
            $list_image .= '<div class="item"><img src="'.$data['img_2'].'" alt=""></div>';
        }
        if($data['img_3']){
            $list_image .= '<div class="item"><img src="'.$data['img_3'].'" alt=""></div>';
        }
        $data['list_img'] = $list_image;

        render("product_detail", $data);
    }

}
