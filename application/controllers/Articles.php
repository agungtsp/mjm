<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Articles extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('articlemodel');
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

        $data["active_articles"] = "active";

        $filter_article['id_lang'] = $id_lang;
        $data['list_articles'] = $this->articlemodel->findBy($filter_article);	
        foreach ($data['list_articles'] as $key => $value) {
            $data['list_articles'][$key]['img'] = getImgLink($value['img'], 'large');
            $data['list_articles'][$key]['created_at'] = iso_date_custom_format($value['create_date'], 'd F Y');
        }   
        $data['list_top_articles'] = $this->top_articles($id_lang);
        $data['list_top_event'] = $this->top_event($id_lang);
        render("articles", $data);
    }

    function detail($uri_path)
    {
        $id_lang = id_lang();
        $filter_article['id_lang'] = $id_lang;
        $filter_article['uri_path'] = $uri_path;
        $data = $this->articlemodel->findBy($filter_article, 1);
        $data['img'] = getImgLink($data['img'], 'large');
        $data['created_at'] = iso_date_custom_format($data['create_date'], 'd F Y');
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

        $data["active_articles"] = "active";
        $data['list_top_articles'] = $this->top_articles($id_lang);
        render("articles_detail", $data);
    }

    private function top_articles($id_lang, $id=''){
        $filter_recomendation['id_lang'] = $id_lang;
        if($id){
            $filter_recomendation['id !='] = $id;
        } 
        $this->db->order_by('create_date', 'asc');
        $data = $this->articlemodel->findBy($filter_recomendation);	
        foreach ($data as $key => $value) {
            $data[$key]['img_top_article'] = getImgLink($value['img'], 'large');
            $data[$key]['uri_path_top_article'] = $value['uri_path'];
            $data[$key]['title_top_article'] = $value['title'];
        }
        return $data;
    }

    private function top_event($id_lang, $id=''){
        $this->load->model('eventmodel');

        $filter_recomendation['id_lang'] = $id_lang;
        if($id){
            $filter_recomendation['id !='] = $id;
        } 
        $this->db->order_by('event_date', 'desc');
        $data = $this->eventmodel->findBy($filter_recomendation);	
        foreach ($data as $key => $value) {
            $data[$key]['img_top_event'] = getImgLink($value['img'], 'large');
            $data[$key]['uri_path_top_event'] = $value['uri_path'];
            $data[$key]['title_top_event'] = $value['title'];
            $data[$key]['event_date'] = date("d F Y", strtotime($value['event_date']));
        }
        return $data;
    }


}
