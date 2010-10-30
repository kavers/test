{include file='header.tpl' menu='settings' showWhiteBack=true}
<!-- Редактирование записи -->
	<li id="video_player" class="block2 green">
		<div class="title"><a href="{router page='settings'}tuning/" class="link"><h1>{$aLang.settings_tuning}</h1></a></div>
		<div class="block_content">
		<div id="text">
			<form action="{router page='settings'}tuning/" method="POST" enctype="multipart/form-data">
				{hook run='form_settings_tuning_begin'}
				<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />
			<div class="left_text">
				<p>{$aLang.notification_private_notice}</p>
			</div>
			<div class="right_text notice">
				<p>&nbsp;</p>
				<p>
					<label for="settings_notice_new_comment">
						<input {if $oUserCurrent->getSettingsNoticeNewComment()}checked{/if} type="checkbox"   id="settings_notice_new_comment" name="settings_notice_new_comment" value="1" class="checkbox" /> 
						{$aLang.notifications_settings_tuning_notice_new_comment}
					</label>
				</p>
				<p>
					<label for="settings_notice_reply_comment">
						<input {if $oUserCurrent->getSettingsNoticeReplyComment()}checked{/if} type="checkbox" id="settings_notice_reply_comment" name="settings_notice_reply_comment" value="1" class="checkbox" /> 	
						{$aLang.notifications_settings_tuning_notice_reply_comment}
					</label>
				</p>
				{if !$oConfig->GetValue('plugin.notification.unionCommentsNotification')}
				<p>
					<label for="settings_notice_new_topic_commented">
						<input {if $oUserCurrent->getSettingsNoticeNewTopicCommented()}checked{/if}  type="checkbox" id="settings_notice_new_topic_commented" name="settings_notice_new_topic_commented" value="1" class="checkbox" /> 
						{$aLang.notification_settings_topic_comment_new}
					</label>
				</p>
				{/if}
				<p>
					<label for="settings_notice_friend_news">
						<input {if $oUserCurrent->getSettingsNoticeFriendNews()}checked{/if}  type="checkbox" id="settings_notice_friend_news" name="settings_notice_friend_news" value="1" class="checkbox" /> 
						{$aLang.notification_settings_friend_news}
					</label>
				</p>
				<p>
					<label for="settings_notice_new_friend">
						<input {if $oUserCurrent->getSettingsNoticeNewFriend()}checked{/if} type="checkbox" id="settings_notice_new_friend" name="settings_notice_new_friend" value="1" class="checkbox" />
						{$aLang.notifications_settings_tuning_notice_new_friend}
					</label>
				</p>
				<p>
					<label for="settings_notice_new_talk">
						<input {if $oUserCurrent->getSettingsNoticeNewTalk()}checked{/if} type="checkbox" id="settings_notice_new_talk" name="settings_notice_new_talk" value="1" class="checkbox" />
						{$aLang.notifications_settings_tuning_notice_new_talk}
					</label>
				</p>
				<p>
					<label for="settings_notice_new_gift">
						<input {if $oUserCurrent->getSettingsNoticeNewGift()}checked{/if} type="checkbox" id="settings_notice_new_gift" name="settings_notice_new_gift" value="1" class="checkbox" disabled />
						{$aLang.notifications_settings_tuning_notice_new_gift}
					</label>
				</p>
				<p>
					<label for="settings_notice_request">
						<input {if $oUserCurrent->getSettingsNoticeRequest()}checked{/if}  type="checkbox" id="settings_notice_request" name="settings_notice_request" value="1" class="checkbox" /> 
						{$aLang.notification_settings_request}
					</label>
				</p>
			</div>
			<div class="left_text">
				<p>{$aLang.notification_community_notice}</p>
			</div>
			<div class="right_text notice">
				<p>&nbsp;</p>
				<p>
					{$aLang.notification_blogs_notify}
					{if !$oConfig->GetValue('plugin.notification.oneSettingForBlogsComments')}
						<hr />
						{$aLang.notification_blogs_notify_comment}
					{/if}
				</p>
				{* Не знаю как получить доступ к "вложенным" объектам, поэтому php вставка *}
				{php}
					$aSubscribeUserBlog = $this->get_template_vars('aSubscribeUserBlog');
					foreach($aSubscribeUserBlog as $oBlogUser) {
						$sTopicCheckbox = '
							<input' . ($oBlogUser->getUserSettingsNoticeNewTopicSubscribe() ? ' checked' : '') .' type="checkbox"
							id="settings_notice_new_topic_subscribe_'. $oBlogUser->getBlogId() .'"
							name="settings_notice_new_topic_subscribe['. $oBlogUser->getBlogId(). ']" value="1" class="checkbox" />
						';
						$sCommentCheckbox = '
							<input' . ($oBlogUser->getUserSettingsNoticeNewCommentSubscribe() ? ' checked' : '') .' type="checkbox"
							id="settings_notice_new_comment_subscribe_'. $oBlogUser->getBlogId() .'"
							name="settings_notice_new_comment_subscribe['. $oBlogUser->getBlogId(). ']" value="1" class="checkbox" />
						';
						echo('
						<p>
							<label for="settings_notice_new_topic_subscribe">
								'. $sTopicCheckbox .'
								'. (!Config::Get('plugin.notification.oneSettingForBlogsComments') ? $sCommentCheckbox : '') .'
								'. $oBlogUser->getBlog()->getTitle() .'
							</label>
						</p>
						');
					}
				{/php}
				{if $oConfig->GetValue('plugin.notification.oneSettingForBlogsComments')}
				<p>
					<label for="user_settings_notice_new_comment_blogs_subscribe">
						<input {if $oUserCurrent->getSettingsNoticeNewCommentBlogsSubscribe()}checked{/if}  type="checkbox" id="user_settings_notice_new_comment_blogs_subscribe" name="user_settings_notice_new_comment_blogs_subscribe" value="1" class="checkbox" /> 
						{$aLang.notification_settings_blog_topic_comment_new}
					</label>
				</p>
				{/if}
				<p>
					<label for="user_settings_notice_new_user_blogs_subscribe">
						<input {if $oUserCurrent->getSettingsNoticeNewUserBlogsSubscribe()}checked{/if}  type="checkbox" id="user_settings_notice_new_user_blogs_subscribe" name="user_settings_notice_new_user_blogs_subscribe" value="1" class="checkbox" /> 
						{$aLang.notification_settings_blog_user_new}
					</label>
				</p>
			</div>
			<!--<div class="left_text">
				<p>{$aLang.notification_favourite_notice}</p>
			</div>
			<div class="right_text notice">
			</div>-->
			<div class="left_text">
				<p>{$aLang.notification_frequency_notice}</p>
			</div>
			<div class="right_text notice">
				<p>&nbsp;</p>
				<p>
					<label for="user_settings_notice_frequency">
						<input type="radio" name="user_settings_notice_frequency" value="at_once" {if $oUserCurrent->getSettingsNoticeFrequency() == 1}checked{/if}/>
						{$aLang.notification_frequency_at_once}
					</label>
				</p>
				<p>
					<label for="user_settings_notice_frequency">
						<input type="radio" name="user_settings_notice_frequency" value="daily" {if $oUserCurrent->getSettingsNoticeFrequency() == 2}checked{/if}/>
						{$aLang.notification_frequency_daily}
					</label>
				</p>
				<p>
					<label for="user_settings_notice_frequency">
						<input type="radio" name="user_settings_notice_frequency" value="weekly" {if $oUserCurrent->getSettingsNoticeFrequency() == 3}checked{/if}/>
						{$aLang.notification_frequency_weekly}
					</label>
				</p>
			</div>
			<input type="hidden" name="submit_settings_tuning" value="1">
			<p class="cn refill"><input type="image" src="{cfg name='path.static.skin'}/img/save_button.gif" width="212" height="32" alt="{$aLang.settings_tuning_submit}" title="{$aLang.settings_tuning_submit}"/></p>
			{hook run='form_settings_tuning_end'}
			</form>
			</div>
		<div class="block_bottom3"></div>
		</div>
	</li>
{include file='footer.tpl'}