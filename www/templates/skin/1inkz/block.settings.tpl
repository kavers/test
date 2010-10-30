<li id="settings" class="block dark_green">
	<div class="title"><a href="http://ls.1inkz.slwork.ru/people/" class="link"><h1>{$aLang.settings_menu}</h1></a><a href="#" class="close_block"><img src="http://ls.1inkz.slwork.ru/templates/skin/1inkz/img/minus.gif" width="18" height="18" alt="Свернуть блок" title="Свернуть блок"/></a></div>
	<div class="block_content">
		<ul class="category">
			<li>
				<a href="{router page='settings'}profile/" {if $sMenuSubItemSelect=='profile'}class="act"{/if}>{$aLang.settings_menu_profile}</a>
			</li>
			<li>
				<a href="{router page='settings'}tuning/" {if $sMenuSubItemSelect=='tuning'}class="act"{/if}>{$aLang.settings_menu_tuning}</a>
			</li>
		</ul>
		<div class="block_bottom"></div>
	</div>
</li>