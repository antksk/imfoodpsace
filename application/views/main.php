<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

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
<meta name="viewport"
	content="width=device-width, minimum-scale=1, maximum-scale=1">
<meta name="apple-mobile-web-app-capable" content="yes" />
<!-- iphone address bar hidden -->
<meta name="mobile-web-app-capable" content="yes">
<!-- Android address bar hidden -->
<meta name="apple-mobile-web-app-status-bar-style" content="black" />

<!--  Android 5 Chrome Color-->
<meta name="theme-color" content="#EE6E73">

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

	<!--  여기에 넣어 주세요  -->

	<nav class="nav-extended">
		<div class="nav-wrapper">
			<a href="/imfs" class="brand-logo">IMFOODSPACE</a> <a href="#"
				data-activates="mobile-demo" class="button-collapse"><i
				class="material-icons">menu</i></a>

		</div>
		<div class="nav-content">
			<ul id="companyTabs" class="tabs tabs-transparent">
				<li class="tab"><a href="#factory">제조공장</a></li>
				<li class="tab"><a class="active" href="#distribution">유통업체</a></li>
				<li class="tab"><a href="#restaurant">요식업체</a></li>
				<li class="tab"><a href="#megazine">Megazine</a></li>
			</ul>
		</div>
	</nav>

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

	<div style="">
		<div id="factory" class="col s12">
			<div class="search">
				<div class="search-wrapper card z-depth-0">
					<input id="search" placeholder="상품,업체명을 검색하세요."> <i
						class="material-icons">search</i>
				</div>
			</div>
			<div data-tmp="handlebars">
				<ul class="collection">
				<? foreach($factory->contents as $c): ?>
					<li class="collection-item"><?=$c->nm?></li>
				<? endforeach; ?>
				<?=$factory->pagination_tag?>
				</ul>
			</div>
		</div>
		<div id="distribution" class="col s12">
			<div class="search">
				<div class="search-wrapper card z-depth-0">
					<input id="search" placeholder="상품,업체명을 검색하세요."> <i
						class="material-icons">search</i>
				</div>
			</div>

			<div data-tmp="handlebars">
				<ul class="collection">
				<? foreach($distribution->contents as $c): ?>
					<li class="collection-item">
						<?=$c->nm?>
					</li>
				<? endforeach; ?>
				<?=$distribution->pagination_tag?>
			
			
			
			
			</div>
		</div>

		<div id="restaurant" class="col s12">
			<div class="search">
				<div class="search-wrapper card z-depth-0">
					<input id="search" placeholder="상품,업체명을 검색하세요."> <i
						class="material-icons">search</i>
				</div>
			</div>
			<div data-tmp="handlebars">
				<ul class="collection">
				<? foreach($restaurant->contents as $c): ?>
					<li class="collection-item"><?=$c->nm?></li>
				<? endforeach; ?>
				<?=$restaurant->pagination_tag?>
				</ul>
			</div>
		</div>
		<div id="megazine" class="col s12">
			<div>
				<ul>
					<li><a>link 1</a> </li>
					<li> link 2</li>
					<li> link 3</li>
					<li> link 4</li>
				</ul>
			</div>
		</div>
	</div>
	<script id="company-template" type="text/x-handlebars-template">
		<ul class="collection">
		{{#each contents}}
			<li class="collection-item">{{./nm}}</li>
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
					console.log( e );
				}
			});
			
			// 각 업체별 페이징 처리
			const paginationEvent = ((e)=>{
				const url = $(e.currentTarget).prop('href');				
				const companyTemplate = Handlebars.compile($('#company-template').html());
				
				// $('#preloader').show();
				
				$.getJSON( url, (JSON)=>{
					// $('#preloader').hide();
					$(`#${JSON.type} div[data-tmp="handlebars"]`)
						.html(companyTemplate(JSON)) 		// JSON를 받아서 화면에 새로 그리고,
							.find('.pagination a[href]')	// 새로 그려진, pagination DOM을 찾아서
							.on('click',paginationEvent); // pagination 이벤트를 등록함
					
				});
				return false;
			});
			$('.collection > .pagination a[href]').on('click',paginationEvent);

		});
	</script>

	<script src="//ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
	<script type="text/javascript">
      // https://github.com/typekit/webfontloader
      WebFont.load({

          // For google fonts
          google: {
            families: ['Baloo Tamma']
          },
          // For early access or custom font
          custom: { families: ['Nanum Gothic'],
            urls:['http://fonts.googleapis.com/earlyaccess/nanumgothic.css']
          }

      });
  </script>

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
