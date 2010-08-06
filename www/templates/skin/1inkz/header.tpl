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
	<link href="{cfg name='path.static.skin'}/css/ext/main.css?v=1" type="text/css" rel="stylesheet">
    
    <link href="{cfg name='path.static.skin'}/css/ext/km_headlinks.css" type="text/css" rel="stylesheet">
    
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

<!--[if IE 6]>
<script type="text/javascript" src="{cfg name='path.static.skin'}/js/ext/DD_belatedPNG_0.0.8a-min.js"></script>
<script type="text/javascript">
	DD_belatedPNG.fix('#tip_box, #tip_box ul, .slider, #prevBtn, #nextBtn, div.item,'+ 
    '.block_hide, .block_bottom, .block_bottom2, .block_bottom3, .block_bottom4,'+
    'li.first, li.first2, li.first3, li.first a, li.first2 a, li.first3 a,'+
    'a.close, a.open, li.close a, a.opened,'+
    '.audio_play, .video_play, a.smile, div.add, .user_info, .vipblog_info, .notes_info,'+
    '.cur_rating a, #circle, .title, .title2, .block .white');
</script>
<![endif]-->

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

<body class="work" onload="prettyPrint()">
{php}
if ($km_headlinks = @file_get_contents('http://kaznetmedia.kz/km_headlinks/km_headlinks.txt'))
{
    echo $km_headlinks;
}
{/php}
{hook run='body_begin'}

<div id="debug" style="border: 2px #dd0000 solid; display: none;"></div>

<div id="page">
  
  {include file=header_top.tpl}
  {*include file=header_nav.tpl*}
  
  {if !$noShowSystemMessage}
	  {include file='system_message.tpl'}
  {/if}
  <div id="columns">
<!-- Две колонки, это первая -->
  <ul id="column_big" class="big_colomn">
  
