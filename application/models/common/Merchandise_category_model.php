<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Merchandise_category_model extends CI_Model {

  public function __construct() {
    parent::__construct();
    $this->load->model('common/simple_query_model','simple_query',TRUE);
  }
	
	public function get_list($level, $parent_id=null){
		
		$merchandise_simple_query = $this->simple_query->table('category');
		
		$where = ['lv' => $level];
		
		if( FALSE == is_null($parent_id) ){
				$where['parent_id'] = $parent_id;
		}
		
		// fb( $where, 'where' );
		
		$total_content = $merchandise_simple_query->get_row_size($where);
		
		$results = $merchandise_simple_query->get_rows([
				'fields' => 'id, parent_id, nm, path, lv, is_root, is_leaf',
				'where' => $where,
				// 'like' => ['after'=>['id'=>'F']],
				// 'offset' => 100,
				// 'limit' => 100,
				'order_by' => 'sort ASC'
			])
		;
		
		return array_to_object([
			'total_content' => $total_content,
			'contents' => $results->result
		]);
	}
	
	public function get_root_list(){
		return $this->get_list(1);
	}
}