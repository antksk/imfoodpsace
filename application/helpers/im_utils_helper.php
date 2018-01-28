<?php
if ( ! function_exists('json_result')){
  function json_result( $result ){
      header('Content-Type: application/json');
      echo json_encode( $result );
  }
}
if ( ! function_exists('array_flat_key_exists')){
  /**
   * 
   * @return boolean
   * @comment
   *  첫번째 파라미터(args[0])에 전달된 배열을 기준으로 
   *  지정된(args[1...]) 키들이 존재하는 지확인하여, TRUE, FLASE 리턴
   *  단일 key, value형태의 array만 조사하기 때문에 속드는 O(n)
   *  
   * @uses
   *  $condition = array('f'=>null,'a'=>null, 'c'=>null);
   *  array_flat_key_exists($condition, 'f') // result : TRUE
   *  array_flat_key_exists($condition, 'f', 'a') // result : TRUE
   *  array_flat_key_exists($condition, 'f', 'c', 'z') // result : FLASE
   */
  function array_flat_key_exists(){
    $args = func_get_args();
    $count = count($args);
    if(  2 <=  $count ){
      $condition = $args[0];
      for( $i = 1; $count > $i; ++$i ){
        $key = $args[$i];
        if( !array_key_exists($key, $condition) ){
          return FALSE;
        }
      }
      return TRUE;
    }
    return FALSE;
  }
}
if ( ! function_exists('im_redirect')){
  function im_redirect($url, $param=array()){
    if( is_array($param) && 0 < count($param) ){
      $builded_param = http_build_query($param);
      redirect("$url?$builded_param");
    }
    redirect($url);
  }
}

if ( ! function_exists('is_im_dev')){
  function is_im_dev(){
    return 'development' === ENVIRONMENT; 
  }
}

if ( ! function_exists('is_im_prod')){
  function is_im_prod(){
    return 'production' === ENVIRONMENT; 
  }
}

if ( ! function_exists('is_im_exists_view')){
  function is_im_exists_view($view_path){
    return file_exists(VIEWPATH . $view_path . '.php');
  }
}

