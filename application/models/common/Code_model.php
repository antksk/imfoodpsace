<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Code_model extends CI_Model {
  const TABLE = 'code';
  public function __construct() {
    parent::__construct();
  }
  
  public function get_cd($cd){
    return $this
		->db
		->get_where(self::TABLE,['cd'=>$cd])
		->row()
	;
  }
  
  public function get_grp($grp, $order_by = array('sort asc'), $like_side = 'not_support'){
    
    $this->db
      ->order_by(implode(', ', $order_by))
    ;
    
    if( in_array($like_side,array('before','after', 'both')) ){
      $this->db->like('grp', '$grp', $like_side); 
    }else{
      $this->db->where('grp',$grp);
    }
    
    return $this->db->get(self::TABLE)->result();
  }
  
}
