{literal}
<script type="text/javascript">
function adminPluginUp(plugin)     {
    var row = $(plugin+'_row');
    var priority = $(plugin+'_priority').value;
    var prev = row.getPrevious();
    if (prev) {
        var prev_priority = $(prev.get('id').replace('_row', '_priority'));
        row.inject(prev, 'before');
        $(plugin+'_priority').value=prev_priority.value;
        prev_priority.value=priority;
    }
}

function adminPluginDown(plugin)     {
    var priority = $(plugin+'_priority').value;
    var row = $(plugin+'_row');
    var next = row.getNext();
    if (next) {
        var next_priority = $(next.get('id').replace('_row', '_priority'));
        row.inject(next, 'after');
        $(plugin+'_priority').value=next_priority.value;
        next_priority.value=priority;
    }
}

function adminPluginSave() {
   return true;
}
</script>
{/literal}

<h3>{$oLang->adm_plugins_title}</h3>

    <form action="{router page='admin'}plugins/" method="post" id="form_plugins_list">
        <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />
        <table class="admin-table">
            <thead>
                <tr>
                    <th width="20px"><input type="checkbox" name="" onclick="checkAllPlugins(this);" /></th>
                    <th class="name">{$aLang.plugins_plugin_name}</th>
                    <th class="version">{$aLang.plugins_plugin_version}</th>
                    <th class="author">{$aLang.plugins_plugin_author}</th>
                    <th class="action">{$aLang.plugins_plugin_action}</th>
                    <th class="">&nbsp;</th>
                </tr>
            </thead>

            <tbody id="plugin_list">
		{foreach from=$aPlugins item=aPlugin key=key}
                <tr id="{$key}_row">
                    <td><input type="checkbox" name="plugin_del[{$aPlugin.code}]" class="form_plugins_checkbox" /></td>
                    <td class="name">
                        <div class="{if $aPlugin.is_active}active{else}unactive{/if}"></div>
                        <div class="title">{$aPlugin.property->name->data|escape:'html'}</div>
                        <div class="description">
                        {$aPlugin.property->description->data|escape:'html'}
                        </div>
                        {if $aPlugin.property->homepage > ''}
                        <div class="url">
                        Homepage: {$aPlugin.property->homepage}
                        </div>
                        {/if}
                    </td>
                    <td class="version">{$aPlugin.property->version|escape:'html'}</td>
                    <td class="author">{$aPlugin.property->author->data|escape:'html'}</td>
                    <td class="{if $aPlugin.is_active}deactivate{else}activate{/if}">
                            {if $aPlugin.is_active}
                            <a href="{router page='admin'}plugins/?plugin={$aPlugin.code}&action=deactivate&security_ls_key={$LIVESTREET_SECURITY_KEY}">{$aLang.adm_act_deactivate}</a>
                            {else}
                            <a href="{router page='admin'}plugins/?plugin={$aPlugin.code}&action=activate&security_ls_key={$LIVESTREET_SECURITY_KEY}">{$aLang.adm_act_activate}</a>
                            {/if}
                    </td>
                    <td>
                        &nbsp;
                        <!--
                        <a href="#" class="adm_plugin_up" title="{$oLang->adm_plugin_priority_up}" onclick="adminPluginUp('{$key}'); return false;"></a>
                        <a href="#" class="adm_plugin_down" title="{$oLang->adm_plugin_priority_down}" onclick="adminPluginDown('{$key}'); return false;"></a>
                        <input type="hidden" id="{$key}_priority" name="{$key}_priority" value="{$aPlugin.priority}" />
                        <input type="hidden" id="{$key}_active" name="{$key}_active" value="{$aPlugin.is_active}" />
                        -->
                    </td>
                </tr>
		{/foreach}
            </tbody>
        </table>
        <!-- <br/> {$oLang->adm_plugin_priority_notice} -->
        <br/><br/>
        <input type="submit" name="submit_plugins_del" value="{$aLang.plugins_submit_delete}" onclick="return ($$('.form_plugins_checkbox:checked').length==0)?false:confirm('{$aLang.plugins_delete_confirm}');" />
        <!-- <input type="submit" name="submit_plugins_save" value="{$aLang.adm_save}" onclick="adminPluginSave();" /> -->
    </form>
