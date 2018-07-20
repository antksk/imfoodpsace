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


		<ul id="estimate-list" class="collapsible popout">
			<!-- 여기에 다이나믹 HTML로 고객이 요청한 견적 내용 추가 됨 -->
		</ul>
		
		<?/* 미리 등록된 회사 정보 검증 */?>
		<div id="checkBno" class="container hide">
		
			<form class="form-horizontal">
				<div class="form-group">
					<label for="bno" class="bmd-label-floating">사업자 번호로 사용자 검증</label>
					<input type="number" id="bno" class="form-control validate" value="1380281016" pattern="[1-9][0-9]{9}" title="'-'를 뺀 사업자 번호 10자리를 입력해주세요." placeholder="'-'를 뺀 사업자 번호 10자리를 입력해주세요." placeholder="사업자번호를 등록해주세요." required autofocus>
				</div>
			</form>
			<button id="confrimBno" class="btn btn-primary btn-block"> 사업자 번호 검증 하기 </button>
			<script>
				$('#confrimBno').on('click',(e)=>{
					$('#confrimBno').addClass('disabled');
					$.getJSON('/imfs/rest/company/exist',{bno:$('#bno').val()},(c)=>{
						console.log( c );
						if( c.exist ){
							$('#checkBno').addClass('hide');
							$('#regUser').removeClass('hide');
							$('#com_id').val(c.result.id);
							$('#com_b36_cd').val(c.result.b36_cd);
						}else{
							alert('등록된 사업자 번호가 없습니다. 영업담당자에게 문의 주세요.');
							$('#confrimBno').removeClass('disabled');
						}
						console.log( c );
					});
					return false;
				});
			</script>
		</div>
		
		<div id="submitEst" class="container hide">
			<button id="submitEst" class="btn btn-primary btn-block"> 각 업체 별로 견적서 요청하기 </button>
		</div>
		
		<?/* 사용자 정보 입력 */?>
		<div id="regUser" class="container hide">
			<form class="form-horizontal">
				<div class="form-group">
					<input type="hidden" id="com_id" value=""/>
					<input type="hidden" id="com_b36_cd" value=""/>
					<label for="nm" class="bmd-label-floating">이름</label>
					<input type="text" id="nm" value="test" class="form-control validate" placeholder="고객 이름을 등록해주세요." max="10" required autofocus>
				</div>
				<div class="form-group">
					<label for="tel_no" class="bmd-label-floating">전화번호</label>
					<input type="tel" id="tel_no" value="1111111111111" class="form-control validate" pattern="[0-9]{10}[0-9]?" title="'-'를 뺀 휴대전화 번호 10~11자리를 입력해주세요." placeholder="전화번호를 등록해주세요." required autofocus>
				</div>
				<div class="form-group">
					<label for="email" class="bmd-label-floating">이메일주소</label>
					<input type="email" id="email" value="a@a.com" class="form-control validate" placeholder="이메일주소를 등록해주세요." required autofocus>
				</div>
			</form>
			<button id="submitEstWithRegUser" class="btn btn-primary btn-block"> 각 업체 별로 견적서 요청하기 </button>
			<script>
				// 처음 등록하는 경우 
				$('#submitEstWithRegUser').on('click',(e)=>{
					$('#submitEstWithRegUser').addClass('disabled');
					if( (false == $('#regUser .validate').hasClass('invalid')) && (-1 === _.indexOf($('#regUser input').map((i,e)=>e.value).get(),'')) ){
						const estInfo = estCollector();
						const postData = {
							user:{
								com_id: $('#regUser #com_id').val(),
								com_b36_cd: $('#regUser #com_b36_cd').val(),
								nm: $('#regUser #nm').val(),
								tel_no: $('#regUser #tel_no').val(),
								email: $('#regUser #email').val()
							},
							est : estInfo
						};
						
						$.post('estimate/reg', { 'est_req':  JSON.stringify(postData) }, (e)=>{
							
							userLocalStorage.set('user',e.jwt);
							
							// ## 사용자가 선택한 모든 제품 정보 제거
							_.each(comLocalStorage(),(n,i)=>n.localStorage.removeAll());
							
							alert('정상적으로 견적요청이 진행되었습니다.\n마이페이지에서 견적내용을 확인하세요.');
							location.href='/imfs';
						});
					}else{
						alert('잘못 등록된 사용자 정보가 있습니다.');
						$('#submitEstWithRegUser').removeClass('disabled');
					}
					return false;
					
				});
			</script>
		</div>
		
		
		<script id="estimate-template" type="text/x-handlebars-template">
			<div class="collapsible-header active">{{com.nm}} <span data-com="{{com.id}}" style="color:red" class="right total" data-total="{{com.price_total}}">{{currency com.price_total}}</span></div>
			<div class="collapsible-body">
				<strong> 업체의 사정에 따라 설정한 금액 정보에 차이가 있을 수 있습니다. </strong>
				<table>
					<thead>
						<tr>
								<th data-field="id">제품명</th>
								<th data-field="name">수량</th>
								<th data-field="price">금액</th>
						</tr>
					</thead>
					<tbody>
					{{#each estimate}}
						<tr>
							<td>{{./nm}}[{{./head_unit}}]</td>
							 <td>
                  {{!--
                <input id="{{./id}}" data-com="{{../com.id}}" data-price="{{./price_sale}}" type="number" value="1" maxlength="3" min="0" max="999" style="width:50px;"/>
                  --}}
                <select id="{{./id}}" data-id="count" data-com="{{../com.id}}" data-price="{{./price_sale}}" style="display:block;">
                  <?for($i=1;50 >=$i; ++$i):?>
                  <option value="<?=$i?>"><?=$i?></option>
                  <?endfor?>
                </select>
              </td>
                            <td>
                                <span>{{currency ./price_sale}}</span> 원
                                <br/><span data-id="{{./id}}" style="color:blue;">{{currency ./price_sale}}</span> 원
                            </td>
						</tr>
					{{/each}}
         </tbody>
				</table>
			</div>
		</script>
		
		<script>
			function comLocalStorage(){
				return _.filter($.namespaceStorages,(e,ns)=>/^<?=$company_prefix?>.+/i.test(ns));
			}
			// 고객이 요청한 견적 내용을 수집함
			function estCollector(){
				return $('#estimate-list li').map((i,e)=>{
						const $self = $(e);
						const commpany_b36_cd = $self.attr('id');
						const commpany_com_id = $self.data('com_id');
						const total = $('.total', $self).data('total');

						const est = $('[data-id="count"]', $self).map((i,e)=>{
							const $self = $(e); 
							return { 
								id: $self.attr('id'), 
								count: parseInt($self.val()), 
								price: $self.data('price') 
							}; 
						}).get();
						
						return {
							company : {
									b36_cd : commpany_b36_cd,
									com_id : commpany_com_id
								},
								total: total,
								est: est
							};
					}).get();
			}
			
			// 특정 숫자에 comma 표시
  		function comma( value ){
  			return String(value).replace(/(\d)(?=(\d{3})+$)/g, '$1,');
  		}
  		
  		Handlebars.registerHelper('currency', (context, options ) => {
  			if( _.isNull(context) || _.isUndefined(context) ){
  				return 0;
  			}
  			
  			return comma(context);
  		});
		</script>
		
		<script>
			// native local storage를 기준으로 namespaceStorages 설정
			/* ################################################################################### */
			const userLocalStorage = $.initNamespaceStorage('@IM_USER').localStorage;

			$.each(localStorage,(k)=>{
				if(/^<?=$company_prefix?>.+/i.test(k)){
					$.initNamespaceStorage(k);
				} 
			});
			/* ################################################################################### */
			
			const estimateTemplate = Handlebars.compile($('#estimate-template').html());
			
			const namespaceMapper = (ns)=>{
				const s = ns.localStorage;
                if(s.isSet('_company')){
					const company = s.get('_company');
					const 
						items = _.filter(s.keys(),e=>/^[0-9]+$/i.test(e))
					, estimate = _.map(items,(e)=>s.get(e))
					;
					
					const price_total = _(estimate).map(e=>parseInt(e.price_sale)).sum();

					return {
						com: {
							id: company.dtl.b36_cd,
							com_id: company.dtl.id,
							nm: company.com_nm,
							price_total: price_total
						},
						estimate: estimate
					};
				}else{
					return {
						com:{
							price_total: 0
						}
					};
				}
			};

			const estimateList = comLocalStorage()
				.map(namespaceMapper)
				.filter(e=>e.com.price_total > 0)
			;
			// console.log( estimateList );
			// 고객이 선택한 제품에 대해 화면에 표시
			_.forEach(estimateList,(e)=>{
				$(`<li id="${e.com.id}" data-com_id="${e.com.com_id}"/>`)
					.html(estimateTemplate(e))
					.appendTo($('#estimate-list'))
				;
			});
			
			// 고객이 수량 수정시 호출
			$('#estimate-list [data-id="count"]').on('change',(e)=>{
				const $self = $(e.currentTarget);
				const 
					com = $self.data('com'),
					item = {
						id : $self.attr('id'),
						count : $self.val(),
						price : $self.data('price')
					}
				;

                // 선택한 항목에 대한 단가 * 수량 계산

                $(`span[data-id="${item.id}"]`, $self.parent().parent()).text( comma(item.count * item.price) );

                // console.log( comma(item.count * item.price), item.count, item.price, 'test');

                // 총 합계 계산
				const totalList = $(`#estimate-list #${com} [data-id="count"]`).map((i, e)=>{
						return {
							 count: parseInt($(e).val())
						 , price: $(e).data('price')
						}
					})
					, total = _(totalList).map(e=>e.count*e.price).sum();
				
				$(`#${com} .total`).data('total',total).text(comma(total));


			});

			if( _.isEmpty(estimateList) ){
				alert('사용자가 선택한 제품 정보가 없습니다.');
				location.href='/imfs';
			}else{
				// 기본 사용자 정보가 저장되지 않은 경우
				if( false === userLocalStorage.isSet('user') ){
					$('#checkBno').removeClass('hide');
				}else{
					/// jwt 설정된 경우	
					$('#submitEst').removeClass('hide').on('click',(e)=>{
						const estInfo = estCollector();
						const postData = {
							jwt:userLocalStorage.get('user'),
							est : estInfo
						};
						$.post('estimate/reg', { 'est_req':  JSON.stringify(postData) }, (e)=>{

							userLocalStorage.set('user',e.jwt);
							
							// ## 사용자가 선택한 모든 제품 정보 제거
							_.each(comLocalStorage(),(n,i)=>n.localStorage.removeAll());
							
							alert('정상적으로 견적요청이 진행되었습니다.\n마이페이지에서 견적내용을 확인하세요.');
							location.href='/imfs';
						});
					});
				}
			}
			
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

</body>
</html>
