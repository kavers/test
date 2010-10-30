<li id="category" class="block green">
	<div class="title"><a href="#111" class="link"><h1>{$aLang.usercats_user_categories}</h1></a><a href="#" class="close_block"><img src="/img/minus.gif" width="18" height="18" alt="Свернуть блок" title="Свернуть блок"/></a></div>
	<div class="block_content">
	<ul class="gradient" id="plugin_usercats_usercats">
	{if $aUserCats}
		{foreach from=$aUserCats item=aCat key=sUserCat name=userCats}
		<li {if $smarty.foreach.userCats.first} class="first3"{/if}>
			<a href="#" name="{$sUserCat}">{$aCat.info.title}</a>
		</li>
		{/foreach}
	{/if}
	</ul>
	{if $aUserCats}
		{foreach from=$aUserCats item=aCat key=sUserCat name=blogCat}
	<ul class="category" {if !$smarty.foreach.blogCat.first} style="display:none"{/if} id="plugin_usercats_blogcats_{$sUserCat}">
		{foreach from=$aCat.blogCats item=aBlogCat}
		<li>
			<a href="{$aBlogCat.info.link}">{$aBlogCat.info.title}</a>
			<strong>{$aBlogCat.info.count}</strong>
		</li>
		{/foreach}
	</ul>
		{/foreach}
	{/if}
	<div class="block_bottom"></div>
	</div>
 </li>