<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . "controllers/_base/MvcController.php");

class Bootstrap extends MvcController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('common/company_model', 'company', TRUE);
        $this->load->model('common/merchandise_model', 'merchandise', TRUE);
        $this->load->model('common/merchandise_category_model', 'merchandise_category', TRUE);
        $this->company->set_im_pagination($this->config->item('im_default_pagination'));
    }

    private function _tag_company($type)
    {
        return $this->_inc_view('tag_company', [
            'company' => $this->company->get_list(['type' => $type])
        ], TRUE);
    }

    // 처음 화면에 표시되는 entry page
    public function index()
    {
        $this->load->view('idx_main', [
            'script_tag' => $this->_cdn_js(),
            'style_tag' => $this->_cdn_css(),
            'inc_common' => $this->_inc_view('inc/inc_common'),
            'inc_help_comment' => $this->_inc_view('inc/inc_help_comment'),
            'inc_navbar' => $this->_inc_view('inc/inc_navbar', ['mode' => 'main']),
            'tag_factory' => $this->_tag_company('factory'),
            'tag_distribution' => $this->_tag_company('distribution'),
            'tag_restaurant' => $this->_tag_company('restaurant')
        ]);
    }

    // 각 업체에 대한 상세 페이지
    public function company_detail($type = 'distribtuion', $b36_cd = '')
    {

        $company_detail = $this->company->get_detail($b36_cd);

        $im_prefix = $this->config->item('im_prefix');

        $this->load->view('dtl_company', [
            'script_tag' => $this->_cdn_js(),
            'style_tag' => $this->_cdn_css(),
            'inc_common' => $this->_inc_view('inc_common'),
            'has_company' => $company_detail->exist,
            'company' => $company_detail->result,
            'company_prefix' => $im_prefix['company'],
            'ns' => base_url("/imfs/company/detail/$type"),
            'merchandise' => $company_detail->exist ? $this->merchandise->get_list($company_detail->result->id) : [],
            'merchandise_category' => $this->merchandise_category->get_root_list()
        ]);
    }

}