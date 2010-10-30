<li id="settings" class="block dark_green">
	<div class="title"><a href="{router page='blog'}" class="link"><h1>{$aLang.settings_menu}</h1></a><a href="#" class="close_block"><img src="{cfg name='path.static.skin'}/img/minus.gif" width="18" height="18" /></a></div>
	<div class="block_content">
		<ul class="category">
                        <li>
				<a href="{router page='blog'}edit/{if $oBlog}{$oBlog->getId()}{elseif $oBlogEdit}{$oBlogEdit->getId()}{else}{$aRequest.blog_id}{/if}/" {if $sMenuItemSelect=='profile'}class="act"{/if}>{$aLang.blog_admin_profile}</a>
			</li>
                        <li>
				<a href="{router page='blog'}admin/{if $oBlog}{$oBlog->getId()}{elseif $oBlogEdit}{$oBlogEdit->getId()}{else}{$aRequest.blog_id}{/if}/" {if $sMenuItemSelect=='admin'}class="act"{/if}>{$aLang.blog_admin_users}</a>
			</li>
			<li>
				<a href="{router page='blog'}template/{if $oBlog}{$oBlog->getId()}{elseif $oBlogEdit}{$oBlogEdit->getId()}{else}{$aRequest.blog_id}{/if}/" {if $sMenuItemSelect=='template'}class="act"{/if}>{$aLang.settings_menu_templates}</a>
			</li>
                        <li>
				<a href="{router page='blog'}widgets/{if $oBlog}{$oBlog->getId()}{elseif $oBlogEdit}{$oBlogEdit->getId()}{else}{$aRequest.blog_id}{/if}/" {if $sMenuItemSelect=='widgets'}class="act"{/if}>{$aLang.settings_menu_widgets}</a>
			</li>
                        <li>
				<a href="{router page='blog'}decor/{if $oBlog}{$oBlog->getId()}{elseif $oBlogEdit}{$oBlogEdit->getId()}{else}{$aRequest.blog_id}{/if}/" {if $sMenuItemSelect=='decor'}class="act"{/if}>{$aLang.settings_menu_decor}</a>
			</li>
		</ul>
		<div class="block_bottom"></div>
	</div>
</li>