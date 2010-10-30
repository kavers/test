{if count($aTopicsFixed)>0}
	{foreach from=$aTopicsFixed item=oTopic}
			{assign var="oBlog" value=$oTopic->getBlog()} 
			{assign var="oUser" value=$oTopic->getUser()} 
			{assign var="oVote" value=$oTopic->getVote()} 
			<!-- Topic -->
			<li class="note fresh">
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
					<a class="comments_num block" href="{$oTopic->getUrl()}#comments" title="{$aLang.topic_comment_read}">{$oTopic->getCountComment()}
					{if $oTopic->getCountComment() == 0 || $oTopic->getCountComment() > 4 && $oTopic->getCountComment() < 21}комментариев
					{elseif $oTopic->getCountComment() == 1 || $oTopic->getCountComment() == 21}комментарий
					{else}комментария{/if}
					</span>
					</a>

				<ul class="tags">
					{foreach from=$oTopic->getTagsArray() item=sTag name=tags_list}
						<li><a href="{router page='tag'}{$sTag|escape:'html'}/">{$sTag|escape:'html'}</a>{if !$smarty.foreach.tags_list.last}, {/if}</li>
					{/foreach}
				</ul>
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
			</li>
	{/foreach}
{/if}