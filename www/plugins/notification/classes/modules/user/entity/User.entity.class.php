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

class PluginNotification_ModuleUser_EntityUser extends PluginNotification_Inherit_ModuleUser_EntityUser {
	public function getSettingsNoticeNewTopicCommented() {
		return $this->_aData['user_settings_notice_new_topic_commented'];
	}
	
	public function getSettingsNoticeFriendNews() {
		return $this->_aData['user_settings_notice_friend_news'];
	}
	
	public function getSettingsNoticeRequest() {
		return $this->_aData['user_settings_notice_request'];
	}
	
	public function getSettingsNoticeNewCommentBlogsSubscribe() {
		return $this->_aData['user_settings_notice_new_comment_blogs_subscribe'];
	}
	
	public function getSettingsNoticeNewGift() {
		return $this->_aData['user_settings_notice_new_gift'];
	}
	
	public function getSettingsNoticeFrequency() {
		return $this->_aData['user_settings_notice_frequency'];
	}
	
	public function getSettingsNoticeNewUserBlogsSubscribe() {
		return $this->_aData['user_settings_notice_new_user_blogs_subscribe'];
	}
	
	public function getSettingsNoticeRequestLast() {
		return $this->_aData['user_settings_notice_request_last'];
	}
	
	public function setSettingsNoticeNewTopicCommented($data) {
		return $this->_aData['user_settings_notice_new_topic_commented'] = $data;
	}
	
	public function setSettingsNoticeFriendNews($data) {
		return $this->_aData['user_settings_notice_friend_news'] = $data;
	}
	
	public function setSettingsNoticeRequest($data) {
		return $this->_aData['user_settings_notice_request'] = $data;
	}
	
	public function setSettingsNoticeNewCommentBlogsSubscribe($data) {
		return $this->_aData['user_settings_notice_new_comment_blogs_subscribe'] = $data;
	}
	
	public function setSettingsNoticeNewGift($data) {
		return $this->_aData['user_settings_notice_new_gift'] = $data;
	}
	
	public function setSettingsNoticeFrequency($data) {
		return $this->_aData['user_settings_notice_frequency'] = $data;
	}
	
	public function setSettingsNoticeNewUserBlogsSubscribe($data) {
		return $this->_aData['user_settings_notice_new_user_blogs_subscribe'] = $data;
	}

}
?>