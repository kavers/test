			{assign var="oBlog" value=$oTopic->getBlog()} 
			{assign var="oUser" value=$oTopic->getUser()}
			{assign var="oVote" value=$oTopic->getVote()} 
            
<!-- Видео плеер -->
     <li id="video_player" class="block2 green">
        <div class="title">
            <a href="/" class="link"><h1>Блоги</h1></a><img src="{cfg name='path.static.skin'}/img/arrow.gif" width="7" height="12" alt="arrow" title="arrow" class="arrow"/>
            {if $sMenuItemSelect=='log'}
            <a href="{router page='people'}" class="link"><h1>Личные блоги</h1></a>
            {elseif $sMenuItemSelect=='blog'}
            <a href="{router page='blogs'}" class="link"><h1>Сообщества</h1></a>
            {/if}
            <img src="{cfg name='path.static.skin'}/img/arrow.gif" width="7" height="12" alt="arrow" title="arrow" class="arrow"/>
            <a class="link" href="{$oBlog->getUrlFull()}"><h1>{$oBlog->getTitle()|escape:'html'}</h1></a>
            <img src="{cfg name='path.static.skin'}/img/arrow.gif" width="7" height="12" alt="arrow" title="arrow" class="arrow"/>
            <a href="{$oTopic->getUrlFull()}" class="link"><h1>{$oTopic->getTitle()|escape:'html'}</h1></a>
            {if $oUserCurrent and ($oUserCurrent->isAdministrator() or $oBlog->getUserIsAdministrator() or $oBlog->getUserIsModerator() or $oBlog->getOwnerId()==$oUserCurrent->getId())}
            <p class="cl"></p>
            <ul class="block_menu">
                {if $oUserCurrent and ($oUserCurrent->getId()==$oTopic->getUserId() or $oUserCurrent->isAdministrator() or $oBlog->getUserIsAdministrator() or $oBlog->getUserIsModerator() or $oBlog->getOwnerId()==$oUserCurrent->getId())}
                    <li class="edit"><a href="{cfg name='path.root.web'}/{$oTopic->getType()}/edit/{$oTopic->getId()}/" title="{$aLang.topic_edit}">{$aLang.topic_edit}</a></li>
                {/if}
                {if $oUserCurrent and ($oUserCurrent->isAdministrator() or $oBlog->getUserIsAdministrator() or $oBlog->getOwnerId()==$oUserCurrent->getId())}
                    <li class="delete"><a href="{router page='topic'}delete/{$oTopic->getId()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}" title="{$aLang.topic_delete}" onclick="return confirm('{$aLang.topic_delete_confirm}');">{$aLang.topic_delete}</a></li>
                {/if}
            </ul>
            {/if}
        </div>
        <div class="block_content">
           <ul class="news_nav">
             {if $prev}<li class="prev"><a href="{router page='blog'}{$oBlog->getUrl()}/{$prev}.html">Предыдущая</a></li>{/if}
             {if $next}<li class="next"><a href="{router page='blog'}{$oBlog->getUrl()}/{$next}.html">Следующая</a></li>{/if}
           </ul>
           <div class="news_block">
             <div class="news_inside">
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
{$oTopic->getText()}
             </div>
           </div>

           <ul class="add_icons dream_icons">
             <li><a href="#" class="more_menu2 close"><img src="{cfg name='path.static.skin'}/img/icon1.png" width="44" height="44" alt="Поделиься" title="Поделиться"/>Поделиться</a>
                <ul class="all_menu share">
                    <li><a rel="nofollow" target="_blank" href="http://twitter.com/home?status=%231inkz%20{$oTopic->getTitle()|escape:'html'}%20{$oTopic->getUrl()}" title="Добавить в Twitter"><img src="{cfg name='path.static.skin'}/img/twitter.png" title="Опубликовать в Twitter" alt="Опубликовать в Twitter" /></a></li>
                    <li><a rel="nofollow" target="_blank" href="http://www.facebook.com/sharer.php?u={$oTopic->getUrl()}"><img src="{cfg name='path.static.skin'}/img/facebook.png" title="Поделиться в Facebook" alt="Поделиться в Facebook" /></a></li>
                    <li><a rel="nofollow" target="_blank" href="http://vkontakte.ru/share.php?url={$oTopic->getUrl()}"><img src="{cfg name='path.static.skin'}/img/vkontakte.png" title="Поделиться ВКонтакте" alt="Поделиться ВКонтакте" /></a></li>
                    <li><a rel="nofollow" target="_blank" href="http://connect.mail.ru/share?share_url={$oTopic->getUrl()}"><img src="{cfg name='path.static.skin'}/img/mailru.png" title="Поделиться в Моём Мире" alt="Поделиться в Моём Мире" /></a></li>
                    <li><a rel="nofollow" target="_blank" href="http://www.google.com/bookmarks/mark?op=edit&bkmk={$oTopic->getUrl()}&title={$oTopic->getTitle()|escape:'html'}&annotation=" ><img src="{cfg name='path.static.skin'}/img/google.png" title="Сохранить в Закладки Google" alt="Сохранить в Закладки Google " /></a></li>
                    <li><a rel="nofollow" target="_blank" href="http://www.google.com/buzz/post?url={$oTopic->getUrl()}&title={$oTopic->getTitle()|escape:'html'}&srcURL=http://1in.kz/" ><img src="{cfg name='path.static.skin'}/img/buzz.png" title="Поделиться в Google Buzz" alt="Поделиться в Google Buzz" /></a></li>
                    <li><a rel="nofollow" target="_blank" href="http://delicious.com/post?url={$oTopic->getUrl()}&title={$oTopic->getTitle()|escape:'html'}"><img src="{cfg name='path.static.skin'}/img/delicious.png" title="Добавить в Delicious" alt="Добавить в Delicious" /></a></li>
                    <li><a rel="nofollow" target="_blank" href="http://www.bobrdobr.ru/add.html?url={$oTopic->getUrl()}&title={$oTopic->getTitle()|escape:'html'}"><img src="{cfg name='path.static.skin'}/img/bobrdobr.png" title="Добавить в BobrDobr" alt="Добавить в BobrDobr" /></a></li> 
 			    </ul>
             </li>
             <li><div class="favorite {if $oUserCurrent}{if $oTopic->getIsFavourite()}active{/if}{else}fav-guest{/if}"><a href="" onclick="lsFavourite.toggle({$oTopic->getId()},this,'topic'); return false;"><img src="{cfg name='path.static.skin'}/img/icon3.gif" width="44" height="44" alt="В избранное" title="В избранное"/>В закладки</a></div></li>
             <li>{hook run='html_pluginSendtofriend'}
             </li>
           </ul>
        <div class="block_bottom2"></div>
        </div>
     </li>
