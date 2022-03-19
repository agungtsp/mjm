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
        $filter_pages['lower(title)'] = 'about us';
        $data = $this->pagesmodel->findBy($filter_pages, 1);
        $data['about_description'] = str_replace("{base_url}", base_url(), $data['description']);	
        $data['about_img'] = getImgLink($data['img'], 'large');

        $id_lang = id_lang();
        $this->load->model('pagesmodel');
        $filter_pages['id_lang'] = $id_lang;
        $filter_pages['lower(title)'] = 'faq';
        $data_faq = $this->pagesmodel->findBy($filter_pages, 1);
        $data['faq_sub_title'] = $data_faq['sub_title'];	
        $data['faq_teaser'] = $data_faq['teaser'];	
        $data['faq_description'] = str_replace("{base_url}", base_url(), $data_faq['description']);	

        $id_lang = id_lang();
        $this->load->model('pagesmodel');
        $filter_pages['id_lang'] = $id_lang;
        $filter_pages['lower(title)'] = 'testimonials';
        $data_testimonial = $this->pagesmodel->findBy($filter_pages, 1);
        $data['testimonials_sub_title'] = $data_testimonial['sub_title'];	
        $data['testimonials_teaser'] = $data_testimonial['teaser'];	

        $this->load->model('testimonialmodel');
        $filter_list_testimonial['id_lang'] = $id_lang;
        $this->db->order_by('a.id','asc');
        $data['list_testimonial'] = $this->testimonialmodel->findBy($filter_list_testimonial);	
        foreach ($data['list_testimonial'] as $key => $value) {
            $data['list_testimonial'][$key]['testimonial_img'] = getImgLink($value['img'], 'large');
            $data['list_testimonial'][$key]['testimonial_description'] = $value['description'];
            $data['list_testimonial'][$key]['testimonial_title'] = $value['title'];
            $data['list_testimonial'][$key]['testimonial_sub_title'] = $value['sub_title'];
            $data['list_testimonial'][$key]['rating'] = '';
            for ($i=1; $i <= (int)$value['rating'] ; $i++) { 
                $data['list_testimonial'][$key]['rating'] .= '<img src="'.base_url().'asset/images/star.jpg" alt="">';
            }
        }

        $this->load->model('faqmodel');
        $filter_list_faq['id_lang'] = $id_lang;
        $this->db->order_by('a.id','asc');
        $data['list_faq'] = $this->faqmodel->findBy($filter_list_faq);	
        foreach ($data['list_faq'] as $key => $value) {
            $data['list_faq'][$key]['faq_id'] = $value['id'];
            $data['list_faq'][$key]['collapsed_show'] = '';
            $data['list_faq'][$key]['collapsed'] = '';
            if($key==0){
                $data['list_faq'][$key]['collapsed_show'] = 'show';
            } else{
                $data['list_faq'][$key]['collapsed'] = 'collapsed';
            }
            $data['list_faq'][$key]['img'] = getImgLink($value['img'], 'large');
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
