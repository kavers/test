{include file='header.tpl' menu='blog_edit' showWhiteBack=true}
{literal}
<script language="JavaScript" type="text/javascript">
function previewTheme(href,name) {
            window.open(href, name, 'resizable=yes,status=yes,toolbar=no,location=no,menubar=no,scrollbars=yes');
}
</script>
{/literal}
<li id="my_vidget" class="block2 green">
        <div class="title"><a href="" class="link"><h1>Шаблон блога</h1></a></div>
        <div class="block_content">
          <div id="text">
             <p class="vidget_title"><strong>Список шаблонов</strong> <a href="{router page='blog'}template/{$oBlog->getId()}/all/"><img src="{cfg name='path.static.skin'}/img/go_catalog.gif" width="123" height="20" alt="Перейти в каталог" title="Перейти в каталог"/></a></p>
             <ul class="templates_list">
               {foreach from=$aTemplates item=oTemplate}
                <li>
                   <dl>
                      <dt>
                            <a onclick="previewTheme('{$oBlog->getUrlFull()}?settpl={$oTemplate->getTplId()}','{$oTemplate->getTplTitle()}');return false;" href="#" target="_blank">
                                <img src="{$oTemplate->getTplAvatar()}" width="140" height="80" alt="" title=""/>
                            </a>
                      </dt>
                      <dd>
	                 <a href="{router page='blog'}template/{$oBlog->getId()}/?delfav={$oTemplate->getTplId()}"><img src="{cfg name='path.static.skin'}/img/scissors.gif"  width="28" height="27" alt="редактировать" title="редактировать"/></a>
                      </dd>
                   </dl>
                   {$oTemplate->getTplTitle()}<br/>
                        {if $oTplActive && $oTemplate->getTplId()==$oTplActive->getTplId()}
                            <a href="{router page='blog'}template/{$oBlog->getId()}/?delete={$oTemplate->getTplId()}"><img src="{cfg name='path.static.skin'}/img/off_button.gif" width="83" height="20" alt="Удалить" title="Удалить"/></a>
                        {else}
                            <a href="{router page='blog'}template/{$oBlog->getId()}/?choose={$oTemplate->getTplId()}"><img src="{cfg name='path.static.skin'}/img/on_button.gif" width="83" height="20" alt="Применить" title="Применить"/></a>
                        {/if}
                </li>
               {/foreach}
             </ul>
          </div>
        <div class="block_bottom3"></div>
        </div>
     </li>
{include file='footer.tpl'}