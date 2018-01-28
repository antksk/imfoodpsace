<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estimate_model extends CI_Model {
  
  
  public function __construct() {
    parent::__construct();
    $this->load->model('common/simple_query_model','simple_query',TRUE);
  }
  
  
  public function add( $data ){
    
    $estimate_simple_query = $this->simple_query->table('estimate');
    return $estimate_simple_query->add( $data );
  }
  
  
  public function edit($id, $data){
    $estimate_simple_query = $this->simple_query->table('estimate');
    return $estimate_simple_query->edit( $data, ['id'=>$id] );
    
  }
  
  public function get( $id ){
    $query = $this->db->query("
      select 
      	e.id, e.reg_date as create_date,
       e.res_date, 
      	cf.nm as from_com_nm, 
      	cf.addr_zipcode as from_com_zip, 
      	cf.addr_road as from_com_addr_road, 
      	cf.addr_detail as from_com_addr_dtl,
      	cf.type as from_com_type,
      	uf.nm as from_usr_nm,
       uf.tel_no as from_usr_tel,
       uf.email as from_usr_email,
      	uf.auth_key as from_usr_key,
      	e.req_est as est_req_json,
      	if(isnull(e.res_est),e.req_est,e.res_est) as est_res_json,
      	ct.nm as to_com_nm, 
      	ct.addr_zipcode as to_com_zip, 
      	ct.addr_road as to_com_addr_road, 
      	ct.addr_detail as to_com_addr_dtl,
      	ct.type as to_com_type,
      	ut.nm as to_usr_nm,
       ut.tel_no as to_usr_tel,
       ut.email as to_usr_email,
      	ut.auth_key as to_usr_key
      from im_estimate e 
      	left join im_company cf on( e.com_from = cf.id )
      	left join im_user uf on (e.usr_from = uf.id)
      	left join im_company ct on( e.com_from = ct.id )
      	left join im_user ut on (e.usr_from = ut.id)
      where e.id = ?
    ", [$id]);
    return $query->result();
  }
  
  public function get_est($id){
    $est = $this->get($id);
    if( FALSE === isset($est[0]) ){
      return array_to_object([]);
    }else{
      $e = $est[0];
      return array_to_object([
        'from' => [
           'com' => [
             'nm' => $e->from_com_nm,
             'typ' => $e->from_com_type,
             'zip' => $e->from_com_zip,
             'addr_r' => $e->from_com_addr_road,
             'addr_d' => $e->from_com_addr_dtl
             ],
            'usr' => [
              'nm' => $e->from_usr_nm,
              'tel' => $e->from_usr_tel,
              'email' => $e->from_usr_email
            ]
        ],
        'to' => [
            'com' => [
                'nm' => $e->to_com_nm,
                'typ' => $e->to_com_type,
                'zip' => $e->to_com_zip,
                'addr_r' => $e->to_com_addr_road,
                'addr_d' => $e->to_com_addr_dtl
            ],
            'usr' => [
                'nm' => $e->to_usr_nm,
                'tel' => $e->to_usr_tel,
                'email' => $e->to_usr_email
            ]
        ],
       'est'=>[
           'id'=>$e->id, 
           'from' => [
             'req_date' => $e->create_date,
             'data' => json_decode($e->est_req_json)
             ],
           'to' => [
             'res_date' => $e->res_date,
             'data' => json_decode($e->est_res_json)
             ]
        ]
      ]);
    }
  }
}
