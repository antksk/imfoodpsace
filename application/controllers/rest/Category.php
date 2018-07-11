<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . "controllers/_base/RestController.php");

class Category extends RestController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('common/merchandise_category_model', 'mc', TRUE);
    }

    public function mc_get($level = 1, $parent_id = null)
    {
        $this->response($this->mc->get_list($level, $parent_id));
    }
}