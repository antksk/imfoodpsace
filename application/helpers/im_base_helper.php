<?php
/**
 * $b16 = 'ABCDEF00001234567890';
 * $b36 = str_baseconvert($b16, 16, 36);
 * echo ("$b16 (hexadecimal) = $b36 (base-36) \\n");
 *
 * $uuid = 'ABCDEF01234567890123456789ABCDEF';
 * $ukey = str_baseconvert($uuid, 16, 36);
 * echo ("$uuid (hexadecimal) = $ukey (base-36) \\n"); 
 * 
 * 
 * ABCDEF00001234567890 (hexadecimal) = 3o47re02jzqisvio (base-36)
 * ABCDEF01234567890123456789ABCDEF (hexadecimal) = a65xa07491kf5zyfpvbo76g33 (base-36)
 * 
 * 
 * http://php.net/manual/kr/function.base-convert.php
 * 
 */
if (! function_exists ( 'str_baseconvert' )) {
  function str_baseconvert($str, $frombase = 10, $tobase = 36) {
    $str = trim ( $str );
    if ('0' == $str) {
      return '0';
    }
    if (intval ( $frombase ) != 10) {
      $len = strlen ( $str );
      $q = 0;
      for($i = 0; $i < $len; $i ++) {
        $r = base_convert ( $str [$i], $frombase, 10 );
        $q = bcadd ( bcmul ( $q, $frombase ), $r );
      }
    } else
      $q = $str;
    
    if (intval ( $tobase ) != 10) {
      $s = '';
      while ( bccomp ( $q, '0', 0 ) > 0 ) {
        $r = intval ( bcmod ( $q, $tobase ) );
        $s = base_convert ( $r, 10, $tobase ) . $s;
        $q = bcdiv ( $q, $tobase, 0 );
      }
    } else
      $s = $q;
    
    return $s;
  }
}
if (! function_exists ( 'base_36' )) {
  function base_36($num, $pad_length = FALSE, $pad = '0') {
    $base_val = str_baseconvert ( $num );
    if (is_int ( $pad_length )) {
      return str_pad ( $base_val, $pad_length, $pad, STR_PAD_LEFT );
    }
    return $base_val;
  }
}

if (! function_exists ( 'current_date' )) {
  function current_date() {
    return date ( 'Y-m-d H:i:s' );
  }
}

if (! function_exists ( 'array_to_object' )) {
  function array_to_object($d) {
    if (is_array ( $d )) {
      /*
       * Return array converted to object
       * Using __FUNCTION__ (Magic constant)
       * for recursive call
       */
      return ( object ) array_map ( __FUNCTION__, $d );
    } else {
      // Return object
      return $d;
    }
  }
}
if (! function_exists ( 'object_to_array' )) {
  function object_to_array($d) {
    if (is_object ( $d )) {
      // Gets the properties of the given object
      // with get_object_vars function
      $d = get_object_vars ( $d );
    }
    
    if (is_array ( $d )) {
      /*
       * Return array converted to object
       * Using __FUNCTION__ (Magic constant)
       * for recursive call
       */
      return array_map ( __FUNCTION__, $d );
    } else {
      // Return array
      return $d;
    }
  }
}

if (! function_exists ( 'cdn_url' )) {
  function cdn_url($url) {
    $CI = & get_instance ();
    $CI->load->config ( 'front_end' );
    $cdn_server = $CI->config->item('im_sv_cdn');
    $cafe24_cdn_base_url = $cdn_server['cafe24'];
    return "$cafe24_cdn_base_url/$url";
  }
}