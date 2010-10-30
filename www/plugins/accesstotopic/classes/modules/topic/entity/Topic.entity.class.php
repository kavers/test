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

class PluginAccesstotopic_ModuleTopic_EntityTopic extends PluginAccesstotopic_Inherit_ModuleTopic_EntityTopic {
	public function getAccessLevel() {
		return $this->_aData['access_level'];
	}
	
	public function setAccessLevel($data) {
		$this->_aData['access_level']=$data;
	}
	
	public function getAccessLevelName() {
		$sLevelName = array_search($this->getAccessLevel(), PluginAccesstotopic_ModuleAccess::GetPersonalTopicAccessLevels());
		if($sLevelName !== false) {
			return $sLevelName;
		}
		
		$sLevelName = array_search($this->getAccessLevel(), PluginAccesstotopic_ModuleAccess::GetCollectiveTopicAccessLevels());
		return $sLevelName !== false ? $sLevelName : '';
	}
}
?>