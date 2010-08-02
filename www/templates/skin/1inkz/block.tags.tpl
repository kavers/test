    <li id="about_author" class="block green">
        <div class="title"><a href="#111" class="link"><h1>Теги</h1></a><a href="#" class="close_block"><img src="/img/minus.gif" width="18" height="18" alt="Свернуть блок" title="Свернуть блок"/></a></div>
        <div class="block_content block tags">
        <ul class="descriptions">
           <li class="descr1">
            <ul class="cloud">
                {foreach from=$aTags item=oTag}
                    <li><a class="w{$oTag->getSize()}" rel="tag" href="{router page='tag'}{$oTag->getText()|escape:'html'}/">{$oTag->getText()|escape:'html'}</a></li>	
                {/foreach}					
            </ul>
           </li>
        </ul>
        <div class="block_bottom"></div>
        </div>
     </li>

{*			<div class="block tags">
				<div class="tl"><div class="tr"></div></div>
				<div class="cl"><div class="cr">
					
					<ul class="cloud">						
						{foreach from=$aTags item=oTag}
							<li><a class="w{$oTag->getSize()}" rel="tag" href="{router page='tag'}{$oTag->getText()|escape:'html'}/">{$oTag->getText()|escape:'html'}</a></li>	
						{/foreach}
					</ul>
					
				</div></div>
				<div class="bl"><div class="br"></div></div>
			</div>
*}
