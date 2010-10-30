<ul>
	{foreach from=$aTopicsUserCatatalog item=oTopic key=key}
		{assign var="oUser" value=$oTopic->getUser()}
	<li>
		<ul>
			<li>
				<dl class="bayan_item">
					<dt>
						<a href="{router page='profile'}{$oUser->getLogin()}/">
							<img src="{$oUser->getProfileAvatarPath(64)}" alt="{$oUser->getLogin()}" title="{$oUser->getLogin()}" width="60" height="60" />
							<br />
							{$oUser->getLogin()}
						</a>
					</dt>
					<dd>
						<a href="{$oTopic->getUrl()}">{$oTopic->getText()|strip_tags:false|truncate:80:'...'}</a>
					</dd>
				</dl>
			</li>
		</ul>
		<a href="{$oTopic->getUrl()}">{$oTopic->getTitle()|escape:'html'}</a>
	</li>
	{/foreach}
</ul>
