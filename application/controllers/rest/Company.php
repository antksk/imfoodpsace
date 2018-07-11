<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . "controllers/_base/RestController.php");

class Company extends RestController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('common/company_model', 'company', TRUE);

        $this->company->set_im_pagination($this->config->item('im_default_pagination'));
    }

    /*
    <ul class="pagination">
        <li class="disabled"><a href="#!"><i class="material-icons">chevron_left</i></a></li>
        <li class="active"><a href="#!">1</a></li>
        <li class="waves-effect"><a href="#!">2</a></li>
        <li class="waves-effect"><a href="#!">3</a></li>
        <li class="waves-effect"><a href="#!">4</a></li>
        <li class="waves-effect"><a href="#!">5</a></li>
        <li class="waves-effect"><a href="#!"><i class="material-icons">chevron_right</i></a></li>
      </ul>
    */


    private function _get_contents($type, $current_page)
    {

        $condition = [
            'type' => $type,
            'filter_mode' => $this->get('m', 'all'),
            'filter' => $this->get('t', '')
        ];

        $contents = $this->company->get_list($condition, $current_page);

        $this->response($contents);
    }

    public function exist_get()
    {
        $bno = $this->input->get('bno');
        $this->response($this->company->get('bno', $bno));
    }

    // 제조 공장
    public function factory_get($page = 0)
    {
        $this->_get_contents('factory', $page);
    }

    // 유통업체
    public function distribution_get($page = 0)
    {
        $this->_get_contents('distribution', $page);
    }

    // 식당
    public function restaurant_get($page = 0)
    {
        $this->_get_contents('restaurant', $page);
    }
}