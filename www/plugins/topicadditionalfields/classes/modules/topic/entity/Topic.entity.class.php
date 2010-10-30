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

class PluginTopicadditionalfields_ModuleTopic_EntityTopic extends PluginTopicadditionalfields_Inherit_ModuleTopic_EntityTopic {
	
	public function getCurrentPlace() {
		return $this->_aData['current_place'];
	}
	
	public function getMood() {
		return $this->_aData['mood'];
	}
	
	public function getMoodName() {
		if($sMoodName = array_search($this->getMood(), Config::Get('plugin.topicadditionalfields.topicMood'))) {
			return $sMoodName;
		}

		return '';
	}
	
	public function getNowListening() {
		return $this->_aData['now_listening'];
	}
	
//*************************************************************************************************************************************************
	
	public function setCurrentPlace($data) {
		$this->_aData['current_place']=$data;
	}
	
	public function setMood($data) {
		$this->_aData['mood']=$data;
	}
	
	public function setNowListening($data) {
		$this->_aData['now_listening']=$data;
	}
}
?>