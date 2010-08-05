<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="ru" xml:lang="ru">

<head>
	{hook run='html_head_begin'}
	<title>{$sHtmlTitle}</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />	
	<meta name="DESCRIPTION" content="{$sHtmlDescription}" />
	<meta name="KEYWORDS" content="{$sHtmlKeywords}" />	

	{$aHtmlHeadFiles.css}
    <link href="{cfg name='path.static.skin'}/css/style2.css?v=1" type="text/css" rel="stylesheet">
	<link href="{cfg name='path.static.skin'}/css/ext/main.css?v=1" type="text/css" rel="stylesheet"
    <link href="http://kaznetmedia.kz/km_headlinks/km_headlinks.css" type="text/css" rel="stylesheet">
    
	<link href="{cfg name='path.static.skin'}/images/favicon.ico" rel="shortcut icon" />
	<link rel="search" type="application/opensearchdescription+xml" href="{router page='search'}opensearch/" title="{cfg name='view.name'}" />
	
	{if $aHtmlRssAlternate}
		<link rel="alternate" type="application/rss+xml" href="{$aHtmlRssAlternate.url}" title="{$aHtmlRssAlternate.title}">
	{/if}

	<script type="text/javascript" src="{cfg name='path.static.skin'}/js/ext//jquery-1.2.6.min.js"></script>
	
<script language="JavaScript" type="text/javascript">
var DIR_WEB_ROOT='{cfg name="path.root.web"}';
var DIR_STATIC_SKIN='{cfg name="path.static.skin"}';
var BLOG_USE_TINYMCE='{cfg name="view.tinymce"}';
var TALK_RELOAD_PERIOD='{cfg name="module.talk.period"}';
var TALK_RELOAD_REQUEST='{cfg name="module.talk.request"}'; 
var TALK_RELOAD_MAX_ERRORS='{cfg name="module.talk.max_errors"}';
var LIVESTREET_SECURITY_KEY = '{$LIVESTREET_SECURITY_KEY}';

var TINYMCE_LANG='en';
{if $oConfig->GetValue('lang.current')=='russian'}
TINYMCE_LANG='ru';
{/if}

var aRouter=new Array();
{foreach from=$aRouter key=sPage item=sPath}
aRouter['{$sPage}']='{$sPath}';
{/foreach}

</script>

	{$aHtmlHeadFiles.js}

{literal}
<script language="JavaScript" type="text/javascript">
var tinyMCE=false;
var msgErrorBox=new Roar({
			position: 'upperRight',
			className: 'roar-error',
			margin: {x: 30, y: 10}
		});	
var msgNoticeBox=new Roar({
			position: 'upperRight',
			className: 'roar-notice',
			margin: {x: 30, y: 10}
		});	
</script>
{/literal}

{literal}
<script type="text/javascript">
$(document).ready(function () {
var randomnumber=Math.floor(Math.random()*99999999999);
$('body').css({background:'#6E6E6E URL(http://adv.kaznetmedia.kz/www/delivery/avw.php?zoneid=50&cb='+randomnumber+'&n=a12ae2ab) no-repeat center 122px'});
});
</script>
{/literal}

{if $oUserCurrent && $oConfig->GetValue('module.talk.reload')}
{literal}
<script language="JavaScript" type="text/javascript">
    var talkNewMessages=new lsTalkMessagesClass({
    	reload: {
            request: TALK_RELOAD_REQUEST,
        	url: DIR_WEB_ROOT+'/include/ajax/talkNewMessages.php',
        	errors: TALK_RELOAD_MAX_ERRORS
    	}
    });  
	(function(){
   		talkNewMessages.get();
	}).periodical(TALK_RELOAD_PERIOD);
</script>
{/literal}
{/if}

	{hook run='html_head_end'}
</head>

<body onload="prettyPrint()">
{hook run='body_begin'}

<div id="debug" style="border: 2px #dd0000 solid; display: none;"></div>

