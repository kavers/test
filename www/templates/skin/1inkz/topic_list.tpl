<script>

{literal}
jQuery.noConflict();
jQuery(document).ready(function($){
function show_reply(el){
    var text = $('#add_c textarea')[0] && $('#add_c textarea')[0].value || '';
    $('#add_c').remove();
    $(el).hide().parent().parent().after('<div id="add_c" class="add_comment" style="width:'
        + ($(el).hide().parent().parent().width()) + 'px;margin-left:'
        + (parseInt($(el).hide().parent().parent().css('margin-left'))||12) + 'px;">'
        + '<form action="" method="post"><textarea>' + text + '</textarea>'
        + '<input type="submit" class="submit" value="" /></form></div>');
}
$(document).ready(function(){
    $('dl.comment_item').hover(
        function(e){
            if($(this).next().attr('id') != 'add_c')
                $($(this).children()[0]).prepend('<div class="add"><a href="#"><img src="{/literal}{cfg name='path.static.skin'}{literal}/img/comment_ico3.png" width="24" height="24" alt="" /></a><a href="#"><img src="{/literal}{cfg name='path.static.skin'}{literal}/img/comment_ico2.png" width="24" height="24" alt="" /></a><a href="#" onclick="show_reply(this.offsetParent);return false;"><img src="{/literal}{cfg name='path.static.skin'}{literal}/img/comment_ico1.png" width="24" height="24" alt="" /></a></div>');
        },
        function(){$(this).find('.add').remove();}
    );
});
});
{/literal}

</script>
            
<!-- Блог -->
     <li id="blog_list" class="block2 green">
        <div class="title"><a href="/" class="link"><h1>Блоги</h1></a>
        {if $blog_title}{$blog_title}{/if}
        </div>
        {if $show_submenu}
         <ul class="gradient">
           <li class="first2"><a href="">Последние</a></li>
           <li><a href="">Популярные</a></li>
           <li><a href="">Обсуждаемые</a></li>
           <li><a class="close" href="">По рейтингу</a>
           </li>
           <li class="right"><a class="more_menu close" href="">За неделю</a>
                 <ul class="all_menu">
                 <li><a href="#">Все</a></li>
                 <li><a href="#">За день</a></li>
                 <li><a href="#">За неделю</a></li>
                 <li><a href="#">За месяц</a></li>
                 <li><a href="#">За год</a></li>
              </ul>
           </li>
        </ul>
        {/if}
        <div class="block_content">
           <ul class="line_list">
{hook run='html_pluginTopicfix_show'}
{if count($aTopics)>0}	
	{foreach from=$aTopics item=oTopic}   
			{assign var="oBlog" value=$oTopic->getBlog()} 
			{assign var="oUser" value=$oTopic->getUser()} 
			{assign var="oVote" value=$oTopic->getVote()} 
			<!-- Topic -->	
            {*  <li class="when">
                 <strong>Сегодня</strong>
              </li>
              *}
              <li class="note">
                 {if $page_type=="tag"}
                     <a href="#" class="blog_avatar"><img src="{$oBlog->getAvatarPath(48)}"/></a>
                     <h1>
                     {if $oBlog->getType() == 'personal'}
                        <a href="{router page='my'}{$oUser->getLogin()}">{$oUser->getLogin()}</a> &#151; <a href="{if $oTopic->getType()=='link'}{router page='link'}go/{$oTopic->getId()}/{else}{$oTopic->getUrl()}{/if}">{$oTopic->getTitle()|escape:'html'}</a>
                     {else}
                        <a href="{router page='my'}{$oUser->getLogin()}">{$oUser->getLogin()}</a> в &laquo;<a href="{$oBlog->getUrlFull()}">{$oBlog->getTitle()}</a>&raquo; &#151; <a href="{if $oTopic->getType()=='link'}{router page='link'}go/{$oTopic->getId()}/{else}{$oTopic->getUrl()}{/if}">{$oTopic->getTitle()|escape:'html'}</a>
                     {/if}
                     </h1>
                 {elseif $page_type=="my"}
                     <a href="#" class="blog_avatar"><img src="{$oUser->getProfileAvatarPath(48)}"/></a>
                     <h1>
                     {if $oBlog->getType() == 'personal'}
                        <a href="{if $oTopic->getType()=='link'}{router page='link'}go/{$oTopic->getId()}/{else}{$oTopic->getUrl()}{/if}">{$oTopic->getTitle()|escape:'html'}</a>
                     {else}
                        В &laquo;<a href="{$oBlog->getUrlFull()}">{$oBlog->getTitle()}</a>&raquo; &#151; <a href="{if $oTopic->getType()=='link'}{router page='link'}go/{$oTopic->getId()}/{else}{$oTopic->getUrl()}{/if}">{$oTopic->getTitle()|escape:'html'}</a>
                     {/if}
                     </h1>
                 {else}
                     <a href="#" class="blog_avatar"><img src="{$oUser->getProfileAvatarPath(48)}"/></a>
                     <h1><a href="{router page='my'}{$oUser->getLogin()}">{$oUser->getLogin()}</a> &#151; <a href="{if $oTopic->getType()=='link'}{router page='link'}go/{$oTopic->getId()}/{else}{$oTopic->getUrl()}{/if}">{$oTopic->getTitle()|escape:'html'}</a></h1>                 
                 {/if}
                {*if $oTopic->getCountComment()>0*}
                    <a class="comments_num block" href="{$oTopic->getUrl()}#comments" title="{$aLang.topic_comment_read}">{$oTopic->getCountComment()}
                    {if $oTopic->getCountComment() == 0 || $oTopic->getCountComment() > 4 && $oTopic->getCountComment() < 21}комментариев
                    {elseif $oTopic->getCountComment() == 1 || $oTopic->getCountComment() == 21}комментарий
                    {else}комментария{/if}
                    </span>
                    {*if $oTopic->getCountCommentNew()}<span class="green">+{$oTopic->getCountCommentNew()}</span>{/if*}</a>
                {*else}
                    <a class="comments_num block" href="{$oTopic->getUrl()}#comments" title="{$aLang.topic_comment_add}"><span class="red">{$aLang.topic_comment_add}</span></a>
                {/if*}
				<ul class="tags">
					{foreach from=$oTopic->getTagsArray() item=sTag name=tags_list}
						<li><a href="{router page='tag'}{$sTag|escape:'html'}/">{$sTag|escape:'html'}</a>{if !$smarty.foreach.tags_list.last}, {/if}</li>
					{/foreach}								
				</ul>
                  {*<p class="dop_info">Язык записи <strong>Русский</strong></p>*}  
                  {*<p  class="blog_title">Несколько картинок со вчерашнего выступлениея ( <a href="#">и так далее</a> )</p>*}
    {$oTopic->getTextShort()}
    {if $oTopic->getTextShort()!=$oTopic->getText()}
        <br><br>( <a href="{$oTopic->getUrl()}" title="{$aLang.topic_read_more}">
        {if $oTopic->getCutText()}
            {$oTopic->getCutText()}
        {else}
            {$aLang.topic_read_more}
        {/if}      			
        </a> )
    {/if}
                  <p>{date_format date=$oTopic->getDateAdd()}</p>
                  {*<div class="rating red">круто</div>*}
              </li>
    {/foreach}	
		
           </ul>
    {include file='paging.tpl' aPaging=`$aPaging`}			
	