<!-- Видео плеер -->

{*			
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
					{$oTopic->getTitle()|escape:'html'}
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
				
					{$oTopic->getText()}
				</div>				
				<ul class="tags">
					{foreach from=$oTopic->getTagsArray() item=sTag name=tags_list}
						<li><a href="{router page='tag'}{$sTag|escape:'html'}/">{$sTag|escape:'html'}</a>{if !$smarty.foreach.tags_list.last}, {/if}</li>
					{/foreach}									
				</ul>				
				<ul class="voting {if $oVote || ($oUserCurrent && $oTopic->getUserId()==$oUserCurrent->getId())|| strtotime($oTopic->getDateAdd())<$smarty.now-$oConfig->GetValue('acl.vote.topic.limit_time')}{if $oTopic->getRating()>0}positive{elseif $oTopic->getRating()<0}negative{/if}{/if} {if !$oUserCurrent || $oTopic->getUserId()==$oUserCurrent->getId() || strtotime($oTopic->getDateAdd())<$smarty.now-$oConfig->GetValue('acl.vote.topic.limit_time')}guest{/if} {if $oVote} voted {if $oVote->getDirection()>0}plus{elseif $oVote->getDirection()<0}minus{/if}{/if}">
					<li class="plus"><a href="#" onclick="lsVote.vote({$oTopic->getId()},this,1,'topic'); return false;"></a></li>
					<li class="total" title="{$aLang.topic_vote_count}: {$oTopic->getCountVote()}">{if $oVote || ($oUserCurrent && $oTopic->getUserId()==$oUserCurrent->getId()) || strtotime($oTopic->getDateAdd())<$smarty.now-$oConfig->GetValue('acl.vote.topic.limit_time')} {if $oTopic->getRating()>0}+{/if}{$oTopic->getRating()} {else} <a href="#" onclick="lsVote.vote({$oTopic->getId()},this,0,'topic'); return false;">&mdash;</a> {/if}</li>
					<li class="minus"><a href="#" onclick="lsVote.vote({$oTopic->getId()},this,-1,'topic'); return false;"></a></li>
					<li class="date">{date_format date=$oTopic->getDateAdd()}</li>
					{if $oTopic->getType()=='link'}
						<li class="link"><a href="{router page='link'}go/{$oTopic->getId()}/" title="{$aLang.topic_link_count_jump}: {$oTopic->getLinkCountJump()}">{$oTopic->getLinkUrl(true)}</a></li>						
					{/if}
					<li class="author"><a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a></li>
					{hook run='topic_show_info' topic=$oTopic}
				</ul>
			</div>
			<!-- /Topic -->
            *}
