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
  <div id="head">
     <div id="head_inside">
	     <div id="logo"><img src="{cfg name='path.static.skin'}/img/logo.gif" width="193" height="96" alt="Первый Казахстанский: Видео, Музыка, Форум, Обои, Гороскоп, Сонник и многое другое!" title="Первый Казахстанский: Видео, Музыка, Форум, Обои, Гороскоп, Сонник и многое другое!"/></div>
	     <div id="search_mainmenu">
	        <form action="/search/" method="post" id="search">
	           <input type="text" value="" />
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
	           <a href=""><img src="{cfg name='path.static.skin'}/img/search_button.gif" width="34" height="33" alt="Искать" title="Искать"/></a>
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
	           <li><a href="/">Главная</a></li>
	           <li><a href="#">Видео</a></li>
	           <li><a href="#">Музыка</a></li>
	           <li><a href="#">Приколы</a></li>
	           <li><a href="#">Обои</a></li>
	           <li><a href="#" class="more_menu close">ещё</a>
	              <ul class="all_menu">
	                 <li><a href="#">Форум</a></li>
	                 <li><a href="#">Новости</a></li>
	                 <li><a href="#">События</a></li>
            		 <li><a href="#">Игры</a></li>
    	      	     <li><a href="#">Обои</a></li>
   	    	    	 <li><a href="#">Софт</a></li>
  	        	     <li><a href="#">Эротика</a></li>
          	    	 <li><a href="#">Сонник</a></li>
        		     <li><a href="#">Гороскоп</a></li>
   			     </ul>
    	       </li>
 	       </ul>
	     </div>
	
        {if $oUserCurrent}
        
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
  {if !$noShowSystemMessage}
	  {include file='system_message.tpl'}
  {/if}
  <div id="columns">
<!-- Две колонки, это первая -->
  <ul id="column_big" class="big_colomn">