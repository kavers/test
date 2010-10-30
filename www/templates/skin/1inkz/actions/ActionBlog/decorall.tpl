{include file='header.tpl' menu='settings' showWhiteBack=true}
{literal}
<script language="JavaScript" type="text/javascript">
function previewTheme(href,name) {
            window.open(href, name, 'resizable=yes,status=yes,toolbar=no,location=no,menubar=no,scrollbars=yes');
}
</script>
{/literal}
    <li id="vip_blogs" class="block2 green">
        <div class="title"><a href="{router page='blog'}decor/{$oBlog->getId()}/all/" class="link"><h1>Украшения</h1></a>{if $aLangCategory}<img src="{cfg name='path.static.skin'}/img/arrow.gif" width="7" height="12" alt="arrow" title="arrow" class="arrow"/><a href="" class="link"><h1>{$aLangCategory}</h1></a>{/if}</div>
         
        <div class="block_content">
           <ul class="table_list dop">
            {foreach from=$aDecors item=oDecor}
              <li>
                 <img src="{$oDecor->getDecAvatar()}" width="82" height="82" alt="{$oDecor->getDecTitle()}" title="{$oDecor->getDecTitle()}"/>
                 <a onclick="previewTheme('{$oBlog->getUrlFull()}?setDec={$oDecor->getDecId()}','{$oDecor->getDecTitle()}');return false;" href="#" class="item_title">{$oDecor->getDecTitle()}</a>
                 <strong>{$oDecor->getCountUsers()} используют</strong>
                 <p>{date_format date=$oDecor->getDecDateAdd()}</p>
                 <!--<div class="rating red">круто</div>-->
	         <div class="vipblog_info">
                    <a onclick="previewTheme('{$oBlog->getUrlFull()}?setDec={$oDecor->getDecId()}','{$oDecor->getDecTitle()}');return false;" href="#" target="_blank"><img src="{cfg name='path.static.skin'}/img/comment_ico8.png" width="24" height="23" alt="" title=""/></a>
                   {if !$oDecor->getDecFav('collective',$oBlog->getId())} <a href="{router page='blog'}decor/{$oBlog->getId()}/?setfav={$oDecor->getDecId()}"><img src="{cfg name='path.static.skin'}/img/comment_ico5.png" width="24" height="23" alt="" title=""/></a>
                    {else}<a href="{router page='blog'}decor/{$oBlog->getId()}/?delfav={$oDecor->getDecId()}"><img src="{cfg name='path.static.skin'}/img/cut_ico.png" width="24" height="23" alt="" title=""/></a>{/if}
        	 </div>
              </li>
             {/foreach}
           </ul>
        <div class="block_bottom3"></div>
        </div>
     </li>
{include file='footer.tpl'}