{include file='header.tpl' showWhiteBack=true menu='people'}
<li id="vip_blogs" class="block2 green">
        {*<div class="title"><a href="/" class="link"><h1>{$aLang.user_list} <span>({$aStat.count_all})</span></h1></a>*}
        <div class="title">
            <a href="{router page='people'}" class="link"><h1>{$aLang.user_list}</h1></a>
            <img src="{cfg name='path.static.skin'}/img/arrow.gif" width="7" height="12" alt="arrow" title="arrow" class="arrow"/>
            <a href="#" class="link"><h1>{$oCountry->getName()}</h1></a>
        </div>
        <div class="block_content">
           <ul class="table_list">
           {if $aUsersCountry}
                {foreach from=$aUsersCountry item=oUser}
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
     
     {*
			<div class="page people">
				
				<h1>{$aLang.user_list}: {$oCountry->getName()}</h1>
				
				{if $aUsersCountry}
				<table>
					<thead>
						<tr>
							<td class="user">{$aLang.user}</td>	
							<td class="date">{$aLang.user_date_last}</td>												
							<td class="date">{$aLang.user_date_registration}</td>
							<td class="strength">{$aLang.user_skill}</td>
							<td class="rating">{$aLang.user_rating}</td>
						</tr>
					</thead>
					
					<tbody>
					{foreach from=$aUsersCountry item=oUser}
					{assign var="oSession" value=$oUser->getSession()}
						<tr>
							<td class="user"><a href="{router page='profile'}{$oUser->getLogin()}/"><img src="{$oUser->getProfileAvatarPath(24)}" alt="" /></a><a href="{router page='profile'}{$oUser->getLogin()}/" class="link">{$oUser->getLogin()}</a></td>														
							<td class="date">{if $oSession}{date_format date=$oSession->getDateLast()}{/if}</td>
							<td class="date">{date_format date=$oUser->getDateRegister()}</td>
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
		*}	
			
{include file='footer.tpl'}