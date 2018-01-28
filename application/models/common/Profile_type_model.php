<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Profile_type_model extends CI_Model {
  
  public function __construct()
  {
    parent::__construct();
  	$this->load->model('common/code_model','code',TRUE);
  }
  
  public function get_type_list(){
  	return $this->code->get_grp('code.user.profile.type');
  }
  
}