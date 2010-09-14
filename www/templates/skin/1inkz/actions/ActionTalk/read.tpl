{include file='header.tpl' menu='talk' showUpdateButton=true}

{assign var="oUser" value=$oTalk->getUser()}
<li id="video_player" class="block2 green">
    <div class="title">
        <a href="#" class="link"><h1>{$oTalk->getTitle()|escape:'html'}</h1></a>
        <p class="cl"></p>
        <ul class="block_menu">
            <li><a href="{router page='talk'}">{$aLang.talk_inbox}</a></li>
            <li class="delete"><a href="{router page='talk'}delete/{$oTalk->getId()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}"  onclick="return confirm('{$aLang.talk_inbox_delete_confirm}');">{$aLang.talk_inbox_delete}</a></li>
        </ul>
    </div>
    <div class="block_content">
        
		<div style="padding:15px" id="text">
		    <div class="favorite {if $oTalk->getIsFavourite()}active{else}guest{/if}"><a href="#" onclick="lsFavourite.toggle({$oTalk->getId()},this,'talk'); return false;"></a></div>			
            <div class="content">
                {$oTalk->getText()}
            </div>				
            <ul class="descr1">
                <li class="date">{date_format date=$oTalk->getDate()}</li>
                <li class="author"><a class="orange" href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a></li>
            </ul>
        </div>
			{*
			{assign var="oTalkUser" value=$oTalk->getTalkUser()}
			
			{if !$bNoComments}
			{include 
				file='comment_tree.tpl' 	
				iTargetId=$oTalk->getId()
				sTargetType='talk'
				iCountComment=$oTalk->getCountComment()
				sDateReadLast=$oTalkUser->getDateLast()
				sNoticeCommentAdd=$aLang.topic_comment_add
				bNoCommentFavourites=true
			}
			{/if}
			*}
        </div>
    </li>			
{include file='footer.tpl'}