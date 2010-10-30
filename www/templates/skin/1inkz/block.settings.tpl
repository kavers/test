<li id="settings" class="block dark_green">
	<div class="title"><a href="{router page='settings'}" class="link"><h1>{$aLang.settings_menu}</h1></a><a href="#" class="close_block"><img src="{cfg name='path.static.skin'}/img/minus.gif" width="18" height="18" alt="�������� ����" title="�������� ����"/></a></div>
	<div class="block_content">
		<ul class="category">
			<li>
				<a href="{router page='settings'}profile/" {if $sMenuSubItemSelect=='profile'}class="act"{/if}>{$aLang.settings_menu_profile}</a>
			</li>
			<li>
				<a href="{router page='settings'}tuning/" {if $sMenuSubItemSelect=='tuning'}class="act"{/if}>{$aLang.settings_menu_tuning}</a>
			</li>
                        <li>
				<a href="{router page='settings'}template/" {if $sMenuSubItemSelect=='template'}class="act"{/if}>{$aLang.settings_menu_templates}</a>
			</li>
                        <li>
				<a href="{router page='settings'}widgets/" {if $sMenuSubItemSelect=='widgets'}class="act"{/if}>{$aLang.settings_menu_widgets}</a>
			</li>
                        <li>
				<a href="{router page='settings'}decor/" {if $sMenuSubItemSelect=='decor'}class="act"{/if}>{$aLang.settings_menu_decor}</a>
			</li>
		</ul>
		<div class="block_bottom"></div>
	</div>
</li>