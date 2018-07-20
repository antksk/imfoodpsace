<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by IntelliJ IDEA.
 * User: we
 * Date: 2018. 7. 11.
 * Time: PM 5:59
 * Ref : http://www.ciboard.co.kr/user_guide/kr/libraries/sessions.html
 */

class LogInCheckController extends CI_Controller

{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }




}