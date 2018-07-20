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
	<div style="padding-top: 50px;">
  	<div id="sent">
			<?//= get_est_html('업체 별로 요청 완료 견적 목록', $ests['sentCmp'])?>
			<?//= get_est_html('업체 별로 요청한 견적 목록', $ests['sentReq'])?>
  	</div>
  	<div id="inbox">
			<?//= get_est_html('요청 받은 견적 목록', $ests['inbox'])?>
  	</div>
  </div>

  <script id="estimate-list-template" type="text/x-handlebars-template">
		<h5>{{title}}</h5>
		<ul class="collection">
		{{#each ests}}
			<li class="collection-item">
       <span>{{id}}</span>
				<a class="waves-effect waves-light" href="#modalEst" data-id="{{id}}" data-step="{{step}}" data-mode="{{mode}}">{{nm}}</a>
       <span class="right"> {{reg_date}}</span>
			</li>
		{{/each}}
		</ul>
	</script>
  
  <script id="estimate-edit-template" type="text/x-handlebars-template">
  	<table>
    	<thead>
    		<tr>
    				<th data-field="no">no.</th>
    				<th data-field="id">제품명</th>
    				<th data-field="count">수량</th>
    				<th data-field="price">판매금액</th>
    				<th data-field="price_dc">할인금액</th>
    		</tr>
    	</thead>
    	<tbody>
      	{{#each estimate}}
      		<tr>
      			<td>{{@index}}</td>
      			<td>{{./nm}};</td>
      			<td>{{currency ./count}}</td>
      			<td>{{currency ./price_sale}}</td>
      			<td>
            <input type="number" data-id="{{./id}}" data-count="{{./count}}", data-ps="{{./price_sale}}" value="{{./price_sale}}" maxlength="3" min="0" max="99999" style="width:50px;height:1.5rem;margin:0;" {{#if ./disabled}}disabled {{/if}}/>
      			</td>
      		</tr>
      	{{/each}}
      </tbody>
    </table>
  </script>
	
	<script id="estimate-template" type="text/x-handlebars-template">
  	<table>
    	<thead>
    		<tr>
    				<th data-field="no">no.</th>
    				<th data-field="id">제품명</th>
    				<th data-field="count">수량</th>
    				<th data-field="price">판매금액</th>
    				<th data-field="price_dc">할인금액</th>
    		</tr>
    	</thead>
    	<tbody>
      	{{#each estimate}}
      		<tr>
      			<td>{{@index}}</td>
      			<td>{{./nm}};</td>
      			<td>{{currency ./count}}</td>
      			<td>{{currency ./price_sale}}</td>
      			<td>{{currency ./price_dc}}</td>
      		</tr>
      	{{/each}}
      </tbody>
    </table>
  </script>
  
  <div id="modalEst" class="modal" style="width:98%;">
    <div class="modal-content">
      <h4 class="title"> </h4>
      <div class="content">
      </div>
    </div>
    <div class="modal-footer">
      <a id="confrim"  href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">확인</a>
      <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">취소</a>
    </div>
  </div>
  
	<script>
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
		
  	function restMyEst( mode='sent' ){
  	  const userLocalStorage = $.initNamespaceStorage('@IM_USER').localStorage;	
  	  // alert( userLocalStorage.get('user')  );
  	  if( userLocalStorage.isSet('user') ){
    		$.post(`/imfs/user/rest_my_ests/${mode}`,{jwt:userLocalStorage.get('user')},(json)=>{

					const estimateListTemplate = Handlebars.compile($('#estimate-list-template').html());
					
					const inbox = _.get(json,'inbox',[]);
					const sentCmp = _.get(json,'sentCmp',[]);
					const sentReq = _.get(json,'sentReq',[]);
					
					const sent = estimateListTemplate({title: '업체 별로 요청 완료 견적 목록', ests: sentCmp}) + 
						estimateListTemplate({title: '업체 별로 요청한 견적 목록', ests: sentReq});
					
					$('#sent').html(sent);
					
					$('#inbox').html(	estimateListTemplate({title: '요청 받은 견적 목록', ests: inbox}) );
					
    		});
  	  }
  	};

  	// restMyEst('sent', (json)=>console.log(json));
  	
  	restMyEst($('#estTabs .tab .active').data('mode'));

  	
  	$(()=>{
			
			const usr = <?=json_encode($im['usr'])?>;
			// console.log( 'com_info : ', <?=json_encode($im['com'])?> );
			// console.log( 'usr_info : ', usr );
			
			const getEstimate = function(merchandise, est, inputDisabled ){
				return _(est)
					.filter(e=>_.has(merchandise,e.id))
					.map((e)=>{
						const m = _.first(merchandise[e.id]);
						return {
							id: e.id,
							nm: m.nm,
							price_sale: e.price,
							price_dc: e.price_dc,
							count: e.count,
							disabled: inputDisabled
						};
					})
					.value();
			};
			
			const estimateActions = {
				
					sentReq: function(merchandise, est){
						const $modalTarget = $('#modalEst');
						const step = $modalTarget.data('step');
						const estimateTemplate = Handlebars.compile($('#estimate-template').html());
						$('.content', $modalTarget).html( estimateTemplate({estimate:getEstimate(merchandise, _.get(est,'to.est.data'), (1 === step))}) );
					},
					
					sentCmp: function(merchandise, est){
						console.log( 'sent-cmp : ', _.get(est,'to.est.data') );
						
						const $modalTarget = $('#modalEst');
						const step = $modalTarget.data('step');
						const estimateTemplate = Handlebars.compile($('#estimate-template').html());
						$('.content', $modalTarget).html( estimateTemplate({estimate:getEstimate(merchandise, _.get(est,'to.est.data'), (1 === step))}) );
					},
					// 받은 견적함 > 요청 받은 견적
					inbox: function(merchandise, est){
						const $modalTarget = $('#modalEst');
						const step = $modalTarget.data('step');
						const estimateTemplate = Handlebars.compile($('#estimate-edit-template').html());
						$('.content', $modalTarget).html( estimateTemplate({estimate:getEstimate(merchandise, _.get(est,'to.est.data'), (1 === step))}) );
						
					}
			};

  		$('#modalEst').modal({
	     dismissible: true, // Modal can be dismissed by clicking outside of the modal
	     opacity: .5, // Opacity of modal background
	     inDuration: 200, // Transition in duration
	     outDuration: 200, // Transition out duration
	     startingTop: '4%', // Starting top style attribute
	     endingTop: '10%', // Ending top style attribute
	     ready: function(modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
	       const $modalTarget = $(trigger);
	       const estId = $modalTarget.data('id');
	       const mode = $modalTarget.data('mode');
				 
	       $(modal).data('id', estId);
				 $(modal).data('step', $modalTarget.data('step'));
				 
				 const step = $modalTarget.data('step');
				 if( 'inbox' === mode ){
					 const inputDisabled = (1 === step);
					 if( inputDisabled ){
						$('#confrim').addClass('hide');
					 }else{
						$('#confrim').removeClass('hide');
					 }
				 }else{
					 $('#confrim').addClass('hide');
				 }
					 
	       $.getJSON(`/imfs/estimate/get/${estId}`,(json)=>{
	    	    const merchandise = _.groupBy(json.est.merchandise,(m)=>m.id);
						if( _.has(estimateActions, mode) ){
							// console.log( 'est : ', json );
							
							estimateActions[mode](merchandise, {
								from : {
									com : _.get(json,'from.com'),
									usr : _.get(json,'from.usr'),
									est : _.get(json,'est.from') 
								},
								to : {
									com : _.get(json,'to.com'),
									usr : _.get(json,'to.usr'),
									est : _.get(json,'est.to') 
								},
							});
						}
	        });
	      }
	    });

	    $('#confrim').on('click',function(e){
		    const $modalEst = $('#modalEst');
		    const estId = $modalEst.data('id');
		    const resEst = $('input[type="number"]',$modalEst).map((i,e)=>{
  		    return {
  			    id: $(e).data('id'),
  			    count: $(e).data('count'),
  			    price: $(e).data('ps'),
  			    price_dc: parseInt($(e).val())
  		    };
		    }).get(); 
		    console.log( resEst, 'resEst' );


		    $.post(`/imfs/estimate/res/${estId}`, {
			    est_res : JSON.stringify(resEst),
			    usr_to : usr.id
		    },(json)=>{
			    console.log( json, _.get(json,'result',false));
					if( _.get(json,'result',false) ){
						const id = _.get(json,'id');
						$(`#inbox .collection-item a[data-id=${id}]`)
							.data('step',1)
							.parent()
							.remove()
							// .css('background-color', '#dad8d8')
						;
					}
		    });
		    
	    	$modalEst.modal('close');
	    	return false;
	    });
  	  	
  		$('#estTabs').tabs({
  			onShow: (e)=>{
  	  		const $activeTab = $('#estTabs .tab .active');

  	  		restMyEst($activeTab.data('mode'));
			
  			}
  		});
		});
	</script>

</body>
</html>
