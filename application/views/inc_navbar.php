<div class="navbar-fixed">
	<nav class="nav-extended">
		<div class="nav-wrapper">
			<a id="imfsLogo" href="#" class="brand-logo">IMFOODSPACE</a>
			<a href="/imfs/user"  class="button-collapse">
				<i class="material-icons">perm_identity</i>
			</a>
      <!-- 
			<a id="login" href="#" class="button-collapse right hide">
				<i class="material-icons">perm_identity</i>
			</a>
			-->
			<a id="logout" href="#" class="button-collapse right hide">
				<i class="material-icons">exit_to_app</i>
			</a>
			<script>
				! function(userLocalStorage){
      		
      		if(userLocalStorage.isSet('user')){
          		
      			$('#logout').removeClass('hide').on('click',e=>{
      				userLocalStorage.removeAll();
      				Cookies.remove('im_user_jwt');
      				location.href='/imfs/user';
      			});
      			
      		}else{
      			$('#login').removeClass('hide').on('click',e=>{
      				location.href='/imfs/user';
      			});
      		}
				}($.initNamespaceStorage('@IM_USER').localStorage);
    	</script>
		</div>
		<? if( 'main' == $mode ): ?>
		<div class="nav-content">
			<ul id="companyTabs" class="tabs tabs-transparent">
				<li class="tab"><a href="#factory">제조공장</a></li>
				<li class="tab"><a class="active" href="#distribution">유통업체</a></li>
				<li class="tab"><a href="#restaurant">요식업체</a></li>
				<li class="tab"><a href="#megazine">Megazine</a></li>
				<li class="tab" style="display: none;"><a href="#help">help</a></li>
			</ul>
		</div>
		<? endif; ?>
		<? if( 'user_my_ests' == $mode ): ?>
		<div class="nav-content" >
			<? if( 'distribution' == $com_type ): ?>
			<ul id="estTabs" class="tabs tabs-transparent">
				<li class="tab"><a class="active" data-mode="inbox" href="#inbox">받은 견적함</a></li>
				<li class="tab"><a data-mode="sent" href="#sent">보낸 견적함</a></li>
			</ul>
			<? else: ?>
			<ul id="estTabs" class="tabs tabs-transparent">
				<li class="tab"><a class="active" data-mode="sent" href="#sent">보낸 견적함</a></li>
				<li class="tab"><a data-mode="inbox" href="#inbox">받은 견적함</a></li>
			</ul>
			<? endif; ?>
		</div>
		<? endif; ?>
	</nav>
</div>