<div id="page">
{php}
if ($km_headlinks = @file_get_contents('http://kaznetmedia.kz/km_headlinks/km_headlinks.txt'))
{
    echo $km_headlinks;
}
{/php}
{*include file=header_nav.tpl*}
  <div id="head">
     <div id="head_inside">
	     <div id="logo"><img src="{cfg name='path.static.skin'}/img/logo.gif" width="193" height="96" alt="Первый Казахстанский: Видео, Музыка, Форум, Обои, Гороскоп, Сонник и многое другое!" title="Первый Казахстанский: Видео, Музыка, Форум, Обои, Гороскоп, Сонник и многое другое!"/></div>
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
            <div id="tip_box" style="display:none;">
               <ul>
                 <li><a href="">крутые фразы</a><strong>654 000 результатов</strong></li>
                 <li><a href="">крутые картинки</a><strong>1 000 000 результатов</strong></li>
                 <li><a href="">крутые ники</a><strong>273 000 результатов</strong></li>
                 <li><a href="">крутые тачки</a><strong>406 000 результатов</strong></li>
                 <li><a href="">крутые игры</a><strong>293 000 результатов</strong></li>
                 <li><a href="">крутые тачки фото</a><strong>280 000 результатов</strong></li>
                 <li><a href="">крутые бобр</a><strong>3 474 000 результатов</strong></li>
               </ul>
            </div>
	        <ul id="main_menu">
	           <li><a href="{cfg name='path.root.web'}">Главная</a></li>
	           <li><a href="{cfg name='path.root.web'}/video/">Видео</a></li>
	           <li><a href="{cfg name='path.root.web'}/music/">Музыка</a></li>
	           <li><a href="{cfg name='path.root.web'}/jokes/">Приколы</a></li>
	           <li><a href="{cfg name='path.root.web'}/photo/">Обои</a></li>
	           <li><a href="" class="more_menu close">ещё</a>
	              <ul class="all_menu">
	                 <li><a href="http://ts.1in.kz">Форум</a></li>
	                 <li><a href="{cfg name='path.root.web'}">Новости</a></li>
	                 <li><a href="{cfg name='path.root.web'}">События</a></li>
            		 <li><a href="{cfg name='path.root.web'}/games/">Игры</a></li>
   	    	    	 <li><a href="{cfg name='path.root.web'}/soft/">Софт</a></li>
  	        	     <li><a href="{cfg name='path.root.web'}/erotic/">Эротика</a></li>
          	    	 <li><a href="{cfg name='path.root.web'}/dreams/">Сонник</a></li>
        		     <li><a href="{cfg name='path.root.web'}/horo/">Гороскоп</a></li>
   			     </ul>
    	       </li>
 	       </ul>
    	       </li>
 	       </ul>
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
  <div id="point_block">
     <div class="title"><a href="http://to.1in.kz"><h1>Точка отрыва</h1></a></div>
     <div class="slider">
        <ul>
           <li>
               <a href="#"><img src="{cfg name='path.static.skin'}/img/pic.jpg" height="27" alt="Точка отрыва" title="Точка отрыва"/></a>
               <div class="theme"><a href="">Банкир разглядывал голых женщин в прямом эфире</a></div>
               <div class="theme_rating red">круто</div>
               <div class="theme_date">5 минут назад</div>
           </li>
           <li>
               <a href="#"><img src="{cfg name='path.static.skin'}/img/pic.jpg" height="27" alt="Точка отрыва" title="Точка отрыва"/></a>
               <div class="theme"><a href="">Банкир разглядывал голых женщин в прямом эфире</a></div>
               <div class="theme_rating red">круто</div>
               <div class="theme_date">5 минут назад</div>
           </li>
           <li>
               <a href="#"><img src="{cfg name='path.static.skin'}/img/pic.jpg" height="27" alt="Точка отрыва" title="Точка отрыва"/></a>
               <div class="theme"><a href="">Банкир разглядывал голых женщин в прямом эфире</a></div>
               <div class="theme_rating red">круто</div>
               <div class="theme_date">5 минут назад</div>
           </li>
        </ul>
     </div>
  </div>
  {*
<!---   Блогосфера    -->
  <ul id="blogosphere" class="blogosphere">
<!-- Список видео -->
     <li class="block3 green">
        <div class="title"><a href="#111" class="link"><h1>Блогосфера</h1></a><a href="javascript:void(0)"><img class="line_sort" src="{cfg name='path.static.skin'}/img/minus.gif" width="18" height="18" alt="Свернуть" title="Свернуть"/></a></div>
         <ul class="gradient">
           <li class="first3"><a href="">Все</a></li>
           <li><a href="">Популярные записи</a></li>
           <li><a href="">Популярных блогеров</a></li>
           <li><a href="">Знаменитостей</a></li>
           <li><a href="">Записи из сообществ</a></li>
           <li><a href="">Рекомендуем</a></li>
        </ul>
        <div class="block_content">
           <div class="blog_line">
              <a href="#" class="prev"><img src="{cfg name='path.static.skin'}/img/wp_left.png" width="25" height="129" alt="влево" title="влево"/></a>
              <div class="blogs_cloud">
<!--  карточка записи -->
                 <div class="item" style="margin:50px 0px 0px 100px; z-index:1;">
                    <a href="#" class="avatar"><img src="{cfg name='path.static.skin'}/img/avatar.gif" width="40" height="40" alt="avatar" title="avatar"/></a>
                    <a href="#" class="theme">Merhaba, nasilsin? Simdi bira icmeye gidelim?</a>
                    <a href="#" class="nickname">tema,</a> час назад
                    <div class="rating red">круто</div>
                 </div>
<!--  карточка записи -->
<!--  карточка записи -->
                 <div class="item" style="margin:80px 0px 0px 300px; z-index:2;">
                    <a href="#" class="avatar"><img src="{cfg name='path.static.skin'}/img/avatar.gif" width="40" height="40" alt="avatar" title="avatar"/></a>
                    <a href="#" class="theme">Merhaba, nasilsin? Simdi bira icmeye gidelim?</a>
                    <a href="#" class="nickname">tema,</a> час назад
                    <div class="rating red">круто</div>
                 </div>
<!--  карточка записи -->
<!--  карточка записи -->
                 <div class="item" style="margin:50px 0px 0px 500px; z-index:1;">
                    <a href="#" class="avatar"><img src="{cfg name='path.static.skin'}/img/avatar.gif" width="40" height="40" alt="avatar" title="avatar"/></a>
                    <a href="#" class="theme">Merhaba, nasilsin? Simdi bira icmeye gidelim?</a>
                    <a href="#" class="nickname">tema,</a> час назад
                    <div class="rating red">круто</div>
                 </div>
<!--  карточка записи -->
              </div>
              <a href="#" class="next"><img src="{cfg name='path.static.skin'}/img/wp_right.png" width="25" height="129" alt="вправо" title="вправо"/></a>
           </div>
           <ul class="time_scroll">
              <li class="left"><a href="#"><img src="{cfg name='path.static.skin'}/img/left_end.gif" width="13" height="13" alt="влево" title="влево"/></a></li>
              <li>19:00</li>
              <li>21:00</li>
              <li class="act">23:00</li>
              <li>1:00</li>
              <li>3:00</li>
              <li>5:00</li>
              <li>7:00</li>
              <li class="right"><a href="#"><img src="{cfg name='path.static.skin'}/img/right_end.gif" width="13" height="13" alt="вправо" title="вправо"/></a></li>
           </ul>
        <div class="block_bottom5"></div>
        </div>
     </li>
<!-- Список видео -->
  </ul>

*}

