{if $oUser}
    {assign var="langKey" value=$oUser->getCategoryName()|string_format:"usercats_category_%s"}
    {assign var="sUserCat" value=$aLang[$langKey]}
{else}
    {assign var="sUserCat" value=""}
{/if}

<p>{$aLang.usercats_user_category}: {$sUserCat}</p>