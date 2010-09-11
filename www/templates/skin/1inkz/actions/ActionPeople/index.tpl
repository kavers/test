{include file='header.tpl' showWhiteBack=true menu='people'}
<li id="vip_blogs" class="block2 pine_green">
        {*<div class="title"><a href="/" class="link"><h1>{$aLang.user_list} <span>({$aStat.count_all})</span></h1></a>*}
        <div class="title">
            <a href="/" class="link"><h1>Блоги</h1></a>
            <img src="{cfg name='path.static.skin'}/img/arrow.gif" width="7" height="12" alt="arrow" title="arrow" class="arrow"/>
            <a href="{router page='people'}" class="link"><h1>Личные блоги <span>({$aStat.count_all})</span></h1></a>
        </div>
         <ul class="gradient">
            <li {if $sEvent=='good'}class="first2"{/if}><strong></strong><a href="{router page='people'}good/">{$aLang.user_good}</a></li>
            <li {if $sEvent=='bad'}class="first2"{/if}><a href="{router page='people'}bad/">{$aLang.user_bad}</a></li>
        </ul>
        <div class="block_content">
           <ul class="table_list">
           {if $aUsersRating}
                {foreach from=$aUsersRating item=oUser}
               <li>
                 <a href="{router page='profile'}{$oUser->getLogin()}/"><img src="{$oUser->getProfileAvatarPath(48)}" alt="" /></a>
                 <a href="{router page='profile'}{$oUser->getLogin()}/" style="color:#767676;font-weight:bold;">{$oUser->getLogin()}</a>
                 <br />
                 <a style="color:#E96800" href="{router page='my'}{$oUser->getLogin()}/">{$aLang.user_menu_publication}</a> 
                 {*
                 <div>{$aLang.user_skill}: {$oUser->getSkill()}</div>
                 <div>{$aLang.user_rating}: {$oUser->getRating()}</div>
                 *}
              </li>
         {/foreach}
         {else}
            <li> {$aLang.user_empty}</li>
        {/if}
           </ul>
           
           {include file='paging.tpl' aPaging=`$aPaging`}

        <div class="block_bottom3"></div>
        </div>
     </li>
{include file='footer.tpl'}     
{*
{include file='header.tpl' showWhiteBack=true menu='people'}

			<div class="page people">
				
				<h1>{$aLang.user_list} <span>({$aStat.count_all})</span></h1>

				
				<ul class="block-nav">
					<li {if $sEvent=='good'}class="active"{/if}><strong></strong><a href="{router page='people'}good/">{$aLang.user_good}</a></li>
					<li {if $sEvent=='bad'}class="active"{/if}><a href="{router page='people'}bad/">{$aLang.user_bad}</a><em></em></li>
				</ul>
				
				{if $aUsersRating}
				<table>
					<thead>
						<tr>
							<td class="user">{$aLang.user}</td>													
							<td class="strength">{$aLang.user_skill}</td>
							<td class="rating">{$aLang.user_rating}</td>
						</tr>
					</thead>
					
					<tbody>
					{foreach from=$aUsersRating item=oUser}
						<tr>
							<td class="user"><a href="{router page='profile'}{$oUser->getLogin()}/"><img src="{$oUser->getProfileAvatarPath(24)}" alt="" /></a><a href="{router page='profile'}{$oUser->getLogin()}/" class="link">{$oUser->getLogin()}</a></td>														
							<td class="strength">{$oUser->getSkill()}</td>
							<td class="rating"><strong>{$oUser->getRating()}</strong></td>
						</tr>
					{/foreach}						
					</tbody>
				</table>
				{else}
					{$aLang.user_empty}	
				{/if}
			</div>

			{include file='paging.tpl' aPaging=`$aPaging`}
			
			
{include file='footer.tpl'}
*}