<!---   Блогосфера    -->
  <div id="columns">
<!-- Три колонки, это первая -->
  <ul id="column1" class="column left_colomn">
<!--  Блок Популярные блогм -->
     <li id="pop_blogs" class="block dark_green">
        <div class="title"><a href="#111" class="link"><h1>Популярные блогеры</h1></a><a href="#" class="close_block"><img src="{cfg name='path.static.skin'}/img/minus.gif" width="18" height="18" alt="Свернуть блок" title="Свернуть блок"/></a></div>
        <div class="block_content">
        <ul class="gradient">
           <li class="first3"><a href="#">За день</a></li>
           <li><a href="#">За неделю</a></li>
           <li><a href="#">За месяц</a></li>
        </ul>
        <div class="bayan" id="b3">
           <ul>
           {foreach from=$aPopularUsers item=oUser key=key}
              <li>
                 <ul><li><br /><img src="{$oUser->getProfileAvatarPath(100)}" alt="" /></li></ul>
                 <a href="{router page='profile'}{$oUser->getLogin()}/">{$oUser->getLogin()}{if $oUser->getProfileName()} &ndash; {$oUser->getProfileName()}{/if}<strong>{$aPopularUsersFriends[$key].friends}</strong></a>
              </li>
           {/foreach}
           </ul>
        </div>
        <div class="block_bottom"></div>
        </div>
     </li>
