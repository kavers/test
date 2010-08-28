{assign var="oUserOwner" value=$oTopic->getOwner()}
<!--  Блок Сведения о новости -->
     <li id="about_news" class="block green">
        <div class="title"><a href="#" class="link"><h1>Об авторе</h1></a><a href="#" class="close_block"><img src="{cfg name='path.static.skin'}/img/minus.gif" width="18" height="18" alt="Свернуть блок" title="Свернуть блок"/></a></div>
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
              <a href="{$oUserOwner->getUserWebPath()}" class="user_avatar"><img src="{$oUserOwner->getProfileAvatarPath(100)}" alt="avatar" title="avatar" /></a>
              <div class="user_info">
                 <a href="#" class="username">{$oUserOwner->getProfileName()}</a>
                 {if $oUserFriend and ($oUserFriend->getFriendStatus()==$USER_FRIEND_ACCEPT+$USER_FRIEND_OFFER or $oUserFriend->getFriendStatus()==$USER_FRIEND_ACCEPT+$USER_FRIEND_ACCEPT) }
                    <a href="#"  title="{$aLang.user_friend_del}" onclick="ajaxDeleteUserFriendFromInfo(this,{$oUserProfile->getId()},'del'); return false;"><img src="{cfg name='path.static.skin'}/img/comment_ico5.png" width="24" height="23" alt="" title=""/></a>
                 {elseif !$oUserFriend}	
                    <a href="#"  title="{$aLang.user_friend_add}" onclick="ajaxAddUserFriendFromInfo(this,{$oUserProfile->getId()},'add'); return false;"><img src="{cfg name='path.static.skin'}/img/comment_ico5.png" width="24" height="23" alt="" title=""/></a>
                 {/if}
                 {*<a href="#"><img src="{cfg name='path.static.skin'}/img/comment_ico6.png" width="24" height="23" alt="" title=""/></a>*}
                 {*<a href="#"><img src="{cfg name='path.static.skin'}/img/comment_ico7.png" width="24" height="23" alt="" title=""/></a>*}

                </div>
           </li>
           <li class="descr1">
              <h2>О себе</h2>
              <p>{$oUserOwner->getProfileAbout()}</p>
           </li>{*
           <li class="descr1">
				<ul class="tags">
                    {foreach from=$oTopic->getTagsArray() item=sTag name=tags_list}
						<li><a href="{router page='tag'}{$sTag|escape:'html'}/">{$sTag|escape:'html'}</a>{if !$smarty.foreach.tags_list.last}, {/if}</li>
					{/foreach}
              </ul>
           </li>*}
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
              {*Просмотров <strong>56788</strong> Оценок <strong>1048</strong> Обсуждают <strong>48</strong></p>*}
           </li>
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
