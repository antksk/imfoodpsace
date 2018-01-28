<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
  
  
  public function __construct() {
    parent::__construct();
    $this->load->model('common/simple_query_model','simple_query',TRUE);
  }
  
	
	/*
	
	select 
		u.*, 
		c.*,
		concat(c.b36_cd, '-', conv(unix_timestamp(u.reg_date),10,36), '-',u.id) as `key` 
	from 
		im_user u left join im_company c on( c.id = u.com_id ); 
	
	*/
  
  public function exist_with_add_user( $data ){
    
    $user_simple_query = $this->simple_query->table('user');
    
    $where = [
        'com_id' => $data->com_id,
        'email' => $data->email
    ];
    
    $total_content = $user_simple_query->get_row_size($where);
    
    if( 0 === $total_content ){
      $user_simple_query -> add($data);
    }
    
    /*
    $row = $user_simple_query->get_row([
        'fields' => 'id, reg_date',
        'where' => $where
    ]);
    
    // fb($row, 'user_model');
    */
    // 새로 고객 정보 요청 될 때 마다, auth_key 새로 발급
    $row = $this->update_auth_key_after_get($where);
    
    return $row->result;
  }
  
  
  public function update_auth_key_after_get( $where ){
    $user_simple_query = $this->simple_query->table('user');
    
    $user_simple_query->edit(['auth_key'=>"concat(com_b36_cd, '-', conv(unix_timestamp(now()),10,36), '-',id)"],$where, FALSE);
    
    return $user_simple_query->get_row([
        'fields' => "id, com_id, com_b36_cd, nm, tel_no, email, auth_key",
        'where' => $where
    ]);
  }
  
	public function get_jwt_encode_with_user($id){
		$user_simple_query = $this->simple_query->table('user');
		$row = $user_simple_query->get_row([
        'fields' => "id, com_id, com_b36_cd, nm, tel_no, email, auth_key",
        'where' => ['id'=>$id]
    ]);
		if( $row->exist ){
			return $this->get_jwt_encode($row->result);
		}
		return '';
	}
	
	public function get_jwt_encode_with_post( ){
	  return $this->get_jwt_encode($this->input->post('jwt'));
	}
	
	public function get_jwt_encode( $result ){
		return JWT::encode([
				'id'=>$result->id, 
				'com_id'=>$result->com_id
		],$this->config->item('encryption_key'));
	}
	
	public function get_jwt_decode_with_post(){
	  $jwt = $this->input->post('jwt', $this->input->cookie('im_user_jwt',''));
		if( '' != $jwt ){
			return $this->get_jwt_decode($jwt);
		}
		
		return array_to_object([
		   'id'=>0,
		    'com_id'=> 0
		]);
	}
	
	public function get_jwt_decode( $jwt ){
		return JWT::decode($jwt,$this->config->item('encryption_key'));
	}
	
  public function get_user( $where ){
    $user_simple_query = $this->simple_query->table('user');
    
    $row = $user_simple_query->get_row([
        'fields' => "id, com_id, com_b36_cd, nm, tel_no, email, auth_key",
        'where' => $where
    ]);
    if( $row->exist ){
      return $row->result;
    }
    return FALSE;
  }
  
  /*
 $sql = "SELECT * FROM some_table WHERE id IN ? AND status = ? AND author = ?";
$this->db->query($sql, array(array(3, 6), 'live', 'Rick'));
   */
  
  // 내가 요청 보낸 견적서 목록
  public function get_my_sent_req_ests($user){
    fb($user, 'get_my_sent_req_ests');
    $query = $this->db->query("
      select 
      	e.id, com_from, usr_from, com_to, c.nm, step, 'sentReq' as mode,
        max(e.reg_date) as reg_date
      from 
      	im_estimate e left join im_company c on( c.id = e.com_to )
      where 
      	step = 0
      and com_from = ? 
      and usr_from = ? 
      group by com_from, usr_from, com_to
      order by e.reg_date desc
    ", [$user->com_id, $user->id]);
    return $query->result();
  }
	
	// 내가 요청 완료 견적서 목록
  public function get_my_sent_cmp_ests($user){
		
    $query = $this->db->query("
      select 
      	e.id, com_from, usr_from, com_to, /*concat(c.nm,'(',u.nm,')')*/ c.nm as nm, step, 'sentCmp' as mode,
        date_format(e.res_date,'%m.%d %r') as reg_date
      from 
      	im_estimate e 
        left join im_company c on( c.id = e.com_to )
        left join im_user u on( u.id = e.usr_from )
      where 
      	step = 1
      and com_from = ? 
      
      order by e.reg_date desc
    ", [$user->com_id]);
    return $query->result();
  }
  
  // 받은 견적서 내용
  public function get_my_inbox_ests($user, $step = 0){
    $query = $this->db->query("
      select 
      	e.id, com_from, usr_from, com_to, c.nm, step, 'inbox' as mode,
       max(e.reg_date) as reg_date
      from 
      	im_estimate e left join im_company c on( c.id = e.com_from )
      where 
      	step = ? 
      and com_to = ? 
      group by com_from, usr_from, com_to
      order by e.reg_date asc
    ", [$step, $user->com_id]);
    return $query->result();
  }
  
}
