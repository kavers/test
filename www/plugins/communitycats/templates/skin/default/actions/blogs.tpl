<ul>
{foreach from=$aBlogsCatatalog item=oBlog key=key}
	<li>
		<ul>
			<li>
				<dl class="bayan_item">
					<dd><a href="{$oBlog->getUrlFull()}">{$oBlog->getDescription()}</a></dd>
					<dt><a href="{$oBlog->getUrlFull()}"><img src="{$oBlog->getAvatarPath(48)}" alt="" /></a></dt>
				</dl>
			</li>
		</ul>
		<a href="{$oBlog->getUrlFull()}">{$oBlog->getTitle()}<strong>{$oBlog->getCountUser()}</strong></a>
	</li>
{/foreach}
</ul>
