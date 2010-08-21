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

class PluginNotification_ModuleBlog_EntityBlogUser extends  PluginNotification_Inherit_ModuleBlog_EntityBlogUser {
	public function getUserSettingsNoticeNewTopicSubscribe() {
		return $this->_aData['user_settings_notice_new_topic_subscribe'];
	}
	
	public function getUserSettingsNoticeNewCommentSubscribe() {
		return $this->_aData['user_settings_notice_new_comment_subscribe'];
	}
	
	public function setUserSettingsNoticeNewTopicSubscribe($data) {
		return $this->_aData['user_settings_notice_new_topic_subscribe'] = $data;
	}
	
	public function setUserSettingsNoticeNewCommentSubscribe($data) {
		return $this->_aData['user_settings_notice_new_comment_subscribe'] = $data;
	}
}

?>