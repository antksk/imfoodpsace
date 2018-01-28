<div id="<?=$company->type?>" class="col s12" style="margin-top:50px;">
	<div class="search">
		<div class="search-wrapper card z-depth-0">
			<input placeholder="상품,업체명을 검색하세요."> 
			<i class="material-icons" data-ref="<?=$company->type?>">search</i>
		</div>
	</div>
	
	<div data-tmp="handlebars">
		<ul class="collection">
		<? foreach($company->contents as $c): ?>
			<li class="collection-item">
				<a href="/imfs/company/detail/<?=$c->type?>/<?=$c->b36_cd?>"><?=$c->nm?></a>
                <!--
				<? // if($c->is_partner): ?>
				<a href="/imfs/company/detail/<?=$c->type?>/<?=$c->b36_cd?>" class="secondary-content"><i class="material-icons">forward</i></a> 
				<? // endif; ?>
				-->
			</li>
		<? endforeach; ?>
		<?=$company->pagination_tag?>
		</ul>
	</div>
</div>