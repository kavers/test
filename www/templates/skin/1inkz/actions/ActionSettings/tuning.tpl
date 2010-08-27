{include file='header.tpl' menu='settings' showWhiteBack=true}
<!-- Редактирование записи -->
     <li id="video_player" class="block2 green">
        <div class="title"><a href="#111" class="link"><h1>{$aLang.settings_tuning}</h1></a></div>
        <div class="block_content">
          <div id="text">
			<form action="{router page='settings'}tuning/" method="POST" enctype="multipart/form-data">
				{*hook run='form_settings_tuning_begin'*}
                <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />
             <div class="left_text">
                <p>{$aLang.settings_tuning_notice}</p>
             </div>
             <div class="right_text notice">
               <p>&nbsp;</p>
				<p><label for="settings_notice_new_topic"><input {if $oUserCurrent->getSettingsNoticeNewTopic()}checked{/if}  type="checkbox" id="settings_notice_new_topic" name="settings_notice_new_topic" value="1" class="checkbox" /> {$aLang.settings_tuning_notice_new_topic}</label></p>
				<p><label for="settings_notice_new_comment"><input {if $oUserCurrent->getSettingsNoticeNewComment()}checked{/if} type="checkbox"   id="settings_notice_new_comment" name="settings_notice_new_comment" value="1" class="checkbox" /> {$aLang.settings_tuning_notice_new_comment}</label></p>
				<p><label for="settings_notice_new_talk"><input {if $oUserCurrent->getSettingsNoticeNewTalk()}checked{/if} type="checkbox" id="settings_notice_new_talk" name="settings_notice_new_talk" value="1" class="checkbox" /> {$aLang.settings_tuning_notice_new_talk}</label></p>
				<p><label for="settings_notice_reply_comment"><input {if $oUserCurrent->getSettingsNoticeReplyComment()}checked{/if} type="checkbox" id="settings_notice_reply_comment" name="settings_notice_reply_comment" value="1" class="checkbox" /> {$aLang.settings_tuning_notice_reply_comment}</label></p>
				<p><label for="settings_notice_new_friend"><input {if $oUserCurrent->getSettingsNoticeNewFriend()}checked{/if} type="checkbox" id="settings_notice_new_friend" name="settings_notice_new_friend" value="1" class="checkbox" /> {$aLang.settings_tuning_notice_new_friend}</label></p>
             </div>
             <input type="hidden" name="submit_settings_tuning" value="1">
             <p class="cn refill"><input type="image" src="{cfg name='path.static.skin'}/img/save_button.gif" width="212" height="32" alt="{$aLang.settings_tuning_submit}" title="{$aLang.settings_tuning_submit}"/></p>
             {*hook run='form_settings_tuning_end'*}
             </form>
           </div>
        <div class="block_bottom3"></div>
        </div>
     </li>
<!-- Редактирование записи -->
{*
			<h1>{$aLang.settings_tuning}</h1>
			<strong>{$aLang.settings_tuning_notice}</strong>
			<form action="{router page='settings'}tuning/" method="POST" enctype="multipart/form-data">
				{hook run='form_settings_tuning_begin'}
				<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" /> 
				<p>
					<label for="settings_notice_new_topic"><input {if $oUserCurrent->getSettingsNoticeNewTopic()}checked{/if}  type="checkbox" id="settings_notice_new_topic" name="settings_notice_new_topic" value="1" class="checkbox" /> &mdash; {$aLang.settings_tuning_notice_new_topic}</label><br />
					<label for="settings_notice_new_comment"><input {if $oUserCurrent->getSettingsNoticeNewComment()}checked{/if} type="checkbox"   id="settings_notice_new_comment" name="settings_notice_new_comment" value="1" class="checkbox" /> &mdash; {$aLang.settings_tuning_notice_new_comment}</label><br />
					<label for="settings_notice_new_talk"><input {if $oUserCurrent->getSettingsNoticeNewTalk()}checked{/if} type="checkbox" id="settings_notice_new_talk" name="settings_notice_new_talk" value="1" class="checkbox" /> &mdash; {$aLang.settings_tuning_notice_new_talk}</label><br />
					<label for="settings_notice_reply_comment"><input {if $oUserCurrent->getSettingsNoticeReplyComment()}checked{/if} type="checkbox" id="settings_notice_reply_comment" name="settings_notice_reply_comment" value="1" class="checkbox" /> &mdash; {$aLang.settings_tuning_notice_reply_comment}</label><br />
					<label for="settings_notice_new_friend"><input {if $oUserCurrent->getSettingsNoticeNewFriend()}checked{/if} type="checkbox" id="settings_notice_new_friend" name="settings_notice_new_friend" value="1" class="checkbox" /> &mdash; {$aLang.settings_tuning_notice_new_friend}</label>
				</p>
				{hook run='form_settings_tuning_end'}
				<p><input type="submit" name="submit_settings_tuning" value="{$aLang.settings_tuning_submit}" /></p>				
			</form>
*}
{include file='footer.tpl'}