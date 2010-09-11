			{if $oUserCurrent && $oUserCurrent->getId()!=$oUserProfile->getId()}
     <li class="block orange">
        <div class="title"><a href="#" class="link"><h1>Действия</h1></a></div>
        <div class="block_content block blogs">
            <div class="block-content">
                <ul class="descriptions list">
                    {include file='actions/ActionProfile/friend_item.tpl' oUserFriend=$oUserProfile->getUserFriend()}
                    <li class="descr1"><a href="{router page='talk'}add/?talk_users={$oUserProfile->getLogin()}">{$aLang.user_write_prvmsg}</a></li>
                </ul>
            </div>
            <div class="block_bottom"></div>
        </div>
     </li>
			{/if}
            
     {if $oUserProfile->getProfileIcq() || $oUserProfile->getProfileFoto()}
     <li class="block orange">
        <div class="title"><a href="#" class="link"><h1>{*$aLang.profile_social_contacts*}Контакты</h1></a></div>
        <div class="block_content block blogs">
            <div class="block-content">
                <ul class="descriptions list">
					{if $oUserProfile->getProfileIcq()}
                    <li class="descr1 icq"><a href="http://www.icq.com/people/about_me.php?uin={$oUserProfile->getProfileIcq()|escape:'html'}" target="_blank">{$oUserProfile->getProfileIcq()}</a></li>
					{/if}	
                    {if $oUserProfile->getProfileFoto()}
				    <li class="descr1"><img src="{$oUserProfile->getProfileFoto()}" alt="photo" /></li>
                    {/if}
                </ul>
            </div>
            <div class="block_bottom"></div>
        </div>
     </li>			
     {/if}
