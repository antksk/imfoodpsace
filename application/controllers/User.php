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

        $this->load->library('encryption');
        $this->load->library("form_validation");


        // $this->load->library('session');
    }


    /**
     * @param $method
     * @url https://codeigniter.com/user_guide/general/controllers.html?highlight=_remap
     */
    public function _remap($method)
    {
        log_message('debug', "test : $method");


        $this->$method();
    }

    public function index()
    {
        return $this->_view('login', $this->_base_res());
    }

    public function login_key()
    {

// https://www.codeigniter.com/user_guide/libraries/form_validation.html?highlight=set_rules#setting-validation-rules
// http://www.ciboard.co.kr/user_guide/kr/libraries/form_validation.html
        $this->form_validation->set_rules('inputEmail', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('inputPassword', 'Password', 'required|trim');

        if ($this->form_validation->run()) {
            $email = $this->input->post('inputEmail');
            $pw = $this->input->post('inputPassword');

            $req_user = $this->user->get_user([
                'email' => $email
            ]);


            if ($req_user) {

                if (password_verify($pw, $req_user->pw)) {

                    json_result([
                        'jwt' => $this->user->get_jwt_encode($req_user)
                    ]);

                    return;
                }
            }

        }


        json_result([
            'error' => [
                'email' => form_error('inputEmail'),
                'password' => form_error('inputPassword')
            ]
        ]);

    }

    public function test()
    {
        json_result([
            'test' => password_hash('test1234', PASSWORD_BCRYPT)
        ]);
    }

    public function register()
    {
        return $this->_view('register');
    }

    public function add_user()
    {
        $this->form_validation->set_rules('fname', 'Full Name', 'trim|required|max_length[128]|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean|max_length[128]');
        $this->form_validation->set_rules('password', 'Password', 'required|max_length[20]');
        $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|matches[password]|max_length[20]');
        $this->form_validation->set_rules('role', 'Role', 'trim|required|numeric');
        $this->form_validation->set_rules('mobile', 'Mobile Number', 'required|min_length[10]|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->addNew();
        } else {
            $name = ucwords(strtolower($this->input->post('fname')));
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $roleId = $this->input->post('role');
            $mobile = $this->input->post('mobile');

            $userInfo = array('email' => $email, 'password' => getHashedPassword($password), 'roleId' => $roleId, 'name' => $name, 'mobile' => $mobile, 'createdBy' => $this->vendorId, 'createdDtm' => date('Y-m-d H:i:sa'));

            $this->load->model('user_model');
            $result = $this->user_model->addNewUser($userInfo);

            if ($result > 0) {
                $this->session->set_flashdata('success', 'New User created successfully');
            } else {
                $this->session->set_flashdata('error', 'User creation failed');
            }

        }
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
        return $this->_view('my_ests', $this->_base_res([
            'inc_navbar' => $this->_inc_view('inc_navbar', ['mode' => 'user_my_ests', 'com_type' => $company_info->type]),
            'im' => ['com' => $company_info, 'usr' => $user_info],
            'ests' => [
                'sentReq' => $this->user->get_my_sent_req_ests($user_info),
                'sentCmp' => $this->user->get_my_sent_cmp_ests($user_info),
                'inbox' => $this->user->get_my_inbox_ests($user_info)
            ]

        ]));
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