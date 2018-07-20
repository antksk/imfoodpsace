<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<title>IMFOODSPACE</title>
	<?=$style_tag?>

<!--Let browser know website is optimized for mobile-->
<meta name="viewport"
	content="width=device-width, minimum-scale=1, maximum-scale=1">
<meta name="apple-mobile-web-app-capable" content="yes" />
<!-- iphone address bar hidden -->
<meta name="mobile-web-app-capable" content="yes">
<!-- Android address bar hidden -->
<meta name="apple-mobile-web-app-status-bar-style" content="black" />

<!--  Android 5 Chrome Color-->
<meta name="theme-color" content="#EE6E73">

<style>
html {
	font-family: 'Nanum Gothic', 'Baloo Tamma';
}

.im-collection-item {
	box-sizing: content-box;
	height: 37px;
	line-height: 37px;
}
</style>
</head>

<body>

	<?=$script_tag?>
	<?=$inc_navbar?>
	
	<!-- 로딩 ui -->
		<div id="preloader"
			style="margin: 0 auto; padding-top: 50px; width: 80px; display: none;">
			<div class="preloader-wrapper active">
				<div class="spinner-layer spinner-red-only">
					<div class="circle-clipper left">
						<div class="circle"></div>
					</div>
					<div class="gap-patch">
						<div class="circle"></div>
					</div>
					<div class="circle-clipper right">
						<div class="circle"></div>
					</div>
				</div>
			</div>
		</div>
		
	<div style="padding-bottom: 50px;">
		<div id="authKeyUI" class="container hide">
			접속할 사용자 정보가 없습니다. <br/>
			imfoodspace에 요청하여 인증 키를 받아 아래에 입력해주세요.
			<input type="text"/>
			<button class="btn"> 검증 </button>
		</div>
	</div>
	<form id="authForm" method="POST" action="/imfs/user/my_ests">
		<input type="hidden" name="jwt" value=""/>
	</form>
	<script>
		! function( userLocalStorage ){
			
			function goingToMyEst (){
				const $authForm = $('#authForm');
				$('input[name="jwt"]', $authForm).val(userLocalStorage.get('user'));
				$authForm.get(0).submit();
			}
  		
  		if(userLocalStorage.isSet('user')){
				goingToMyEst();
  			// location.href='/imfs/user/my_ests';
  		}else{
  			const $authKeyUI = $('#authKeyUI').removeClass('hide');
  			$('.btn', $authKeyUI).on('click',(e)=>{
  				const authKey = $('input[type="text"]',$authKeyUI).val();
  
  				$.post('/imfs/user/auth_key_and_jwt',{ak:authKey},json=>{
  					if( '' != json.jwt ){
  						userLocalStorage.set('user',json.jwt);
  						Cookies.set('im_user_jwt',json.jwt); 
  						// location.href='/imfs/user/my_ests';
							goingToMyEst();
  					}else{
							alert('인증키가 적합하지 않습니다.');
						}
  				});
  				
  			});
  		}
		}($.initNamespaceStorage('@IM_USER').localStorage);
	</script>

</body>
</html>
