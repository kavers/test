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
	
	public function getSettingsNoticeNewCommentCommented() {
		return $this->_aData['user_settings_notice_new_comment_commented'];
	}
	
	public function getSettingsNoticeFriendNews() {
		return $this->_aData['user_settings_notice_friend_news'];
	}
	
	public function setSettingsNoticeNewTopicCommented($data) {
		return $this->_aData['user_settings_notice_new_topic_commented'] = $data;
	}
	
	public function setSettingsNoticeNewCommentCommented($data) {
		return $this->_aData['user_settings_notice_new_comment_commented'] = $data;
	}
	
	public function setSettingsNoticeFriendNews($data) {
		return $this->_aData['user_settings_notice_friend_news'] = $data;
	}
}
?>