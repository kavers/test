{include file='header.tpl'}

<div style="width: 50%; float: left;">
    <h3>{$oLang->adm_title}</h3>

    <div class="topic">
        <p>Description: {$aPluginInfo.description}</p>
        <p>Version: {$aPluginInfo.version}</p>
        <p>Current version of LiveStreet: {$LS_VERSION}</p>

        <br /><br />
    </div>
</div>

<div style="width: 50%; float: left;">
    <h3>{$oLang->adm_site_statistics}</h3>

    <div class="topic">
        <p>{$oLang->adm_site_stat_users} {$aSiteStat.users}</p>
        <p>{$oLang->adm_site_stat_blogs} {$aSiteStat.blogs}</p>
        <p>{$oLang->adm_site_stat_topics} {$aSiteStat.topics}</p>
        <p>{$oLang->adm_site_stat_comments} {$aSiteStat.comments}</p>

        <br /><br />
    </div>
</div>

<div style="width: 50%; float: left;">
    <h3>{$oLang->adm_active_plugins}</h3>

    <div class="topic">
        {foreach from=$aPlugins item=aPlugin}
        {if $aPlugin.is_active}
        <p>{$aPlugin.property->name->data|escape:'html'}, v.{$aPlugin.property->version|escape:'html'}</p>
        {/if}
        {/foreach}

        <br /><br />
    </div>
</div>


{include file='footer.tpl'}