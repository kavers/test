{include file='header.tpl' menu='talk' noShowSystemMessage=false}
<li id="video_player" class="block2 orange">
    <div class="title"><a href="#" class="link"><h1>{$aLang.talk_inbox}</h1></a></div>
    <div class="block_content">

            <div id="text" class="topic people top-blogs talk-table">
				<form action="{router page='talk'}" method="post" id="form_talks_list">
         <div class="right_text settings" style="width:100%">
        <div id="add_book_format">
				<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" /> 
				<table>
					<thead>
						<tr>
							<td width="20px"><input type="checkbox" name="" onclick="checkAllTalk(this);"></td>
							<td class="user" width="200px"><b>{$aLang.talk_inbox_target}</b></td>
							<td></th>
							<td><b>{$aLang.talk_inbox_title}</b></td>
							<td><b>{$aLang.talk_inbox_date}</b></td>
						</tr>
					</thead>
					
					<tbody>
					{foreach from=$aTalks item=oTalk}
						{assign var="oTalkUserAuthor" value=$oTalk->getTalkUser()}
						<tr>
							<td><input type="checkbox" name="talk_del[{$oTalk->getId()}]" class="form_talks_checkbox"></td>
							<td class="name">							
								{foreach from=$oTalk->getTalkUsers() item=oTalkUser name=users}
									{if $oTalkUser->getUserId()!=$oUserCurrent->getId()}
									{assign var="oUser" value=$oTalkUser->getUser()}
										<a href="{$oUser->getUserWebPath()}" class="orange author {if $oTalkUser->getUserActive()!=$TALK_USER_ACTIVE}inactive{/if}">{$oUser->getLogin()}</a>
									{/if}
								{/foreach}
							</td>							
							<td class="talk">
								<span class="favorite {if $oTalk->getIsFavourite()}active{/if}">
									<a href="#" onclick="lsFavourite.toggle({$oTalk->getId()},this,'talk'); return false;"></a>
								</span>
							</td>
							<td>	
							{if $oTalkUserAuthor->getCommentCountNew() or !$oTalkUserAuthor->getDateLast()}
								<a class="orange" href="{router page='talk'}read/{$oTalk->getId()}/"><b>{$oTalk->getTitle()|escape:'html'}</b></a>
							{else}
								<a class="orange" href="{router page='talk'}read/{$oTalk->getId()}/">{$oTalk->getTitle()|escape:'html'}</a>
							{/if}
					 		&nbsp;	
							{if $oTalk->getCountComment()}
								{$oTalk->getCountComment()} {if $oTalkUserAuthor->getCommentCountNew()}<span style="color: #008000;">+{$oTalkUserAuthor->getCommentCountNew()}</span>{/if}
							{/if}
							</td>
							<td class="gray">{date_format date=$oTalk->getDate()}</td>
						</tr>
					{/foreach}
					</tbody>
				</table>
				<input type="submit" name="submit_talk_del" value="{$aLang.talk_inbox_delete}" onclick="return ($$('.form_talks_checkbox:checked').length==0)?false:confirm('{$aLang.talk_inbox_delete_confirm}');">
                </div></div>
				</form>
			</div>
            {include file='paging.tpl' aPaging=`$aPaging`}
        </div>
    </li>

{include file='footer.tpl'}