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
    <li id="vip_blogs" class="block2 green">
        <div class="title"><a href="{router page='settings'}widgets/all/" class="link"><h1>Виджеты</h1></a>{if $aLangCategory}<img src="{cfg name='path.static.skin'}/img/arrow.gif" width="7" height="12" alt="arrow" title="arrow" class="arrow"/><a href="" class="link"><h1>{$aLangCategory}</h1></a>{/if}</div>
         
        <div class="block_content">
           <ul class="table_list dop">
            {foreach from=$aWidgets item=oWidget}
              <li>
                 <img src="{$oWidget->getWidAvatar()}" width="82" height="82" alt="{$oWidget->getWidTitle()}" title="{$oWidget->getWidTitle()}"/>
                 <a onclick="previewTheme('{router page='my'}{$oUserCurrent->getLogin()}/?setwid={$oWidget->getWidId()}','{$oWidget->getWidTitle()}');return false;" href="#" class="item_title">{$oWidget->getWidTitle()}</a>
                 <strong>{$oWidget->getCountUsers()} используют</strong>
                 <p>{date_format date=$oWidget->getWidDateAdd()}</p>
                 <!--<div class="rating red"></div>-->
	         <div class="vipblog_info">
                    <a href="{$oWidget->getWidAvatar()}" ext="увеличить" onclick="return hs.expand(this)"><img src="{cfg name='path.static.skin'}/img/comment_ico8.png" width="24" height="23" alt="" title=""/></a>
                   {if !$oWidget->getWidFav('personal',$oUserCurrent->getId())} <a href="{router page='settings'}widgets/?setfav={$oWidget->getWidId()}"><img src="{cfg name='path.static.skin'}/img/comment_ico5.png" width="24" height="23" alt="" title=""/></a>
                    {else}<a href="{router page='settings'}widgets/?delfav={$oWidget->getWidId()}"><img src="{cfg name='path.static.skin'}/img/cut_ico.png" width="24" height="23" alt="" title=""/></a>{/if}
        	 </div>
              </li>
             {/foreach}
           </ul>
        <div class="block_bottom3"></div>
        </div>
     </li>
{include file='footer.tpl'}