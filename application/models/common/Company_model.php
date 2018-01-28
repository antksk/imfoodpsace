<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Company_model extends CI_Model {
  
	const MAX_PAGE_SIZE = 15;
	
  public function __construct() {
    parent::__construct();
    $this->load->model('common/simple_query_model','simple_query',TRUE);
		$this->load->library('pagination');
  }
	
	private $im_pagination;
	
	public function set_im_pagination($im_pagination){
		$this->im_pagination = $im_pagination;
	}
	
	private function _get_company_custom_pagination_tag($url, $total_rows, $per_page){
		$this->pagination->initialize(array_merge($this->im_pagination,[
			'base_url' => $url, // "/imfs/rest/company/$type",
			'total_rows' => $total_rows, // $contents['total_content'],
			'per_page' => $per_page,// $contents['max_page_size'],
			'enable_query_strings' => TRUE
			])
		);
		
		return $this->pagination->create_links();
	}
	
	const FILTER_MODES = ['nm'=>'회사이름','bno'=>'사업자번호','addr_road'=>'주소'];
	// const FILTER_COLUMNS = ['n'=>'nm', 'bn' => 'bno', 'adr'=> 'addr_road'];
	
	private function _build_like( $condition ){
		
		$like = [];
		
		if( array_flat_key_exists($condition,'filter_mode', 'filter') ) {
			
			$filter_mode = $condition['filter_mode'];
			$filter = $condition['filter'];
			if( in_array($filter_mode, array_keys(self::FILTER_MODES)) ){
				if( '' !== $filter ){
					// $column = self::FILTER_COLUMNS[$filter_mode];
					$like["$filter_mode"] = $filter;
				}
			}
		}
		return $like;
	}
	/**
	 * 각 업체별 목록 표시
	 */
  public function get_list($condition, $current_page=0){
		
		$company_simple_query = $this->simple_query->table('company');
		
		$type = $condition['type'];
		
		$where = ['type' => $type, 'is_active' => TRUE];
		
		$like = $this->_build_like($condition);
		
		$total_content = $company_simple_query->get_row_size($where);
		
		$results = $company_simple_query->get_rows([
				'fields' => 'id, b36_cd, nm, bno, type, addr_zipcode as zipcd, addr_road, addr_detail, com_classify, tel_no, if(is_partner,true,null) as is_partner',
				'where' => $where,
				'like' => $like,
				'order_by' => 'is_partner DESC, nm ASC',
				'offset'=> $current_page,
				'limit' => self::MAX_PAGE_SIZE
			])
		;
		
		return array_to_object([
			'type' => $type,
			'total_content' => $total_content,
			'current_page' => intval($current_page),
			'max_page_size' => self::MAX_PAGE_SIZE,
			'pagination_tag' => $this->_get_company_custom_pagination_tag("/imfs/rest/company/$type", $total_content, self::MAX_PAGE_SIZE),
			'contents' => $results->result
		]);
	}
	
	public function get_detail($b36_cd){
		$company_simple_query = $this->simple_query->table('company');
		return $company_simple_query->get_row([
				'fields' => 'id, b36_cd, nm, bno, type, addr_zipcode as zipcd, addr_road, addr_detail, pd_nm, tel_no, fax_no, email',
				'where' => ['b36_cd'=>$b36_cd]
			])
		;
	}
	
	public function get($field='bno', $value){
		$company_simple_query = $this->simple_query->table('company');
		return $company_simple_query->get_row([
				'fields' => 'id, b36_cd, nm, bno, type, addr_zipcode as zipcd, addr_road, addr_detail, pd_nm, tel_no, fax_no, email',
				'where' => [$field=>$value]
			])
		;
	}
	
}
