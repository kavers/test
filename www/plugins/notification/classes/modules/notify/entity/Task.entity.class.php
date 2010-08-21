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

class PluginNotification_ModuleNotify_EntityTask extends PluginNotification_Inherit_ModuleNotify_EntityTask {
	public function getNotifyFreqType() {
		return $this->_aData['notify_freq_type'];
	}
	
	public function setNotifyFreqType($data) {
		return $this->_aData['notify_freq_type'] = $data;
	}
}

?>