<!--  Блок Популярные блоги -->
<!--  Блок Эксперты блогосферы -->
     <li id="blog_experts" class="block green">
        <div class="title"><a href="#111" class="link"><h1>Эксперты блогосферы</h1></a><a href="#" class="close_block"><img src="{cfg name='path.static.skin'}/img/minus.gif" width="18" height="18" alt="Свернуть блок" title="Свернуть блок"/></a></div>
        <div class="block_content">
        <ul class="gradient">
           <li class="first3"><a href="#">Все</a></li>
           <li><a href="#">Весёлые</a></li>
           <li><a href="#">Умные</a></li>
           <li><a href="#">Зануды</a></li>
        </ul>
        <div class="bayan" id="b11">
           <ul>
              <li>
                  <ul>
                     <li>
                       <dl class="bayan_item">
                          <dt><a href="#"><img src="{cfg name='path.static.skin'}/img/alisher.gif" alt="Алишер" title="Алишер" width="60" height="60" /><br />Алишер</a></dt>
                          <dd><a href="#">Не волнуйтесь, вам не грозит увольнение или проблемы на работе, но заняться повышением профессионального уровня не помешает. Не исключено, что вам сегодня придется решить массу вопросов, связанных с делами бизнеса, постарайтесь</a></dd>
                       </dl>
                     </li>
                  </ul>
                  <a href="#">За торренты возьмутся всеръёз</a>
              </li>
              <li>
                 <ul><li><br /><img src="{cfg name='path.static.skin'}/img/book.jpg" width="93" height="145" alt="Книга" title="Книга"/></li></ul>
                 <a href="#">Nero 8</a>
              </li>
              <li>
                 <ul><li><br /><img src="{cfg name='path.static.skin'}/img/book.jpg" width="93" height="145" alt="Книга" title="Книга"/></li></ul>
                 <a href="#">Sony Vegas Pro 8</a>
              </li>
              <li>
                 <ul><li><br /><img src="{cfg name='path.static.skin'}/img/book.jpg" width="93" height="145" alt="Книга" title="Книга"/></li></ul>
                 <a href="#">Esset NOD32</a>
              </li>
              <li>
                 <ul><li><br /><img src="{cfg name='path.static.skin'}/img/book.jpg" width="93" height="145" alt="Книга" title="Книга"/></li></ul>
                 <a href="#">Уильям Пол Янг - Хижина</a>
              </li>
           </ul>
        </div>
        <div class="block_bottom"></div>
        </div>
     </li>
<!--  Блок Эксперты блогосферы -->
  </ul>
  <ul id="column2" class="column middle_colomn">
<!--  Блок Что нового -->
     <li id="whats_news" class="block green">
        <div class="title"><a href="#111" class="link"><h1>Что нового в блогах</h1></a><a href="#" class="close_block"><img src="{cfg name='path.static.skin'}/img/minus.gif" width="18" height="18" alt="Свернуть блок" title="Свернуть блок"/></a></div>
        <div class="block_content">
        <ul class="gradient">
           <li class="first3"><a href="#">За день</a></li>
           <li><a href="#">За неделю</a></li>
           <li><a href="#">За месяц</a></li>
        </ul>
        <div class="bayan" id="b12">
           <ul>
           {foreach from=$aTopics item=oTopic}
			{assign var="oBlog" value=$oTopic->getBlog()} 
			{assign var="oUser" value=$oTopic->getUser()}
              <li>
                 <ul><li><br><img src="{$oBlog->getAvatarPath(48)}" alt="" /></li></ul>
                 <a href="{if $oTopic->getType()=='link'}{router page='link'}go/{$oTopic->getId()}/{else}{$oTopic->getUrl()}{/if}">{$oBlog->getTitle()|escape:'html'} - {$oTopic->getTitle()|escape:'html'}</a>
              </li>
           {/foreach}
           </ul>
        </div>
        <div class="block_bottom"></div>
        </div>
     </li>
