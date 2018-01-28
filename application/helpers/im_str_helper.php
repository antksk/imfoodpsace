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
if ( ! function_exists('snake_case')){
  function snake_case($str) {
    return str_replace('-', '_', strtolower(preg_replace(array('/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/'), '$1_$2', $str)));
  }
}
/**
 * https://github.com/tylerhall/html-compressor/blob/master/html-compressor.php
 */
// $data is either a handle to an open file, or an HTML string
if ( ! function_exists('html_compress')){
  function html_compress($data, $options = null) {
    if (!isset($options)) {
      $options = array();
    }
    $data .= "\n";
    $out = '';
    $inside_pre = false;
    $bytecount = 0;
    while ($line = get_line($data)) {
      $bytecount += strlen($line);
      if (!$inside_pre) {
        if (strpos($line, '<pre') === false) {
          // Since we're not inside a <pre> block, we can trim both ends of the line
          $line = trim($line);
  
          // And condense multiple spaces down to one
          $line = preg_replace('/\s\s+/', ' ', $line);
        } else {
          // Only trim the beginning since we just entered a <pre> block...
          $line = ltrim($line);
          $inside_pre = true;
          // If the <pre> ends on the same line, don't turn on $inside_pre...
          if ((strpos($line, '</pre') !== false) && (strripos($line, '</pre') >= strripos($line, '<pre'))) {
            $line = rtrim($line);
            $inside_pre = false;
          }
        }
      } else {
        if ((strpos($line, '</pre') !== false) && (strripos($line, '</pre') >= strripos($line, '<pre'))) {
          // Trim the end of the line now that we found the end of the <pre> block...
          $line = rtrim($line);
          $inside_pre = false;
        }
      }
      // Filter out any blank lines that aren't inside a <pre> block...
      if ($inside_pre || $line != '') {
        $out .= $line . "\n";
      }
    }
  
    // Remove HTML comments...
    if (array_key_exists('c', $options) || array_key_exists('no-comments', $options)) {
      $out = preg_replace('/(<!--.*?-->)/ms', '', $out);
      $out = str_replace('<!>', '', $out);
    }
    // Perform any extra (unsafe) compression techniques...
    if (array_key_exists('x', $options) || array_key_exists('extra', $options)) {
      // Can break layouts that are dependent on whitespace between tags
      $out = str_replace(">\n<", '><', $out);
    }
    // Remove the trailing \n
    $out = trim($out);
    // Output either our stats or the compressed data...
    if (array_key_exists('s', $options) || array_key_exists('stats', $options)) {
      $echo = '';
      $echo .= "Original Size: $bytecount\n";
      $echo .= "Compressed Size: " . strlen($out) . "\n";
      $echo .= "Savings: " . round((1 - strlen($out) / $bytecount) * 100, 2) . "%\n";
      echo $echo;
    } else {
      if (array_key_exists('o', $options) || array_key_exists('overwrite', $options)) {
        if ($GLOBALS['argc'] > 1 && is_writable($GLOBALS['argv'][$GLOBALS['argc'] - 1])) {
          file_put_contents($GLOBALS['argv'][$GLOBALS['argc'] - 1], $out);
          return true;
        } else {
          return "Error: could not write to " . $GLOBALS['argv'][$GLOBALS['argc'] - 1] . "\n";
        }
      } else {
        return $out;
      }
    }
  }
}
if ( ! function_exists('get_est_html')){
	function get_est_html($title, $ests){
		$html = [];
		
		array_push($html,"<h5>$title</h5>");
		array_push($html,'<ul class="collection">');
		
		foreach($ests as $e){
			array_push($html,'<li class="collection-item">');
			array_push($html,"<span>$e->id</span>");
			array_push($html,"<a class=\"waves-effect waves-light\" href=\"#modalEst\" data-id=\"$e->id\" data-step=\"$e->step\" data-mode=\"$e->mode\">$e->nm</a>");
			array_push($html,'</li>');
		}
		
		array_push($html,'</ul>');
		
		return implode($html);
	}
}

// Returns the next line from an open file handle or a string
if ( ! function_exists('get_line')){
  function get_line(&$data) {
    if (is_resource($data)) {
      return fgets($data);
    }
    if (is_string($data)) {
      if (strlen($data) > 0) {
        $pos = strpos($data, "\n");
        $return = substr($data, 0, $pos) . "\n";
        $data = substr($data, $pos + 1);
        return $return;
      } else {
        return false;
      }
    }
  
    return false;
  }
}
