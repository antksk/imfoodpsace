<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH ."controllers/_base/RestController.php");

class Merchandise extends RestController{
	public function __construct()
  {
    parent::__construct();
    $this->load->model('common/merchandise_model','merchandise',TRUE);
  }
	
	public function m_get($com_id, $cate_id=null){
		$this->response( $this->merchandise->get_list($com_id, $cate_id) );
	}
}