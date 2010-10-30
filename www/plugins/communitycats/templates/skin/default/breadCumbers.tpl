{if $aPath}
	{assign var="sPath" value="/blogs/"}
	{foreach from=$aPath item=sPathPart}
		{assign var="uPathPart" value=$sPathPart|upper}
		{assign var="langKey" value=$uPathPart|string_format:"communitycats_category_%s"}
		{php}
			$sPath = $this->get_template_vars('sPath');
			$sPathPart = strtolower($this->get_template_vars('sPathPart'));
			$sPath .= $sPathPart . '/';
			$this->assign('sPath', $sPath);
		{/php}
		<img src="{cfg name='path.static.skin'}/img/arrow.gif" width="7" height="12" alt="arrow" title="arrow" class="arrow"/>
		<a href="{$sPath}" class="link"><h1>{$aLang[$langKey]}</h1></a>
	{/foreach}
{/if}