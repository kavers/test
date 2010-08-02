{if count($aTopics)>0}	
	{foreach from=$aTopics item=aTopic}
				<div class="comments padding-none">
					<div class="comment">						
						<div class="comment-topic"><a href="{$aTopic.getUrl}">{$aTopic.getTitle|escape:'html'}</a> / <a href="{$aTopic.getBlogUrlFull}" class="comment-blog">{$aTopic.getBlogTitle|escape:'html'}</a> <a href="{$aTopic.getUrl}#comments" class="comment-total">{$aTopic.getCountComment}</a></div>				
						<div class="voting {if $aTopic.getRating>0}positive{elseif $aTopic.getRating<0}negative{/if}">
							<div class="total">{if $aTopic.getRating>0}+{/if}{$aTopic.getRating}</div>
						</div>										
						<div class="content">
							<div class="tb"><div class="tl"><div class="tr"></div></div></div>							
							<div class="text">
					       {$aTopic.getTextShort}
							</div>			
							<div class="bl"><div class="bb"><div class="br"></div></div></div>
						</div>						
						<div class="info">
							<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$aTopic.getUserLogin}/"><img src="{$aTopic.getUserProfileAvatarPath}" alt="avatar" class="avatar" /></a>
							<p><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$aTopic.getUserLogin}/" class="author">{$aTopic.getUserLogin}</a></p>
							<ul>
								<li class="date">{date_format date=$aTopic.getDate}</li>								
								<li>
									&rarr;&nbsp;<a href="{$aTopic.getUrl}">{$aTopic.getUrl}</a>
								</li>								
							</ul>
						</div>
					</div>
				</div>
	{/foreach}	
{/if}
	
{include file='paging.tpl' aPaging=`$aPaging`}