<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Event extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('eventmodel');
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

        $id_lang = id_lang();
        $this->load->model('pagesmodel');
        $filter_pages['id_lang'] = $id_lang;
        $filter_pages['lower(title)'] = 'event';
        $data_event = $this->pagesmodel->findBy($filter_pages, 1);
        $data['event_sub_title'] = $data_event['sub_title'];	
        $data['event_teaser'] = $data_event['teaser'];	

        $filter_list_event['id_lang'] = $id_lang;
        $this->db->order_by('event_date', 'desc');
        $data['list_event'] = $this->eventmodel->findBy($filter_list_event);
        $data['list_event_one'] = array();
        $data['list_event_two'] = array();
        foreach ($data['list_event'] as $key => $value) {
            $value['img'] = getImgLink($value['img'], 'large');
            $value['event_date'] = date("d F Y", strtotime($value['event_date']));
            if($key<1){
                $data['list_event_one'][] = $value;
            } else {
                $data['list_event_two'][] = $value;
            }
        }
        render("event", $data);
    }

    function detail($uri_path)
    {
        $id_lang = id_lang();
        $filter_event['id_lang'] = $id_lang;
        $filter_event['uri_path'] = $uri_path;
        $data = $this->eventmodel->findBy($filter_event, 1);
        $data['img'] = getImgLink($data['img'], 'large');
        $data['event_date'] = date("d F Y", strtotime($data['event_date']));

        if ($data["seo_title"] == "") {
            $data["seo_title"] = "MJM | Partner for Quality";
        }

        $data["meta_description"] = preg_replace(
            "/<[^>]*>/",
            "",
            $data["meta_description"]
        );

        $data["active_event"] = "active";
        $data['list_top_articles'] = $this->top_articles($id_lang);
        render("event_detail", $data);
    }

    private function top_articles($id_lang, $id=''){
        $this->load->model('articlemodel');

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

}