<!--  Блок Что нового -->
<!--  Блок Знаменитости -->
     <li id="celebrity" class="block dark_green">
        <div class="title"><a href="#111" class="link"><h1>Знаменитости</h1></a><a href="#" class="close_block"><img src="{cfg name='path.static.skin'}/img/minus.gif" width="18" height="18" alt="Свернуть блок" title="Свернуть блок"/></a></div>
        <div class="block_content">
        <ul class="gradient">
           <li class="first"><a href="#">Все</a></li>
           <li><a href="#">Актёры</a></li>
           <li><a href="#">Музыканты</a></li>
           <li><a class="more_menu close" href="#">ещё</a>
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
                 <li><a href="#">Рэп и хип-хоп</a></li>
                 <li><a href="#">Техно</a></li>
                 <li><a href="#">Электронная музыка</a></li>
              </ul>
           </li>
        </ul>
        <div class="bayan" id="b15">
           <ul>
              <li>
                 <ul><li><br /><img src="{cfg name='path.static.skin'}/img/book.jpg" width="93" height="145" alt="Книга" title="Книга"/></li></ul>
                 <a href="#">Снежана</a>
              </li>
              <li>
                 <ul><li><br /><img src="{cfg name='path.static.skin'}/img/book.jpg" width="93" height="145" alt="Книга" title="Книга"/></li></ul>
                 <a href="#">Ирена Понарошку - Эх, подарю! </a>
              </li>
              <li>
                 <ul><li><br /><img src="{cfg name='path.static.skin'}/img/book.jpg" width="93" height="145" alt="Книга" title="Книга"/></li></ul>
                 <a href="#">Группа Подиум</a>
              </li>
              <li>
                 <ul><li><br /><img src="{cfg name='path.static.skin'}/img/book.jpg" width="93" height="145" alt="Книга" title="Книга"/></li></ul>
                 <a href="#">Лина Тишунова</a>
              </li>
              <li>
                 <ul><li><br /><img src="{cfg name='path.static.skin'}/img/book.jpg" width="93" height="145" alt="Книга" title="Книга"/></li></ul>
                 <a href="#">Ольга Константинова</a>
              </li>
           </ul>
        </div>
        <div class="block_bottom"></div>
        </div>
     </li>
<!--  Блок знаменитости -->
  </ul>
  <ul id="column3" class="column right_colomn">
<!-- Баннер почтового сервиса-->
     <li id="mail_banner" class="block">
		<script type="text/javascript" src="{cfg name='path.static.skin'}/js/ibanleft.js"></script>	
		<noscript><a href='http://adv.kaznetmedia.kz/www/delivery/ck.php?n=ad398848&amp;cb=<?php echo rand(); ?>' target='_blank'><img src='http://adv.kaznetmedia.kz/www/delivery/avw.php?zoneid=49&amp;charset=UTF-8&amp;cb=<?php echo rand(); ?>&amp;n=ad398848' border='0' alt='' /></a></noscript>	 
	 </li>
<!-- Баннер почтового сервиса-->
<!--  Блок сообщества -->
     <li id="community" class="block green">
        <div class="title"><a href="#111" class="link"><h1>Сообщества</h1></a><a href="#" class="close_block"><img src="{cfg name='path.static.skin'}/img/minus.gif" width="18" height="18" alt="Свернуть блок" title="Свернуть блок"/></a></div>
        <div class="block_content">
        <ul class="gradient">
           <li class="first"><a href="{router page='blogs'}">Все</a></li>
           {*
           <li><a href="#">Музыка</a></li>
           <li><a href="#">Книги</a></li>
           <li><a class="more_menu close" href="#">ещё</a>
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
                 <li><a href="#">Рэп и хип-хоп</a></li>
                 <li><a href="#">Техно</a></li>
                 <li><a href="#">Электронная музыка</a></li>
              </ul>
           </li>
           *}
        </ul>
        <div class="bayan" id="b13">
           <ul>
           {foreach from=$aCollectiveBlogs item=oBlog}
              <li>
                  <ul>
                     <li>
                       <dl class="bayan_item">
                          <dt><a href="{$oBlog->getUrlFull()}"><img src="{$oBlog->getAvatarPath(48)}" alt="" /></dt>
                          <dd><a href="{$oBlog->getUrlFull()}">{$oBlog->getDescription()}</a></dd>
                       </dl>
                     </li>
                  </ul>
                  <a href="{$oBlog->getUrlFull()}">{$oBlog->getTitle()}<strong>{$oBlog->getCountUser()}</strong></a>
              </li>
           {/foreach}
           </ul>
        </div>
        <div class="block_bottom"></div>
        </div>
     </li>
