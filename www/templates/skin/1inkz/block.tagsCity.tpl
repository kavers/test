{if $aCityList && count($aCityList)>0}
    <li id="about_author" class="block green">
        <div class="title"><a href="#111" class="link"><h1>{$aLang.block_city_tags}</h1></a><a href="#" class="close_block"><img src="/img/minus.gif" width="18" height="18" alt="�������� ����" title="�������� ����"/></a></div>
        <div class="block_content block tags">
        <ul class="descriptions">
           <li class="descr1">
					<ul class="cloud">
						{foreach from=$aCityList item=aCity}
							<li><a class="w{$aCity.size}" rel="tag" href="{router page='people'}city/{$aCity.name|escape:'html'}/" >{$aCity.name|escape:'html'}</a></li>	
						{/foreach}					
					</ul>
           </li>
        </ul>
        <div class="block_bottom"></div>
        </div>
     </li>
{/if}
{*     
        {if $aCityList && count($aCityList)>0}
			<div class="block white tags">
				<div class="tl"><div class="tr"></div></div>
				<div class="cl"><div class="cr">					
					<h1>{$aLang.block_city_tags}</h1>					
					<ul class="cloud">
						{foreach from=$aCityList item=aCity}
							<li><a class="w{$aCity.size}" rel="tag" href="{router page='people'}city/{$aCity.name|escape:'html'}/" >{$aCity.name|escape:'html'}</a></li>	
						{/foreach}					
					</ul>									
				</div></div>
				<div class="bl"><div class="br"></div></div>
			</div>
		{/if}
*}