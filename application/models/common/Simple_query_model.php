<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author seung-gyum kim
 * @see
 *   http://www.ciboard.co.kr/user_guide/kr/database/query_builder.html#query-grouping
 */
class Simple_query_model extends CI_Model {
  var $target_table;
  
  public function __construct()
  {
    parent::__construct();
  }
  
  public function table( $table ){
    $this->target_table = $table;
    return $this;
  }
  
  public function set($key, $value = '', $escape = TRUE){
    $this->db->set($key,$value,$escape);
    return $this;
  }
  
  public function add($data, $escape=TRUE){
    $this->db->insert($this->target_table, $data, $escape);
    return $this->db->insert_id();
  }
  
  public function add_list($data_list){
    return $this->db->insert_batch($this->target_table, $data_list);
  }
  
  // update 
  public function edit($data, $where, $escape=TRUE){
    return 
      $this->db->set($data,'',$escape)
          ->where($where)
          ->update($this->target_table)
        ;
  }
  
  // replace
  public function modify($data, $where){
    return 
      $this
        ->db->where($where)
        ->replace($this->target_table, $data)
       ;
  }
  
  public function _check_condition(){
    $args = func_get_args();
    return call_user_func_array('array_flat_key_exists', $args);
  }
  
  private function _get($query, $entity){
    $row_count = $query->num_rows();
    $fields_count = $query->num_fields();
    if( isset($entity) ){
      $row = $query->custom_row_object(0, $entity);
    }
  }
	/*
  private function condition_base_query_build($condition){
    $where = $condition['where'];
    fb( $this->target_table, 'condition_base_query_build');
    fb( $where, '$where');
		
    if( $this->_check_condition($condition, 'fields') ){
      $this->db->select($condition['fields'], FALSE);
	  
      if( $this->_check_condition($condition, 'limit', 'offset') ){
        $this->db->limit($condition['limit'], $condition['offset']);
      }
//        fb( '$this->db->get($this->table)');
      return $this->db->where($where)->get($this->target_table);
    }else{
      if( $this->_check_condition($condition, 'limit', 'offset') ){
//         fb( '$this->db->get_where($this->table, $where, $limit, $offset)');
        return $this->db->get_where($this->target_table, $where, $condition['limit'], $condition['offset']);
      }else{
//         fb( '$this->db->get_where($this->table, $where)');
        return $this->db->get_where($this->target_table, $where);
      }
    }
    return null;
  }
  */
	
	private function condition_like_build($condition){
		if( $this->_check_condition($condition, 'like') && FALSE === empty($condition['like']) ){
			if( $this->_check_condition($condition['like'], 'before') ){
				$this->db->like($condition['like']['before'], '', 'AND', 'before');
			}
			else if( $this->_check_condition($condition['like'], 'after') ){
				$this->db->like($condition['like']['after'], '', 'AND', 'after');
			}
			else if( $this->_check_condition($condition['like'], 'both') ){
				$this->db->like($condition['like']['both'], '', 'AND', 'both');
			}
			else{
				$this->db->like($condition['like']);
			}
		}
	}
	
	private function condition_base_query_build($condition){
      	
    if( $this->_check_condition($condition, 'fields') ){
      $this->db->select($condition['fields'], FALSE);
		}
		
		if( $this->_check_condition($condition, 'where') ){
			$this->db->where($condition['where']);
		}
		
		if( $this->_check_condition($condition, 'where_in') ){
			$field = $condition['where_in']['field'];
			$data = $condition['where_in']['data'];
			$this->db->where_in($field, $data);
		}
		
		if( $this->_check_condition($condition, 'limit', 'offset') ){
			$this->db->limit($condition['limit'], $condition['offset']);
		}
		
		$this->condition_like_build($condition);
		
		if( $this->_check_condition($condition, 'order_by') ){
			$this->db->order_by($condition['order_by']);
		}

		return $this->db->get($this->target_table);
  }
	
	public function get_row_size($where, $entity=null, $meta = FALSE){
		$size_condition = [
			'fields' => 'count(1) as size',
			'where'=>$where
		];
		$result = $this->get_row($size_condition, $entity, $meta);
		
		if( $result->exist ){
			return intval($result->result->size);
		}
		return 0;
	}
	
  public function get_row($condition, $entity=null, $meta = FALSE){
    $query = $this->condition_base_query_build($condition);
//     fb( $query, 'get_rows');
    $result = new StdClass();
    $result->row_count = $query->num_rows();
    $result->exist = (1 === $result->row_count);
    if( $result->exist ){ 
      if( isset($entity) ){
        // fb('entity');
        $result->result = $query->custom_row_object(0, $entity);
      }else{
        $result->result = $query->first_row();
      }
    }else{
			$result->result = new StdClass();
		}
    
    if( $meta ){
      $result->field_count = $query->num_fields();
      $result->field_names = $query->list_fields();
      $result->field_meta = $query->field_data();
    }
    return $result;
  }
  
  public function get_rows($condition, $entity=null, $meta = FALSE){
    $query = $this->condition_base_query_build($condition);
     // fb( $query, 'get_rows');
    $result = new StdClass();
    $result->row_count = $query->num_rows();
    $result->exist = (1 <= $result->row_count);
    if( $result->exist ){
      if( isset($entity) ){
        $result->result = $query->custom_result_object($entity);
      }else{
        $result->result = $query->result();
      }
    }else{
			$result->result = new StdClass();
		}
    
    if( $meta ){
      $result->field_count = $query->num_fields();
      $result->field_names = $query->list_fields();
      $result->field_meta = $query->field_data();
    }
    return $result;
  }
  
  public function query_build($type='update', $data, $where){
    switch($type){
      case 'update': return $this->db->update_string($this->target_table, $data, $where);
      case 'insert': return $this->db->insert_string($this->target_table, $data);
    }
    return '';
  }
  
}