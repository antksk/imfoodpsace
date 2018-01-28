<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH ."controllers/_base/MvcController.php");

class Estimate extends MvcController{
	public function __construct()
  {
    parent::__construct();
		$this->load->model('common/merchandise_model','merchandise',TRUE);
		$this->load->model('common/user_model','user',TRUE);
		$this->load->model('common/estimate_model','estimate',TRUE);
  }
	
	public function index(){
		// $post_mcds = json_decode($this->input->post('mcds'));
		// fb($this->merchandise->get_list_id_in($post_mcds->mcds), 'fb');
		$im_prefix = $this->config->item('im_prefix');
		
		return $this->load->view('idx_estimate',[
			'script_tag' => $this->_cdn_js(),
			'style_tag' => $this->_cdn_css(),
		  'inc_common' => $this->_inc_view('inc_common'),
			'inc_navbar' => $this->_inc_view('inc_navbar',['mode'=>'estimate']),
			'company_prefix' => $im_prefix['company']
		]);
	}
	
	private function _estimate( $est ){
	  $result = [];
	  foreach($est as $e){
	    $estimate = new StdClass();
	    
	    if( is_object($e) && property_exists($e,'id') ){
	      // fb($e, 'e');
	      $estimate->id = intval($e->id);
	      $estimate->count = $e->count;
	      $estimate->price = $e->price;
	      
	      array_push($result, $estimate);
	    }
	    
	  }
	  return $result;
	}
	
	private function _est_add( $user, $post_est_req ){
	  foreach($post_est_req->est as $est){
	    if( is_object($est) && property_exists($est,'est') ){
	      $data = [
	          'com_from'=>$user->com_id,
	          'usr_from'=>$user->id,
	          'com_to'=>$est->company->com_id,
	          'from_total_price'=>$est->total,
	          // 'reg_date'=>now(),
	          'req_est'=> json_encode($this->_estimate($est->est))
	      ];
	      
	      $this->estimate->add($data);
	    }
	  }
	}
	
	private function _reg_user_and_add_est( $post_est_req ){
		$user = $this->user->exist_with_add_user( $post_est_req->user );
	  
	  $this->_est_add($user, $post_est_req);
	 
	  return $this->user->get_jwt_encode($user);
	}
	
	// 견적서 요청하는 화면에서 jwt가 설정되어 있지 않은 경우 
	// 사용자 정보고 같이 등록을 받음
	public function reg(){
	  $post_est_req = json_decode( $this->input->post('est_req') );
	  
	  $result = [];
	  
	  // 사용자 정보(jwt)를  클라이언트가 가지고 있는 경우
	  if( property_exists($post_est_req, 'jwt') ){
	    $user = $this->user->get_jwt_decode($post_est_req->jwt);
			fb($user, 'jwt-$user');
	    $post_est_req->user = $this->user->get_user(object_to_array($user));
	  }
	  
	  $jwt = $this->_reg_user_and_add_est($post_est_req);
		
	  fb($post_est_req, '$post_est_req');
		fb($jwt, 'reg_user');
	  
	  $result = [ 'jwt'=>$jwt ];
	  
	  json_result($result);
	}
	
	public function res($est_id){
	  $result = $this->estimate->edit($est_id,[
	      'res_est' => $this->input->post('est_res'),
	      'usr_to' => $this->input->post('usr_id'),
	      'step' => 1,
	      'res_date' => date('Y-m-d H:i:s',now())
	  ]);
	  
	  json_result([
				'id' => $est_id,
	      'result' => $result,
	      'now' => date('Y-m-d H:i:s',now())
	  ]);
	}
	
	public function get($id){
	  $e = $this->estimate->get_est($id);
	  $map = function($elem){
	    return $elem['id'];
	  };
	  
	  // 견적 내용은 요청한 쪽과 요청 받는 쪽은 모두 같은 제품을 가지고 
	  // 진행하기 때문에 아무곳(req_est 혹은 res_est)에서나 제품 id를 기준으로 가져와도 됨
	  $e->est->merchandise = $this->merchandise->get_list_id_in( array_map($map, object_to_array($e->est->from->data)));
	   
	  
	  json_result( $e );
	}
	
}