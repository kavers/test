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

class PluginTopicstat_ModuleTopic_EntityTopic extends PluginTopicstat_Inherit_ModuleTopic_EntityTopic {
	public function getCountUniqueRead() {
		return $this->_aData['topic_count_unique_read'];
	}
	
	public function getCountUsersRead() {
		return $this->_aData['topic_count_users_read'];
	}
}
?>