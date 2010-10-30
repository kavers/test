<ul>
{foreach from=$aUsersCatatalog item=oUser key=key}
	<li>
		<ul><li><br /><img src="{$oUser->getProfileAvatarPath(100)}" alt="" /></li></ul>
		<a href="{router page='profile'}{$oUser->getLogin()}/">{$oUser->getLogin()}{if $oUser->getProfileName()} &ndash; {$oUser->getProfileName()}{/if}</a>
	</li>
{/foreach}
</ul>
