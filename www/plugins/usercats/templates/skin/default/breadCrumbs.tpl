{if $aPath}
	{assign var="sPath" value="/people/"}
	{foreach from=$aPath item=sPathPart}
		{assign var="uPathPart" value=$sPathPart|upper}
		{if $bCat == "1"}
			{assign var="langKey" value=$uPathPart|string_format:"communitycats_category_%s"}
		{else}
			{assign var="langKey" value=$uPathPart|string_format:"usercats_category_%s"}
		{/if}
		{php}
			$sPath = $this->get_template_vars('sPath');
			$sPathPart = strtolower($this->get_template_vars('sPathPart'));
			$sPath .= $sPathPart . '/';
			$this->assign('sPath', $sPath);
		{/php}
		{if $uPathPart != "CAT"}
			<img src="{cfg name='path.static.skin'}/img/arrow.gif" width="7" height="12" alt="arrow" title="arrow" class="arrow"/>
			<a href="{$sPath}" class="link"><h1>{$aLang[$langKey]}</h1></a>
		{else}
			{assign var="bCat" value="1"}
		{/if}
	{/foreach}
{/if}