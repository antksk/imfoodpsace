<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Merchandise_model extends CI_Model {

  public function __construct() {
    parent::__construct();
    $this->load->model('common/simple_query_model','simple_query',TRUE);
  }
	
	public function get_list($com_id, $cate_id = null,  $like=[]){
		
		$merchandise_simple_query = $this->simple_query->table('merchandise');
		
		$where = [
		    'com_id' => $com_id,
		    'cate_id' => $cate_id
		];
		
		$total_content = $merchandise_simple_query->get_row_size($where);
		
		/*
		
		$results = $merchandise_simple_query->get_rows([
				'fields' => 'id, cate_id, item_id, nm, head_unit, price_purchase, price_sale, price_market, has_mp',
				'where' => $where,
				'like' => $like,
				'offset' => 0,
				'limit' => 10,
				'order_by' => 'nm ASC'
			])
		;
		*/
		$query = $this->db->query("
			SELECT 
					m.id, m.cate_id, m.item_id, 
					m.nm, m.head_unit, 
					m.price_purchase, m.price_sale, m.price_market, m.has_mp,
					c.parent_id, c.nm as cate_nm, c.path, c.lv, c.sort, c.is_root, c.is_leaf
			FROM
					im_merchandise m
			LEFT JOIN
					im_category c ON (m.cate_id = c.id)
			WHERE
					com_id = ? AND cate_id = ?;
		", $where);
		
		return array_to_object([
			'total_content' => $total_content,
			'contents' => $query->result()
		]);
	}
	
	public function get_list_id_in($ids = []){
		$merchandise_simple_query = $this->simple_query->table('merchandise');
		
		$results = $merchandise_simple_query->get_rows([
				'fields' => 'id, cate_id, item_id, nm, head_unit, price_purchase, price_sale, price_market, has_mp',
				'where_in' => ['field'=>'id','data'=>$ids],
				'order_by' => 'price_sale ASC'
			])
		;
		return $results->result;
	}
}