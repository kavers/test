{*  <ul class="top_menu">
     <li><a href="http://to.1in.kz">Точка отрыва</a></li>
     <li><a href="http://ts.1in.kz">Точка сбора</a></li>
     <li><a href="#">Чат</a></li>
     <li><a href="http://mail.1in.kz">Почта</a></li>
     <li {if $sAction=='blogs' or $sAction=='index'} class="active"{/if}><a href="/">Блоги</a></li>
     {if $oUserCurrent}
     <li{if $sAction=='blog' && $sMenuSubItemSelect=='add'} class="active"{/if}><a href="{router page='blog'}add/">{$aLang.blog_menu_create}</a></li>
     {/if}
  </ul>
*}  
  <div id="headsm">
     <div id="head_insidesm">
	     <div id="logo_sm"><a href="/"><img src="{cfg name='path.static.skin'}/img//logo_sm.gif" width="133" height="68" alt="Первый Казахстанский: Видео, Музыка, Форум, Обои, Гороскоп, Сонник и многое другое!" title="Первый Казахстанский: Видео, Музыка, Форум, Обои, Гороскоп, Сонник и многое другое!" /></a></div>
	     <div id="search_mainmenu">
			
            <form action="{router page='search'}topics/" method="GET" id="search">
			   <input type="text" onblur="if (!value) value=defaultValue" onclick="if (value==defaultValue) value=''" value="{$aLang.search}" name="q" />
	           <ul>
	              <li class="search_menu"><a href="" class="close">Блоги</a>
	                <ul class="search_menu_ul" style="display:none">
	                  <li><a href="">Везде</a></li>
	                  <li><a href="">Форум</a></li>
	                  <li><a href="">Новости</a></li>
	                  <li><a href="">События</a></li>
	                  <li><a href="">Видео</a></li>
	                  <li><a href="">Музыка</a></li>
	                  <li><a href="">Приколы</a></li>
	                  <li><a href="">Игры</a></li>
	                  <li><a href="">Книги</a></li>
	                  <li><a href="">Обои</a></li>
	                  <li><a href="">Софт</a></li>
	                  <li><a href="">Эротика</a></li>
	                  <li><a href="">Сонник</a></li>
	                  <li><a href="">Гороскоп</a></li>
	                </ul>
	              </li>
	           </ul>
	           <a href="" onclick="$('search').submit();return false;"><img src="{cfg name='path.static.skin'}/img/search_button.gif" width="34" height="33" alt="Искать" title="Искать"/></a>
			</form>
	     </div>
		{if $oUserCurrent}
	     <dl>
	        <dt>
                <a href="{$oUserCurrent->getUserWebPath()}">{$oUserCurrent->getLogin()}</a> (<a href="{router page='login'}exit/?security_ls_key={$LIVESTREET_SECURITY_KEY}">{$aLang.exit}</a>)
                <br />
                {if $iUserCurrentCountTalkNew}
                    <a href="{router page='talk'}" class="message" id="new_messages" title="{$aLang.user_privat_messages_new}">{$iUserCurrentCountTalkNew}</a> 
                {else}
                    <a href="{router page='talk'}" class="message-empty" id="new_messages">&nbsp;</a>
                {/if}
                {$aLang.user_settings} <a href="{router page='settings'}profile/">{$aLang.user_settings_profile}</a> | <a href="{router page='settings'}tuning/">{$aLang.user_settings_tuning}</a>
                <br>
                 {*if $oUserCurrent and ($sAction=='blog' or $sAction=='index' or $sAction=='new' or $sAction=='personal_blog')*}
                 {if $oUserCurrent}
                    <a href="{router page='topic'}add/" alt="{$aLang.topic_create}" title="{$aLang.topic_create}">Новая запись{*$aLang.topic_create*}</a>
                 {/if}
                {$aLang.user_rating} <strong>{$oUserCurrent->getRating()}</strong>    
            </dt>
	        <dd><a href="{$oUserCurrent->getUserWebPath()}"><img src="{$oUserCurrent->getProfileAvatarPath(48)}" alt="{$oUserCurrent->getLogin()}" /></a></dd>
	     </dl>
        {else}
	     <dl>
	        <dt><a href="{router page='registration'}">Создать учётную записть</a><br />или&nbsp;<a href="{router page='login'}" onclick="return showLoginForm();">Войти</a></dt>
	        <dd><img src="{cfg name='path.static.skin'}/img/avatar.gif" width="47" height="44" alt="user_avatar" title="user_avatar"/></dd>
	     </dl>
         {/if}
     </div>
  </div>
  
<!-- Header -->
	{if !$oUserCurrent}	
	<div style="display: none;">
	<div class="login-popup" id="login-form">
		<div class="login-popup-top"><a href="#" class="close-block" onclick="return false;"></a></div>
		<div class="content">
			<form action="{router page='login'}" method="POST">
				<h3>{$aLang.user_authorization}</h3>
				{hook run='form_login_popup_begin'}
				<div class="lite-note"><a href="{router page='registration'}">{$aLang.registration_submit}</a><label for="">{$aLang.user_login}</label></div>
				<p><input type="text" class="input-text" name="login" tabindex="1" id="login-input"/></p>
				<div class="lite-note"><a href="{router page='login'}reminder/" tabindex="-1">{$aLang.user_password_reminder}</a><label for="">{$aLang.user_password}</label></div>
				<p><input type="password" name="password" class="input-text" tabindex="2" /></p>
				{hook run='form_login_popup_end'}
				<div class="lite-note"><button type="submit" onfocus="blur()"><span><em>{$aLang.user_login_submit}</em></span></button><label for="" class="input-checkbox"><input type="checkbox" name="remember" checked tabindex="3" >{$aLang.user_login_remember}</label></div>
				<input type="hidden" name="submit_login">
			</form>
		</div>
		<div class="login-popup-bottom"></div>
	</div>
	</div>
	{/if}
	