<?php
class OpenAPI {
	private static function get_url_contents($url) {
		$URL_parsed = parse_url ( $url );
		$host = $URL_parsed ["host"];
		$port = $URL_parsed ["port"];
		if ($port == 0)
			$port = 80;
		$path = $URL_parsed ["path"];
		if ($URL_parsed ["query"] != "")
			$path .= "?" . $URL_parsed ["query"];
		$out = "GET $path HTTP/1.0rn";
		$out .= "Host: $hostrn";
		$out .= "Connection: Closernrn";
		$fp = fsockopen ( $host, $port, $errno, $errstr, 30 );
		if (! $fp) {
			echo "$errstr ($errno)<br>n";
		} else {
			fputs ( $fp, $out );
			$body = false;
			while ( ! feof ( $fp ) ) {
				$s = fgets ( $fp, 128 );
				if ($body)
					$in .= $s;
				if ($s == "rn")
					$body = true;
			}
			fclose ( $fp );
			return $in;
		}
		return '';
	}
	
	public static function generate_state() {
		$mt = microtime ();
		$rand = mt_rand ();
		return md5 ( $mt . $rand );
	}
	public static function redirect($url, $param = array()) {
		im_redirect ( $url, $param );
	}
	public static function post_contents($url, $param = array()) {
		return file_get_contents ( "$url?", FALSE, stream_context_create ( array (
				'http' => array (
						'method' => 'POST',
						'header' => 'Content-type: application/x-www-form-urlencoded',
						'content' => http_build_query ( $param ) 
				) 
		) ) );
// 		return OpenAPI::get_url_contents( "$url?" . stream_context_create ( array (
// 				'http' => array (
// 						'method' => 'POST',
// 						'header' => 'Content-type: application/x-www-form-urlencoded',
// 						'content' => http_build_query ( $param )
// 				)
// 		) ) );
	}
	public static function post($url, $param = array(), $assoc = FALSE) {
		return json_decode ( OpenAPI::post_contents ( $url, $param ), $assoc );
	}
	public static function authorization($url, $authorization, $assoc = FALSE) {
		$result = @file_get_contents ( $url, FALSE, stream_context_create ( array (
				'http' => array (
						'method' => 'POST',
						'header' => "Authorization: $authorization" 
				) 
		) ) );
// 		$result = @OpenAPI::get_url_contents( $url, FALSE, stream_context_create ( array (
// 				'http' => array (
// 						'method' => 'POST',
// 						'header' => "Authorization: $authorization"
// 				)
// 		) ) );
		if (FALSE === $result) {
			$e = new stdClass ();
			$e->error = 'unexpect authorization';
			return $e;
		} else {
			return json_decode ( $result, $assoc );
		}
	}
}
