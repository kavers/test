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

class PluginNotification_ActionSettings extends PluginNotification_Inherit_ActionSettings {
	protected function EventTuning() {
		if(isPost('submit_settings_tuning')) {
			$this->oUserCurrent->setSettingsNoticeNewTopicCommented( getRequest('settings_notice_new_topic_commented') ? 1 : 0 );
			$this->oUserCurrent->setSettingsNoticeFriendNews( getRequest('settings_notice_friend_news') ? 1 : 0 );
			$this->oUserCurrent->setSettingsNoticeRequest( getRequest('settings_notice_request') ? 1 : 0 );
		}
		parent::EventTuning();
	}
}
?>