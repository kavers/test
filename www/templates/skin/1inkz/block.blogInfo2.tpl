{if $oBlog}
{assign var="oUserOwner" value=$oBlog->getOwner()}
<!--  Блок Сведения о новости -->
     <li id="about_news" class="block green">
        <div class="title"><a href="#" class="link"><h1>О сообществе</h1></a><a href="#" class="close_block"><img src="{cfg name='path.static.skin'}/img/minus.gif" width="18" height="18" alt="Свернуть блок" title="Свернуть блок"/></a></div>
        <div class="block_content">
       {* <ul class="gradient">
           <li class="first3"><a href="">Блог</a></li>
           <li><a href="#">Профиль</a></li>
           <li><a href="#">Записи друзей</a></li>
           <li><a href="" class="more_menu close">ещё</a>
              <ul class="all_menu">
                 <li><a href="#">Crunk</a></li>
                 <li><a href="#">Dance</a></li>
                 <li><a href="#">Drum 'N' Bass</a></li>
                 <li><a href="#">R'n'B</a></li>
                 <li><a href="#">Rap</a></li>
                 <li><a href="#">Soundtrack</a></li>
                 <li><a href="#">Вокал</a></li>
                 <li><a href="#">Гимны</a></li>
                 <li><a href="#">Джаз</a></li>
                 <li><a href="#">Казахская музыка</a></li>
                 <li><a href="#">Классическая музыка</a></li>
                 <li><a href="#">Поп</a></li>
                 <li><a href="#">Ретро</a></li>
                 <li><a href="#">Рок</a></li>
                 <li><a href="#">Рок-н-ролл</a></li>
              </ul>
           </li>
        </ul>
        *}
        <ul class="descriptions">
           <li class="descr3">
              <a href="{$oBlog->getFullPath()}" class="user_avatar"><img src="{$oBlog->getAvatarPath(100)}" alt="avatar" title="avatar" /></a>
              <div class="user_info">
                 <a href="{$oBlog->getFullPath()}" class="username">{$oBlog->getTitle()}</a>
                 {*<a href="#"><img src="{cfg name='path.static.skin'}/img/comment_ico5.png" width="24" height="23" alt="" title=""/></a>
                 *}{if $oUserCurrent and $oUserCurrent->getId()!=$oBlog->getOwnerId()}
							<a href="#" onclick="ajaxJoinLeaveBlog(this,{$oBlog->getId()}); return false;"><img src="{cfg name='path.static.skin'}/img/comment_ico6.png" width="24" height="23" alt="" title=""/></a>
			     {/if}

                </div>
           </li>
           {if $oBlog->getDescription()}
           <li class="descr1">
              <h2>Описание</h2>
              <p>{$oBlog->getDescription()}</p>
           </li>
           {/if}
           {*
           <li class="descr1">
				<ul class="tags">
                    {foreach from=$oBlog->getTagsArray() item=sTag name=tags_list}
						<li><a href="{router page='tag'}{$sTag|escape:'html'}/">{$sTag|escape:'html'}</a>{if !$smarty.foreach.tags_list.last}, {/if}</li>
					{/foreach}
              </ul>
           </li>
           {*
           <li class="descr1">
              <h2>Статистика</h2>
              <p>Язык записей <strong>Русский</strong>&nbsp;Место 
                {if $oUserOwner->getProfileCountry()}
                    <a style="display:inline;font-size:1em" href="{router page='people'}country/{$oUserOwner->getProfileCountry()|escape:'html'}/">{$oUserOwner->getProfileCountry()|escape:'html'}</a>{if $oUserOwner->getProfileCity()},{/if}
                {/if}						
                {if $oUserOwner->getProfileCity()}
                    <a style="display:inline;font-size:1em" href="{router page='people'}city/{$oUserOwner->getProfileCity()|escape:'html'}/">{$oUserOwner->getProfileCity()|escape:'html'}</a>
                {/if}
                <br />
              Просмотров <strong>56788</strong> Оценок <strong>1048</strong> Обсуждают <strong>48</strong></p>
           </li>
           *}
        </ul>
        <div class="block_bottom"></div>
        </div>
     </li>
<!--  Блок Сведения о новости -->

{*
<li id="about_author" class="block green">
        <div class="title"><a href="#111" class="link"><h1>{$aLang.block_blogs}</h1></a><a href="#" class="close_block"><img src="{cfg name='path.static.skin'}/img/minus.gif" width="18" height="18" alt="Свернуть блок" title="Свернуть блок"/></a></div>
        <div class="block_content block blogs">
        <ul class="gradient block-nav">
           <li class="active"><a href="#" id="block_blogs_top" onclick="lsBlockBlogs.toggle(this,'blogs_top'); return false;">{$aLang.block_blogs_top}</a></li>
            {if $oUserCurrent}
                <li><a href="#" id="block_blogs_join" onclick="lsBlockBlogs.toggle(this,'blogs_join'); return false;">{$aLang.block_blogs_join}</a></li>
                <li><a href="#" id="block_blogs_self" onclick="lsBlockBlogs.toggle(this,'blogs_self'); return false;">{$aLang.block_blogs_self}</a></li>
            {/if}
        </ul>
        
        <div class="block-content">
        {literal}
            <script language="JavaScript" type="text/javascript">
            var lsBlockBlogs;
            window.addEvent('domready', function() {       
                lsBlockBlogs=new lsBlockLoaderClass();
            });
            </script>
        {/literal}
            {$sBlogsTop}
        </div>
        <div class="right"><a href="{router page='blogs'}">{$aLang.block_blogs_all}</a></div>
        <div class="block_bottom"></div>
        </div>
     </li>

{*			<div class="block blogs">
				<div class="tl"><div class="tr"></div></div>
				<div class="cl"><div class="cr">
					
					<h1>{$aLang.block_blogs}</h1>
					
					<ul class="block-nav">
						<li class="active"><strong></strong><a href="#" id="block_blogs_top" onclick="lsBlockBlogs.toggle(this,'blogs_top'); return false;">{$aLang.block_blogs_top}</a>{if !$oUserCurrent}<em></em>{/if}</li>
						{if $oUserCurrent}
							<li><a href="#" id="block_blogs_join" onclick="lsBlockBlogs.toggle(this,'blogs_join'); return false;">{$aLang.block_blogs_join}</a></li>
							<li><a href="#" id="block_blogs_self" onclick="lsBlockBlogs.toggle(this,'blogs_self'); return false;">{$aLang.block_blogs_self}</a><em></em></li>
						{/if}
					</ul>
					
					<div class="block-content">
					{literal}
						<script language="JavaScript" type="text/javascript">
						var lsBlockBlogs;
						window.addEvent('domready', function() {       
							lsBlockBlogs=new lsBlockLoaderClass();
						});
						</script>
					{/literal}
					{$sBlogsTop}
					</div>
					
					<div class="right"><a href="{router page='blogs'}">{$aLang.block_blogs_all}</a></div>

					
				</div></div>
				<div class="bl"><div class="br"></div></div>
			</div>
*}
{else $oTopic}
{include file="block.info.tpl"}
{/if}