{else}
{$aLang.blog_no_topic}
{/if}
        
        </div>
        <div class="block_bottom3"></div>
     </li>

<!-- Блог -->
            
{*

{if count($aTopics)>0}	
	{foreach from=$aTopics item=oTopic}   
			{assign var="oBlog" value=$oTopic->getBlog()} 
			{assign var="oUser" value=$oTopic->getUser()} 
			{assign var="oVote" value=$oTopic->getVote()} 
			<!-- Topic -->			
			<div class="topic">
				
				<div class="favorite {if $oUserCurrent}{if $oTopic->getIsFavourite()}active{/if}{else}fav-guest{/if}"><a href="#" onclick="lsFavourite.toggle({$oTopic->getId()},this,'topic'); return false;"></a></div>
				
				<h1 class="title">		
					{if $oTopic->getPublish()==0}	
						<img src="{cfg name='path.static.skin'}/images/topic_unpublish.gif" border="0" title="{$aLang.topic_unpublish}" width="16" height="16" alt="{$aLang.topic_unpublish}">
					{/if}			
					<a href="{if $oTopic->getType()=='link'}{router page='link'}go/{$oTopic->getId()}/{else}{$oTopic->getUrl()}{/if}">{$oTopic->getTitle()|escape:'html'}</a>
					{if $oTopic->getType()=='link'}
  						<img src="{cfg name='path.static.skin'}/images/link_url_big.gif" border="0" title="{$aLang.topic_link}" width="16" height="16" alt="{$aLang.topic_link}">
  					{/if}
				</h1>
				<ul class="action">
					<li><a href="{$oBlog->getUrlFull()}">{$oBlog->getTitle()|escape:'html'}</a>&nbsp;&nbsp;</li>										
					{if $oUserCurrent and ($oUserCurrent->getId()==$oTopic->getUserId() or $oUserCurrent->isAdministrator() or $oBlog->getUserIsAdministrator() or $oBlog->getUserIsModerator() or $oBlog->getOwnerId()==$oUserCurrent->getId())}
  						<li class="edit"><a href="{cfg name='path.root.web'}/{$oTopic->getType()}/edit/{$oTopic->getId()}/" title="{$aLang.topic_edit}">{$aLang.topic_edit}</a></li>
  					{/if}
					{if $oUserCurrent and ($oUserCurrent->isAdministrator() or $oBlog->getUserIsAdministrator() or $oBlog->getOwnerId()==$oUserCurrent->getId())}
							<li class="delete"><a href="{router page='topic'}delete/{$oTopic->getId()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}" title="{$aLang.topic_delete}" onclick="return confirm('{$aLang.topic_delete_confirm}');">{$aLang.topic_delete}</a></li>
					{/if}
				</ul>				
				<div class="content">
				
			{if $oTopic->getType()=='question'}   
    		
    		<div id="topic_question_area_{$oTopic->getId()}">
    		{if !$oTopic->getUserQuestionIsVote()} 		
    			<ul class="poll-new">	
				{foreach from=$oTopic->getQuestionAnswers() key=key item=aAnswer}				
					<li><label for="topic_answer_{$oTopic->getId()}_{$key}"><input type="radio" id="topic_answer_{$oTopic->getId()}_{$key}" name="topic_answer_{$oTopic->getId()}"  value="{$key}" onchange="$('topic_answer_{$oTopic->getId()}_value').setProperty('value',this.value);"/> {$aAnswer.text|escape:'html'}</label></li>				
				{/foreach}
					<li>
					<input type="submit"  value="{$aLang.topic_question_vote}" onclick="ajaxQuestionVote({$oTopic->getId()},$('topic_answer_{$oTopic->getId()}_value').getProperty('value'));">
					<input type="submit"  value="{$aLang.topic_question_abstain}"  onclick="ajaxQuestionVote({$oTopic->getId()},-1)">
					</li>				
					<input type="hidden" id="topic_answer_{$oTopic->getId()}_value" value="-1">				
				</ul>				
				<span>{$aLang.topic_question_vote_result}: {$oTopic->getQuestionCountVote()}. {$aLang.topic_question_abstain_result}: {$oTopic->getQuestionCountVoteAbstain()}</span><br>			
			{else}			
				{include file='topic_question.tpl'}
			{/if}
			</div>
			<br>	
						
    		{/if}
				
					{$oTopic->getTextShort()}
					{if $oTopic->getTextShort()!=$oTopic->getText()}
      					<br><br>( <a href="{$oTopic->getUrl()}" title="{$aLang.topic_read_more}">
      					{if $oTopic->getCutText()}
      						{$oTopic->getCutText()}
      					{else}
      						{$aLang.topic_read_more}
      					{/if}      			
      					</a> )
      				{/if}
				</div>				
				<ul class="tags">
					{foreach from=$oTopic->getTagsArray() item=sTag name=tags_list}
						<li><a href="{router page='tag'}{$sTag|escape:'html'}/">{$sTag|escape:'html'}</a>{if !$smarty.foreach.tags_list.last}, {/if}</li>
					{/foreach}								
				</ul>				
				<ul class="voting {if $oVote || ($oUserCurrent && $oTopic->getUserId()==$oUserCurrent->getId()) || strtotime($oTopic->getDateAdd())<$smarty.now-$oConfig->GetValue('acl.vote.topic.limit_time')}{if $oTopic->getRating()>0}positive{elseif $oTopic->getRating()<0}negative{/if}{/if} {if !$oUserCurrent || $oTopic->getUserId()==$oUserCurrent->getId() || strtotime($oTopic->getDateAdd())<$smarty.now-$oConfig->GetValue('acl.vote.topic.limit_time')}guest{/if} {if $oVote} voted {if $oVote->getDirection()>0}plus{elseif $oVote->getDirection()<0}minus{/if}{/if}">
					<li class="plus"><a href="#" onclick="lsVote.vote({$oTopic->getId()},this,1,'topic'); return false;"></a></li>
					<li class="total" title="{$aLang.topic_vote_count}: {$oTopic->getCountVote()}">{if $oVote || ($oUserCurrent && $oTopic->getUserId()==$oUserCurrent->getId()) || strtotime($oTopic->getDateAdd())<$smarty.now-$oConfig->GetValue('acl.vote.topic.limit_time')} {if $oTopic->getRating()>0}+{/if}{$oTopic->getRating()} {else} <a href="#" onclick="lsVote.vote({$oTopic->getId()},this,0,'topic'); return false;">&mdash;</a> {/if}</li>
					<li class="minus"><a href="#" onclick="lsVote.vote({$oTopic->getId()},this,-1,'topic'); return false;"></a></li>
					<li class="date">{date_format date=$oTopic->getDateAdd()}</li>
					{if $oTopic->getType()=='link'}
						<li class="link"><a href="{router page='link'}go/{$oTopic->getId()}/" title="{$aLang.topic_link_count_jump}: {$oTopic->getLinkCountJump()}">{$oTopic->getLinkUrl(true)}</a></li>
					{/if}
					<li class="author"><a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a></li>		
					<li class="comments-total">
						{if $oTopic->getCountComment()>0}
							<a href="{$oTopic->getUrl()}#comments" title="{$aLang.topic_comment_read}"><span class="red">{$oTopic->getCountComment()}</span>{if $oTopic->getCountCommentNew()}<span class="green">+{$oTopic->getCountCommentNew()}</span>{/if}</a>
						{else}
							<a href="{$oTopic->getUrl()}#comments" title="{$aLang.topic_comment_add}"><span class="red">{$aLang.topic_comment_add}</span></a>
						{/if}
					</li>
					{hook run='topic_show_info' topic=$oTopic}
				</ul>
			</div>
			<!-- /Topic -->
	{/foreach}	
		
    {include file='paging.tpl' aPaging=`$aPaging`}			
	
{else}
{$aLang.blog_no_topic}
{/if}
*}
