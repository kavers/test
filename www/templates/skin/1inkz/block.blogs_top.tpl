<ul class="descriptions list">
    {foreach from=$aBlogs item=oBlog}
    <li class="descr1"><div class="total">{$oBlog->getRating()}</div><a href="{router page='blog'}{$oBlog->getUrl()}/" class="stream-author {if $oBlog->getType()=='close'}close{/if}">{$oBlog->getTitle()|escape:'html'}</a></li>
    {/foreach}
</ul>
{*<ul class="list">
	{foreach from=$aBlogs item=oBlog}
		<li><div class="total">{$oBlog->getRating()}</div><a href="{router page='blog'}{$oBlog->getUrl()}/" class="stream-author {if $oBlog->getType()=='close'}close{/if}">{$oBlog->getTitle()|escape:'html'}</a></li>						
	{/foreach}
</ul>				
*}