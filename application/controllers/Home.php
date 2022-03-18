<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Home extends CI_Controller
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

        $data["active_home"] = "active";

        // $this->load->model('slideshowmodel');
        // $filter_slideshow['id_lang'] = $id_lang;
        // $data['list_slideshow'] = $this->slideshowmodel->findBy($filter_slideshow);	
        // foreach ($data['list_slideshow'] as $key => $value) {
        //     $data['list_slideshow'][$key]['img'] = getImgLink($value['img'], 'large');
        // }       
        
        $this->load->model('productmodel');
        $filter_product['id_lang'] = $id_lang;
        $filter_product['is_featured'] = 1;
        $this->db->limit(3);
        $this->db->order_by('a.id','desc');
        $data['list_product'] = $this->productmodel->findBy($filter_product);	
        foreach ($data['list_product'] as $key => $value) {
            $data['list_product'][$key]['img'] = getImgLink($value['img'], 'large');
        }       

        $this->load->model('servicemodel');
        $filter_service['id_lang'] = $id_lang;
        $filter_service['is_featured'] = 1;
        $this->db->limit(3);
        $data['list_service'] = $this->servicemodel->findBy($filter_service);	
        foreach ($data['list_service'] as $key => $value) {
            $data['list_service'][$key]['img'] = getImgLink($value['img'], 'large');
        }  
        
        $this->load->model('articlemodel');
        $filter_article['id_lang'] = $id_lang;
        $filter_article['is_featured'] = 1;
        $this->db->limit(3);
        $data['list_article'] = $this->articlemodel->findBy($filter_article);	
        foreach ($data['list_article'] as $key => $value) {
            $data['list_article'][$key]['img'] = getImgLink($value['img'], 'large');
        }  

        render("home", $data);
    }

}
