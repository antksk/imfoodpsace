<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . "controllers/_base/MvcController.php");

class User extends MvcController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('common/merchandise_model', 'merchandise', TRUE);
        $this->load->model('common/user_model', 'user', TRUE);
        $this->load->model('common/company_model', 'company', TRUE);
        $this->load->model('common/estimate_model', 'estimate', TRUE);
    }

    public function index()
    {
        return $this->_view('idx_user', $this->_base_res([
            'inc_navbar' => $this->_inc_view('inc_navbar', ['mode' => 'user']),
        ]));
    }

    public function my_ests()
    {

        $user_key = $this->user->get_jwt_decode_with_post();
        $user_info = $this->user->get_user(object_to_array($user_key));
        // echo 'alert(' . json_encode($user_key) . ')';

        // echo 'alert(' . json_encode($user_info) . ')';

        fb($user_info, 'user');

        $company_info = new stdClass;
        $company_info->type = '';
        // 사용자 정보를 DB에서 가져올수 있으면,
        if ($user_info) {
            $row = $this->company->get('id', $user_info->com_id);
            if ($row->exist) {
                $company_info = $row->result;
            }
        }

        fb($company_info, 'company_infoer');

        // fb( $user_info, 'server-side-user-info');
        // fb( $company_info, 'servier-side-com-info');
        return $this->load->view('idx_user_my_ests', [
            'script_tag' => $this->_cdn_js(),
            'style_tag' => $this->_cdn_css(),
            'inc_common' => $this->_inc_view('inc_common'),
            'inc_navbar' => $this->_inc_view('inc_navbar', ['mode' => 'user_my_ests', 'com_type' => $company_info->type]),
            'im' => ['com' => $company_info, 'usr' => $user_info],
            'ests' => [
                'sentReq' => $this->user->get_my_sent_req_ests($user_info),
                'sentCmp' => $this->user->get_my_sent_cmp_ests($user_info),
                'inbox' => $this->user->get_my_inbox_ests($user_info)
            ]
        ]);
    }


    // auth-key를 기반으로 해서 사용자 jwt 정보를 얻어옴
    public function auth_key_and_jwt()
    {
        $user = $this->user->get_user(['auth_key' => $this->input->post('ak')]);
        // fb($user,'user');
        if ($user) {
            json_result([
                'jwt' => $this->user->get_jwt_encode($user)
            ]);
        } else {
            json_result([
                'jwt' => ''
            ]);
        }
        // echo $this->user->get_jwt_encode( );
    }

    public function rest_info()
    {
        json_result($this->user->get_jwt_decode());
    }

    public function rest_my_ests($mode)
    {
        $jwt_user = $this->user->get_jwt_decode_with_post();
        // fb($jwt_user, 'user');
        switch ($mode) {
            case 'sent':
                json_result([
                    'sentReq' => $this->user->get_my_sent_req_ests($jwt_user),
                    'sentCmp' => $this->user->get_my_sent_cmp_ests($jwt_user),
                ]);
                break;
            case 'inbox':
                json_result([
                    'inbox' => $this->user->get_my_inbox_ests($jwt_user)
                ]);
                break;
        }
    }

    public function refresh_auth_key($id)
    {
        $user = $this->user->update_auth_key_after_get(['id' => $id]);
        json_result($user);
    }


}