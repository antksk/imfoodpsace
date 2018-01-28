<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class District_model extends CI_Model {
  const CODE_GROP = 'code.district.city';
  
  public function __construct() {
    parent::__construct();
    $this ->load ->model('common/code_model','code',TRUE);
  }
  // 시, 도 정보 
  public function city_list(){
    return $this->code->get_grp(self::CODE_GROP);
  }
  // 구 정보
  public function area_list($city_cd, $area_cd=""){
    $code_group = self::CODE_GROP;
    if( '' === $area_cd ){
      return $this->code->get_grp("$code_group.area.$city_cd");
    }else{
      return $this->code->get_cd("$city_cd$area_cd");
    }
  }
}
