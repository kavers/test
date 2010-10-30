<li id="blog_experts" class="block asparagus_green">
	<div class="title"><a href="{router page='people'}expert/" class="link"><h1>{$aLang.usercats_category_EXPERTS}</h1></a><a href="#" class="close_block"><img src="{cfg name='path.static.skin'}/img/minus.gif" width="18" height="18" alt="Свернуть блок" title="Свернуть блок"/></a></div>
	<div class="block_content">
	<ul class="gradient">
		<li class="first3"><a href="#" name="EXPERT">{$aLang.usercats_category_all}</a></li>
			{if $aUserCats}
				{foreach from=$aUserCats.EXPERT.subCats item=aCat key=sUserCat name=userCats}
					{if $smarty.foreach.userCats.iteration < 4}
		<li>
			<a href="#" name="{$sUserCat}">{$aCat.info.title}</a>
		</li>
					{/if}
				{/foreach}
		{*<li><a class="more_menu close" href="#">{$aLang.usercats_category_additional}</a>
			<ul class="all_menu">
			{foreach from=$aUserCats.EXPERT.subCats item=aCat key=sUserCat name=userCats}
				{if $smarty.foreach.userCats.iteration >= 3}
				<li>
					<a href="#" name="{$sUserCat}">{$aCat.info.title}</a>
				</li>
				{/if}
			{/foreach}
			</ul>
		</li>*}
			{/if}
	</ul>
	<div class="bayan" id="b11">
		{include file="file:../../../plugins/usercats/templates/skin/default/actions/expertUserTopics.tpl"}
	</div>
	<div class="block_bottom"></div>
	</div>
</li>