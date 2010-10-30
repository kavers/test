<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright © 2008 Mzhelskiy Maxim
*
*--------------------------------------------------------
*
*   Official site: www.livestreet.ru
*   Contact e-mail: rus.engine@gmail.com
*
*   GNU General Public License, version 2:
*   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
---------------------------------------------------------
*/

class PluginNotification_ModuleUser_MapperUser extends  PluginNotification_Inherit_ModuleUser_MapperUser {
	public function Update(ModuleUser_EntityUser $oUser) {
		$sql = 'UPDATE '.Config::Get('db.table.user').'
			SET
				user_settings_notice_new_topic = ?,
				user_settings_notice_new_comment = ?,
				user_settings_notice_new_talk = ?,
				user_settings_notice_reply_comment = ?,
				user_settings_notice_new_friend = ?,
				user_settings_notice_new_topic_commented = ?,
				user_settings_notice_friend_news = ?,
				user_settings_notice_request = ?,
				user_settings_notice_new_comment_blogs_subscribe = ?,
				user_settings_notice_new_gift = ?,
				user_settings_notice_frequency = ?,
				user_settings_notice_new_user_blogs_subscribe = ?
			WHERE user_id = ?
		';
		if ($this->oDb->query($sql,
								$oUser->getSettingsNoticeNewTopic(),
								$oUser->getSettingsNoticeNewComment(),
								$oUser->getSettingsNoticeNewTalk(),
								$oUser->getSettingsNoticeReplyComment(),
								$oUser->getSettingsNoticeNewFriend(),
								$oUser->getSettingsNoticeNewTopicCommented(),
								$oUser->getSettingsNoticeFriendNews(),
								$oUser->getSettingsNoticeRequest(),
								$oUser->getSettingsNoticeNewCommentBlogsSubscribe(),
								$oUser->getSettingsNoticeNewGift(),
								$oUser->getSettingsNoticeFrequency(),
								$oUser->getSettingsNoticeNewUserBlogsSubscribe(),
								$oUser->getId()) !== null
		) {
			return parent::Update($oUser);
		}
		return false;
	}
}