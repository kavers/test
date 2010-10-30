<li id="community" class="block dark_green">
	<div class="title"><a href="{router page='blogs'}" class="link"><h1>{$aLang.communitycats_communities}</h1></a><a href="#" class="close_block"><img src="{cfg name='path.static.skin'}/img/minus.gif" width="18" height="18" alt="Свернуть блок" title="Свернуть блок"/></a></div>
	<div class="block_content">
		<ul class="gradient">
			<li class="first"><a href="#" name="ALL">{$aLang.communtitycats_category_all}</a></li>
			{if $aBlogCats}
				{foreach from=$aBlogCats item=aCat key=sBlogCat name=blogCats}
					{if $smarty.foreach.blogCats.iteration < 3}
				<li>
					<a href="#" name="{$sBlogCat}">{$aCat.info.title}</a>
				</li>
					{/if}
				{/foreach}
				<li><a class="more_menu close" href="#">{$aLang.communitycats_category_additional}</a>
					<ul class="all_menu">
					{foreach from=$aBlogCats item=aCat key=sBlogCat name=blogCats}
						{if $smarty.foreach.blogCats.iteration >= 3}
						<li>
							<a href="#" name="{$sBlogCat}">{$aCat.info.title}</a>
						</li>
						{/if}
					{/foreach}
					</ul>
				</li>
			{/if}
		</ul>
		<div class="bayan" id="b13">
			{include file="file:../../../plugins/communitycats/templates/skin/default/actions/blogs.tpl"}
		</div>
		<div class="block_bottom"></div>
	</div>
</li>