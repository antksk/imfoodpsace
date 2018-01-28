<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 화면에 표시될 데이터의 key 값에 대한 접두어(prefix) 설정
 */
$config['im_prefix'] = [
	'company' => '@CMP-'
];

/**
 * front-end 리소스 설정 관련
 * @var array $config
 */

$config['im_fe_cdn'] = [
	'js' => [
	  // jquery 3x + materialize
	  'lib'=>'https://cdn.jsdelivr.net/g/'
					.  'jquery@3.2.1'
					// .  'jquery@2.1.4'
					. ',jquery.url.parser@2.3.1(purl.min.js)'
					. ',materialize@0.98.2'
					. ',handlebarsjs@4.0.5'
					. ',momentjs@2.18.1(moment-with-locales.min.js)'
					. ',momentjs.timezone@0.5.13(moment-timezone-with-data.min.js)'
					. ',lodash@4.17.4'
					. ',js-cookie@2.2.0'
					. ',jquery.storage-api@1.7.2'
					// . ',bootstrap@3.3.7'
					// . ',bootstrap.material-design@4.0.2(bootstrap-material-design.iife.min.js'
					
	],
	'css' => [
			 'google-mdl-icon' => 'http://fonts.googleapis.com/icon?family=Material+Icons'
	   , 'materialize' => 'https://cdn.jsdelivr.net/materialize/0.98.2/css/materialize.min.css'
		 
		 // , 'bootstrap.design' => 'https://cdn.jsdelivr.net/g/bootstrap@3.3.7(css/bootstrap.min.css)'
		 /*
		 , 'bootstrap.material-design' => 'https://cdn.jsdelivr.net/g/bootstrap.material-design@4.0.2(bootstrap-material-design.min.css),bootstrap@3.3.7(css/bootstrap.min.css)'
		 */
	]
];

/**
 * im 서버상에서 사용하는 CDN 서버 주소
 * @var Ambiguous $config
 */
$config['im_sv_cdn'] = [
  'cafe24' => 'http://im221.cdn3.cafe24.com'
];

/**
 * CI 페이지 TAG 생성 초기 설정 
 */
$config['im_default_pagination'] = [
			
	'attributes' => [],
	
	'full_tag_open' => '<ul class="pagination">',
	
		'first_tag_open' => '<li class="waves-effect">',
		'first_link' => '<i class="material-icons">chevron_left</i>',
		'first_tag_close' => '</li>',
	
		'prev_tag_open' => '<li class="waves-effect">',
		'prev_link' => '<i class="material-icons">chevron_left</i>',
		'prev_tag_close' => '</li>',
		
			'cur_tag_open' => '<li class="active"><a href="#active">',
			'cur_tag_close' => '</a></li>',
			'num_tag_open' => '<li class="waves-effect">',
			'num_tag_close' => '</li>',

		'next_tag_open' => '<li class="waves-effect">',
		'next_link' => '<i class="material-icons">chevron_right</i>',
		'next_tag_close' => '</li>',
						
	
		'last_tag_open' => '<li class="waves-effect">',
		'last_link' => '<i class="material-icons">chevron_right</i>',
		'last_tag_close' => '</li>',
		
	'full_tag_close' => '</ul>'
];
