{include file='header.tpl' menu='settings' showWhiteBack=true}
{literal}
<script language="JavaScript" type="text/javascript">
function previewTheme(href,name) {
            window.open(href, name, 'resizable=yes,status=yes,toolbar=no,location=no,menubar=no,scrollbars=yes');
}
</script>
{/literal}

     <li id="my_vidget" class="block2 green">
        <div class="title"><a href="#111" class="link"><h1>Украшения для блога</h1></a></div>
        <div class="block_content">
          <div id="text">
             <p class="vidget_title"><strong>Список украшений</strong> <a href="{router page='settings'}decor/all/"><img src="{cfg name='path.static.skin'}/img/go_catalog.gif" width="123" height="20" alt="Перейти в каталог" title="Перейти в каталог"/></a></p>
             <ul class="templates_list">
               {foreach from=$aDecors item=oDecor}
                <li>
                   <dl>
                      <dt>
                        <a onclick="previewTheme('{router page='my'}{$oUserCurrent->getLogin()}/?setDec={$oDecor->getDecId()}','{$oDecor->getDecTitle()}');return false;" href="#" target="_blank">
                           <img src="{$oDecor->getDecAvatar()}" width="140" height="80" alt="" title=""/></dt>
                        </a>
                      <dd>
	                 <a href="{router page='settings'}decor/?delfav={$oDecor->getDecId()}"><img src="{cfg name='path.static.skin'}/img/scissors.gif"  width="28" height="27" alt="редактировать" title="редактировать"/></a>
                      </dd>
                   </dl>
                   {$oDecor->getDecTitle()}<br/>
                        {if $oDecActive && $oDecor->getDecId()==$oDecActive->getDecId()}
                            <a href="{router page='settings'}decor/?delete={$oDecor->getDecId()}"><img src="{cfg name='path.static.skin'}/img/off_button.gif" width="83" height="20" alt="Удалить" title="Удалить"/></a>
                        {else}
                            <a href="{router page='settings'}decor/?choose={$oDecor->getDecId()}"><img src="{cfg name='path.static.skin'}/img/on_button.gif" width="83" height="20" alt="Применить" title="Применить"/></a>
                        {/if}
                </li>
               {/foreach}
             </ul>
          </div>
        <div class="block_bottom3"></div>
        </div>
     </li>
{include file='footer.tpl'}