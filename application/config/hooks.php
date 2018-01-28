<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/

/*
$hook['post_controller_constructor'][] = function(){
    $CI = &get_instance();
    isset($CI->session()) OR $CI->load->library('session');
    $CI->load->helper('url');
    $CI->ssesion_has_userdata('name') OR $CI->session->set_userdata('name', 'guest');
    $userName = $CI->session_userdata('name');

    if( $userName === 'guest' && !(isset($CI->allowed_ethod) && in_array($CI->router->method, $CI->allowed_method)))
        show_error('이 페이지는 로그인을 해야만 ㅎ사용이 가능한 페이지 입니다. ', 401);
}
*/