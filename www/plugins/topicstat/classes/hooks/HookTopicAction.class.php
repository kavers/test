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
class PluginTopicstat_HookTopicAction extends Hook {

	public function RegisterHook() {
		$this->AddHook('topic_show', 'UpdateCounter');
	}
	
	public function UpdateCounter($data) {
		$this->PluginTopicstat_Counter_UpdateCounter($data['oTopic']);
	}
}
?>