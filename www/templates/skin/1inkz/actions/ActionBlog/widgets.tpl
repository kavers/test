{include file='header.tpl' menu='settings' showWhiteBack=true}
<script type="text/javascript" src="{cfg name='path.static.skin'}/js/highslide.packed.js"></script>
	<script type="text/javascript">
	{literal}
	 window.addEvent('domready', function(){
		  $$('a[ext="highslide"]').addEvent('click', function(){return hs.expand(this)});
	 });
	{/literal}
	hs.graphicsDir = '{cfg name='path.static.skin'}/images/highslide/';
    hs.outlineType = 'rounded-white';
    hs.numberOfImagesToPreload = 0;
    hs.showCredits = false;
	{literal}
        hs.lang = {
                loadingText :     'Загрузка...',
                fullExpandTitle : 'Развернуть до полного размера',
                restoreTitle :    'Кликните для закрытия картинки, нажмите и удерживайте для перемещения',
                focusTitle :      'Сфокусировать',
                loadingTitle :    'Нажмите для отмены'
        };
	{/literal}
</script>

     <li id="my_vidget" class="block2 green">
        <div class="title"><a href="/" class="link"><h1>Виджеты блога</h1></a></div>
        <div class="block_content">
          <div id="text">
             <p class="vidget_title"><strong>Список виджетов</strong> <a href="{router page='blog'}widgets/{$oBlog->getId()}/all/"><img src="{cfg name='path.static.skin'}/img/go_catalog.gif" width="123" height="20" alt="Перейти в каталог" title="Перейти в каталог"/></a></p>
             <ul class="vidget_list">
               {foreach from=$aWidgets item=oWidget name=wids_list}
                <li>
                   <dl>
                      <dt>
                        <a href="{$oWidget->getWidAvatar()}" ext="увеличить" onclick="return hs.expand(this)">
                            <img src="{$oWidget->getWidAvatar()}" width="140" height="80" alt="" title=""/>
                        </a>
                      </dt>
                      <dd>
	                 <a href="{router page='blog'}widgets/{$oBlog->getId()}/?delfav={$oWidget->getWidId()}"><img src="{cfg name='path.static.skin'}/img/scissors.gif"  width="28" height="27" alt="редактировать" title="редактировать"/></a>
                         <!--<a href="/"><img src="{cfg name='path.static.skin'}/img/gl_r.gif"  width="28" height="27" alt="" title=""/></a>
                      -->{if !$smarty.foreach.wids_list.first}<a href="{router page='blog'}widgets/{$oBlog->getId()}/?sort={$oWidget->getWidId()}&sorttype=down">&larr;</a> {/if}
                          {if !$smarty.foreach.wids_list.last}<a href="{router page='blog'}widgets/{$oBlog->getId()}/?sort={$oWidget->getWidId()}&sorttype=up">&rarr;</a> {/if}
                      </dd>
                   </dl>
                   {$oWidget->getWidTitle()}<br/>
                        {if $oWidget->getWidActive('collective',$oBlog->getId())}
                            <a href="{router page='blog'}widgets/{$oBlog->getId()}/?delete={$oWidget->getWidId()}"><img src="{cfg name='path.static.skin'}/img/off_button.gif" width="83" height="20" alt="Удалить" title="Удалить"/></a>
                        {else}
                            <a href="{router page='blog'}widgets/{$oBlog->getId()}/?choose={$oWidget->getWidId()}"><img src="{cfg name='path.static.skin'}/img/on_button.gif" width="83" height="20" alt="Применить" title="Применить"/></a>
                        {/if}
                </li>
               {/foreach}
             </ul>
          </div>
        <div class="block_bottom3"></div>
        </div>
     </li>
{include file='footer.tpl'}