<li id="category" class="block green">
	<div class="title"><a href="#111" class="link"><h1>{$aLang.communitycats_categories}</h1></a><a href="#" class="close_block"><img src="/img/minus.gif" width="18" height="18" alt="Свернуть блок" title="Свернуть блок"/></a></div>
	<div class="block_content">
	{*<ul class="gradient">
		<li class="first3"><a href="">Персональные</a></li>
		<li><a href="">Звёзды</a></li>
		<li><a href="">VIP</a></li>

		<li><a href="">ещё</a></li>
	</ul>*}
	<ul class="category">
	{if $aCats}
		{foreach from=$aCats item=aCat}
		<li>
			<a href="{$aCat.info.link}">{$aCat.info.title}</a>
			<strong>{$aCat.info.count}</strong>
		</li>
		{/foreach}
	{/if}
	</ul>
	<div class="block_bottom"></div>
	</div>
 </li>
