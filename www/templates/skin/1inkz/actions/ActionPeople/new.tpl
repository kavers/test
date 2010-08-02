{include file='header.tpl' showWhiteBack=true menu='people'}
<li id="vip_blogs" class="block2 green">
        <div class="title"><a href="" class="link"><h1>{$aLang.user_list_new}</h1></a>
        </div>
        <div class="block_content">
           <ul class="table_list dop">
           {if $aUsersRegister}
                {foreach from=$aUsersRegister item=oUser}
               <li>
                 <a href="{router page='profile'}{$oUser->getLogin()}/"><img src="{$oUser->getProfileAvatarPath(48)}" alt="" /></a>
                 <a href="{router page='profile'}{$oUser->getLogin()}/" class="item_title">{$oUser->getLogin()}</a>
                 <div>{$aLang.user_date_registration}: {date_format date=$oUser->getDateRegister()}</div>
                 <div>{$aLang.user_skill}: {$oUser->getSkill()}</div>
                 <div>{$aLang.user_rating}: {$oUser->getRating()}</div>
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
				
				<h1>{$aLang.user_list_new}</h1>
				
				{if $aUsersRegister}
				<table>
					<thead>
						<tr>
							<td class="user">{$aLang.user}</td>													
							<td class="date">{$aLang.user_date_registration}</td>
							<td class="strength">{$aLang.user_skill}</td>
							<td class="rating">{$aLang.user_rating}</td>
						</tr>
					</thead>
					
					<tbody>
					{foreach from=$aUsersRegister item=oUser}
						<tr>
							<td class="user"><a href="{router page='profile'}{$oUser->getLogin()}/"><img src="{$oUser->getProfileAvatarPath(24)}" alt="" /></a><a href="{router page='profile'}{$oUser->getLogin()}/" class="link">{$oUser->getLogin()}</a></td>														
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
			
			
{include file='footer.tpl'}
*}