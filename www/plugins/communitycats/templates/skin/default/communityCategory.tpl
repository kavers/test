{if $oBlog}
    {assign var="langKey" value=$oBlog->getCategoryName()|string_format:"communitycats_category_%s"}
    {assign var="sBlogCat" value=$aLang[$langKey]}
{else}
    {assign var="sBlogCat" value=""}
{/if}

<p>{$aLang.communitycats_category}: {$sBlogCat}</p>