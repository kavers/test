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

/**
* Регистрация хуков
*/
class PluginAccesstotopic_HookTopicAction extends Hook {

	public function RegisterHook() {
		//Хук для добавления данных об уровне доступа в объект
		$this->AddHook('topic_add_before', 'AddAccessLevelToTopic');
		$this->AddHook('topic_edit_before', 'AddAccessLevelToTopic');
		//Хук для добавления данных в форму редактирования топика о текущем уровне доступа
		$this->AddHook('topic_edit_show', 'AddAccessLevelToShowForm');
	}

	public function AddAccessLevelToTopic($data) {
		if(getRequest('access_level', null) !== null) {
			$sAccessLevelName = strtoupper(substr(getRequest('access_level'), 0, 20));
			if($data['oBlog']->getType() == 'personal') {
				$aAccessLevel = PluginAccesstotopic_ModuleAccess::GetPersonalTopicAccessLevels();
				$iAccessLevelNum = $aAccessLevel[$sAccessLevelName] ? 
					$aAccessLevel[$sAccessLevelName] : 
					$aAccessLevel['FOR_ALL'];
			} else {
				$aAccessLevel = PluginAccesstotopic_ModuleAccess::GetCollectiveTopicAccessLevels();
				$iAccessLevelNum = $aAccessLevel[$sAccessLevelName] ? 
					$aAccessLevel[$sAccessLevelName] : 
					$aAccessLevel['FOR_ALL'];
			}
		}
		$data['oTopic']->setAccessLevel($iAccessLevelNum);
	}
	
	public function AddAccessLevelToShowForm($data) {
		if(getRequest('access_level', null) === null) {
			$_REQUEST['access_level'] = $data['oTopic']->getAccessLevelName();
		}
	}
}
?>