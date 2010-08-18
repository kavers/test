<div class="right_text notice">
	<p>
		<label for="settings_notice_new_topic_commented">
			<input {if $oUserCurrent->getSettingsNoticeNewTopicCommented()}checked{/if}  type="checkbox" id="settings_notice_new_topic_commented" name="settings_notice_new_topic_commented" value="1" class="checkbox" /> 
			{$aLang.notification_settings_topic_comment_new}
		</label>
	</p>
	<p>
		<label for="settings_notice_friend_news">
			<input {if $oUserCurrent->getSettingsNoticeFriendNews()}checked{/if}  type="checkbox" id="settings_notice_friend_news" name="settings_notice_friend_news" value="1" class="checkbox" /> 
			{$aLang.notification_settings_friend_news}
		</label>
	</p>
	<p>
		<label for="settings_notice_request">
			<input {if $oUserCurrent->getSettingsNoticeRequest()}checked{/if}  type="checkbox" id="settings_notice_request" name="settings_notice_request" value="1" class="checkbox" /> 
			{$aLang.notification_settings_request}
		</label>
	</p>
	<p>
		<script language="JavaScript" type="text/javascript">
		{literal}
			function sendRequest(recipientId) {
				JsHttpRequest.query(
					'POST /request',
					{ userId: recipientId, security_ls_key: LIVESTREET_SECURITY_KEY },
					function(result, errors) {
						if (!result) {
							msgErrorBox.alert('Error','Please try again later');
							return;
						}
						msgErrorBox.alert('Result',result.sRequestResultText);
					},
					true
				);
			}
		{/literal}
		</script>
		<input type="button" value="TestRequest" onclick="sendRequest(7);" />
	</p>
</div>