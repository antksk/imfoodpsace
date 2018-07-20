<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>

<title>IMFOODSPACE</title>

<!--Import Google Icon Font-->
<!--
<link href="http://fonts.googleapis.com/icon?family=Material+Icons"
	rel="stylesheet">
-->
<!-- <link href="https://fonts.googleapis.com/css?family=Baloo+Tamma" rel="stylesheet"> -->
<!--Import materialize.css-->
<!--
<link type="text/css" rel="stylesheet"
	href="https://cdn.jsdelivr.net/materialize/0.97.7/css/materialize.min.css"
	media="screen,projection" />
-->

	<?=$style_tag?>

<!--Let browser know website is optimized for mobile-->
<meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1"/>
<meta name="apple-mobile-web-app-capable" content="yes" />
<!-- iphone address bar hidden -->
<meta name="mobile-web-app-capable" content="yes"/>
<!-- Android address bar hidden -->
<meta name="apple-mobile-web-app-status-bar-style" content="black" />

<!--  Android 5 Chrome Color-->
<meta name="theme-color" content="#EE6E73"/>

<!--
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
-->
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
		<div id="preloader" style="margin: 0 auto; padding-top: 50px; width: 80px; display: none;">
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
		<!-- 제조공장 -->
		<?=$tag_factory?>
		
		<!-- 유통업체 -->
		<?=$tag_distribution?>
		
		<!-- 요식업체 -->
		<?=$tag_restaurant?>
				
		<div id="megazine" class="col s12" style="margin-top: 50px; display: none;">
			<div class="col s12 m7">
				<div class="card horizontal">
					<div class="card-image">
						<img src="">
					</div>
					<div class="card-stacked">
						<div class="card-content">
							<p>리얼푸드 test</p>
						</div>
						<div class="card-action">
							<a href="http://www.realfoods.co.kr/section.php?sec=read">방문하기</a>
						</div>
					</div>
				</div>
			</div>
			
			<div class="col s12 m7">
				<div class="card horizontal">
					<div class="card-image">
						<img src="">
					</div>
					<div class="card-stacked">
						<div class="card-content">
							<p>오뚜기</p>
						</div>
						<div class="card-action">
							<a href="http://www.ottogi.co.kr/otgr/cyber/Cookand.jsp">방문하기</a>
						</div>
					</div>
				</div>
			</div>

			<div class="col s12 m7">
				<div class="card horizontal">
					<div class="card-image">
						<img src="">
					</div>
					<div class="card-stacked">
						<div class="card-content">
							<p>팜앤마켓</p>
						</div>
						<div class="card-action">
							<a href="http://www.farmnmarket.com/news/section?no=95">방문하기</a>
						</div>
					</div>
				</div>
			</div>
			
			<div class="col s12 m7">
				<div class="card horizontal">
					<div class="card-image">
						<img src="">
					</div>
					<div class="card-stacked">
						<div class="card-content">
							<p>푸드 트래블</p>
						</div>
						<div class="card-action">
							<a href="http://foodtravel.co.kr/category/news-trends/">방문하기</a>
						</div>
					</div>
				</div>
			</div>
		</div>
        <div id="help" class="col s12" style="margin-top: 50px; display: none;">
            <?=$inc_help_comment?>
        </div>
	</div>
	<script id="company-template" type="text/x-handlebars-template">
		<ul class="collection">
		{{#each contents}}
			<li class="collection-item">
                <a href="/imfs/company/detail/{{./type}}/{{./b36_cd}}">{{./nm}}</a>
                <!--
				{{!-- #if ./is_partner --}}
				<a href="/imfs/company/detail/{{./type}}/{{./b36_cd}}" class="secondary-content"><i class="material-icons">forward</i></a>
				{{!-- /if --}}
				-->
			</li>
		{{/each}}
		</ul>
		{{{pagination_tag}}}
	</script>

	<script>
		$(()=>{
			// 업체별 tab 키 이벤트
			$('#companyTabs').tabs({
				// swipeable: true,
				onShow: (e)=>{
					console.log( $('#companyTabs .tab .active').attr('href') );
				}
			});
			
			function renderingWithCompanyJson(url, param={}){
				const companyTemplate = Handlebars.compile($('#company-template').html());
				
				// $('#preloader').show();
				
				$.getJSON( url, param, (JSON)=>{
					// $('#preloader').hide();
					$(`#${JSON.type} div[data-tmp="handlebars"]`)
						.html(companyTemplate(JSON)) 		// JSON를 받아서 화면에 새로 그리고,
							.find('.pagination a[href]')	// 새로 그려진, pagination DOM을 찾아서
							.on('click',paginationEvent); // pagination 이벤트를 등록함
					
				});
			}
			
			// 각 업체별 페이징 처리
			const paginationEvent = ((e)=>{
				const url = $(e.currentTarget).prop('href');				
				renderingWithCompanyJson(url);
				return false;
			});
			$('.collection > .pagination a[href]').on('click',paginationEvent);
			
			
			$('.search i[data-ref]').on('click',(e)=>{
				const $icon = $(e.currentTarget);
				const ref = $icon.data('ref');
				const searchVal = $icon.siblings('input').val();
				
				// 회사명으로만 검색할수 있도록 설정
				renderingWithCompanyJson(`/imfs/rest/company/${ref}`, {m:'nm', t:searchVal});
				
				return false;
			});
			
		});
	</script>

 <?=$inc_common?>

	<style>
.search {
	left: 0;
	margin-top: 10px;
	padding: 1px 0 0;
	right: 0;
	top: 120px;
	z-index: 1;
}

.search:hover {
	background-color: #fff
}

.search .search-wrapper {
	margin: 0 12px;
	transition: margin 0.25s ease 0s;
}

.search .search-wrapper.focused {
	margin: 0
}

.search .search-wrapper input#search {
	border: 0 none;
	display: block;
	font-size: 16px;
	font-weight: 300;
	height: 45px;
	margin: 0;
	padding: 0 45px 0 15px;
	width: 80%;
}

.search .search-wrapper input#search:focus {
	outline: none
}

.search .search-wrapper i.material-icons {
	cursor: pointer;
	position: absolute;
	right: 10px;
	top: 10px;
}
</style>


</body>
</html>
