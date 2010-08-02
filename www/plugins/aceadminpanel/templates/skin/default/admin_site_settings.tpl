<h3>{$oLang->adm_settings_title}</h3>
<ul class="block-nav">
    <li {if $sMenuNavItemSelect=='base'}class="active"{/if}><strong></strong><a href="{router page='admin'}site/settings/base/">{$oLang->adm_settongs_base}</a></li>
    <li {if $sMenuNavItemSelect=='sys'}class="active"{/if}><a href="{router page='admin'}site/settings/sys/">{$oLang->adm_settongs_sys}</a><em></em></li>
</ul>
<div class=topic>

    <form action="" method="POST">
        <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />
        {foreach from=$aFields key=sConfigKey item=aItem}
        <p>
            {if $aItem.type=='section'}
        {assign var="sTitle" value="$sConfigKey"}
        <h4 class="adm_section">{$oLang->Get("`$sTitle`")}</h4>
        {else}
        {assign var="sValue" value=$oConfig->GetValue("`$aItem.config`")}
            <label for="{$sConfigKey}">{$oLang->Get("$sConfigKey")}:</label>
            {if $aItem.type=='checkbox'}
            <input type="{$aItem.type}" id="{$sConfigKey}" name="{$sConfigKey}" value="1" class="{$aItem.class}" {if ($sValue)}checked{/if} /><br />
            {elseif $aItem.type=='select'}
            <select id="{$sConfigKey}" name="{$sConfigKey}" class="{$aItem.class}">
            {foreach from=$aItem.options item=sOption}
            <option value="{$sOption}" {if $sOption==$sValue}selected{/if}>{$sOption}</option>
            {/foreach}
            </select>
            {else}
            <input type="{$aItem.type}" id="{$sConfigKey}" name="{$sConfigKey}" value="{$sValue}" class="{$aItem.class}" /><br />
            {/if}
            <!-- span class="form_note">{$oLang->Get("adm_set_`$sConfigKey`_notice")}</span -->
        {/if}
        </p>
        {/foreach}

        <p class="buttons">
            <input type="submit" name="submit_data_save" value="{$oLang->adm_save}" />&nbsp;
        </p>

    </form>

</div>
