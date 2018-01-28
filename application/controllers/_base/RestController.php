<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH ."libraries/REST_Controller.php");

class RestController extends REST_Controller {
  public function __construct()
  {
    parent::__construct();
  }
}