<!--  Блок Сообщества -->
  </ul>


  </div>
  <div id="footer">
     <div class="title">
        <h1>Настроить</h1>
        <a href="javascript:void(0)"><img class="tools_button" src="{cfg name='path.static.skin'}/img/minus.gif" width="18" height="18" alt="Свернуть" title="Свернуть"/></a>
        <a href="javascript:void(0)"><img class="recover_button" src="{cfg name='path.static.skin'}/img/recover.gif" width="18" height="18" alt="Восстановление настроек" title="Восстановление настроек"/></a>
        <a href="javascript:void(0)"><img class="help_button" src="{cfg name='path.static.skin'}/img/help.gif" width="18" height="18" alt="Помощь" title="Помощь"/></a>

     </div>
     <div id="tools">
        <ul>
          <li class="dark_green"><input checked type="checkbox" value="" name="pop_blogs"/>Популярные блогеры</li>
          <li class="green"><input checked type="checkbox" value="" name="whats_news"/>Что нового в блогах</li>
          <li class="green"><input checked type="checkbox" value="" name="community"/>Сообщества</li>
          <li class="green"><input checked type="checkbox" value="" name="blog_experts"/>Эксперты блогосферы</li>
          <li class="dark_green"><input checked type="checkbox" value="" name="celebrity"/>Знаменитости</li>
        </ul>
     </div>
     <div class="comments">Одной из наших приоритетных целей является создание полезных пользователю интернет-проектов на рынке Казнета. Первый Казахстанский - портал, где каждый найдет для себя что-то интересное. Мы отбираем для вас лучшее видео, самые смешные приколы, оригинальные обои для рабочего стола, свежие программы, популярные книги, новую музыку и многое другое.  <a href="#">Узнать больше</a></div>
     <div class="search_counters">
        <form action="/search/" method="post" id="search_bottom" name="search_bottom">
           <input type="text" value="">
           <ul>
              <li><a href="javascript:void(0)" onclick="comboboxShow(this)">Везде</a>
                <ul>
                  <li><a href="">Форум</a></li>
                </ul>
              </li>
           </ul>
           <a href=""><img src="{cfg name='path.static.skin'}/img/search_button2.gif" width="28" height="26" alt="Искать" title="Искать"/></a>
        </form>
{*
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
*}
        <ul class="counters">
           <li><a href=""><img src="{cfg name='path.static.skin'}/img/1in_ico.gif" width="16" height="16" alt="1in.kz" title="1in.kz"/></a></li>
           <li><a href=""><img src="{cfg name='path.static.skin'}/img/facebook_ico.gif" width="14" height="14" alt="Facebook" title="Facebook"/></a></li>
           <li><a href=""><img src="{cfg name='path.static.skin'}/img/twitter_ico.gif" width="14" height="16" alt="Twitter" title="Twitter"/></a></li>
           <li><a href=""><img src="{cfg name='path.static.skin'}/img/b_ico.gif" width="16" height="16" alt="В контакте" title="В контакте"/></a></li>
           <li><a href=""><img src="{cfg name='path.static.skin'}/img/zero_ico.gif" width="88" height="31" alt="Zero" title="Zero"/></a></li>
           <li><a href=""><img src="{cfg name='path.static.skin'}/img/counter_ico.gif" width="103" height="31" alt="Kaznet" title="Kaznet"/></a></li>
        </ul>
     </div>
  </div>
  <div id="copyright">
     <a href="#"><img src="{cfg name='path.static.skin'}/img/kaznet_logo.gif" width="60" height="49" alt="Kaznet media" title="Kaznet media"/></a>
     &copy;&nbsp;2009-2010&nbsp;&laquo;Первый казахстанский&raquo;
     <ul>
        <li><a href="">О проекте</a></li>
        <li>|</li>
        <li><a href="">Блог</a></li>
        <li>|</li>
        <li><a href="">Контактная информация</a></li>
        <li>|</li>
        <li><a href="">Реклама на сайте</a></li>
     </ul>
  </div>
</div>

<script type="text/javascript" src="{cfg name='path.static.skin'}/js/ext//jquery-ui-personalized-1.6rc2.min.js"></script>
<script type="text/javascript" src="{cfg name='path.static.skin'}/js/ext//inettuts.js"></script>
</body>
</html>

