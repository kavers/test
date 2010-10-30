
{include file='header.tpl'}
{literal}
<script language="JavaScript" type="text/javascript">
function previewTheme(href,name) {
            window.open(href, name, 'resizable=yes,status=yes,toolbar=no,location=no,menubar=no,scrollbars=yes');
}
</script>
{/literal}

<li id="my_vidget" class="block2 green">
        <div class="title"><a href="#111" class="link"><h1>Шаблоны оформления:</h1></a>[<a href="{router page='admin'}templates/add/">добавить</a>]</div>
        <div class="block_content">
          <div id="text">
             <p class="vidget_title"><strong>Список шаблонов</strong>
             <ul class="templates_list">
               {foreach from=$aTemplates item=oTemplate}
                <li>
                   <dl>
                      <dt><img src="{$oTemplate->getTplAvatar()}" width="140" height="80" alt="" title=""/></dt>
                      <dd>
	                 <a href="{router page='admin'}templates/edit/{$oTemplate->getTplId()}/"><img src="{cfg name='path.static.skin'}/img/scissors.gif"  width="28" height="27" alt="редактировать" title="редактировать"/></a>
                      </dd>
                   </dl>
                   {$oTemplate->getTplTitle()}<br/>
<a href="{router page='admin'}templates/edit/{$oTemplate->getTplId()}/">Ред.</a> | <a onclick="return confirm('Вы уверены что хотите удалить этот шаблон?')" href="{router page='admin'}templates/?delete={$oTemplate->getTplId()}&security_ls_key={$LIVESTREET_SECURITY_KEY}">Удалить.</a>
                </li>
               {/foreach}
             </ul>
          </div>
        <div class="block_bottom3"></div>
        </div>
     </li>

{include file='footer.tpl'}