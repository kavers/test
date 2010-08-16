<div class="right_text notice">
	<p>
		<label for="settings_notice_new_topic_commented">
			<input {if $oUserCurrent->getSettingsNoticeNewTopicCommented()}checked{/if}  type="checkbox" id="settings_notice_new_topic_commented" name="settings_notice_new_topic_commented" value="1" class="checkbox" /> 
			{$aLang.notification_settings_topic_comment_new}
		</label>
	</p>
	<p>
		<label for="settings_notice_new_comment_commented">
			<input {if $oUserCurrent->getSettingsNoticeNewCommentCommented()}checked{/if}  type="checkbox" id="settings_notice_new_comment_commented" name="settings_notice_new_comment_commented" value="1" class="checkbox" /> 
			{$aLang.notification_settings_comment_comment_new}
		</label>
	</p>
	<p>
		<label for="settings_notice_friend_news">
			<input {if $oUserCurrent->getSettingsNoticeFriendNews()}checked{/if}  type="checkbox" id="settings_notice_friend_news" name="settings_notice_friend_news" value="1" class="checkbox" /> 
			{$aLang.notification_settings_friend_news}
		</label>
	</p>
</div>