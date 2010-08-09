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
		/**
		* Хук для добавления данных об уровне доступа в объект
		*/
		$this->AddHook('topic_add_before', 'AddAccessLevelToTopic');
	}

	public function AddAccessLevelToTopic($data) {
		$accessLevelName = strtoupper(substr(getRequest('access_level'), 0, 20));
		if($data['oBlog']->getType() != 'personal') {
			$accessLevelNum = Config::Get('plugin.accesstotopic.collectiveBlog.accessLevels.'. $accessLevelName) ? 
				Config::Get('plugin.accesstotopic.collectiveBlog.accessLevels.'. $accessLevelName) : 
				Config::Get('plugin.accesstotopic.collectiveBlog.accessLevels.FOR_ALL');
		} else {
			$accessLevelNum = Config::Get('plugin.accesstotopic.personalBlog.accessLevels.'. $accessLevelName) ?
				Config::Get('plugin.accesstotopic.personalBlog.accessLevels.'. $accessLevelName) :
				Config::Get('plugin.accesstotopic.personalBlog.accessLevels.FOR_ALL');
		}
		$data['oTopic']->setAccessLevel($accessLevelNum);
	}
}
?>