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

class PluginTopicstat_ModuleCounter extends Module {
	//database access layer 
	protected $oMapper;

	public function Init() {
		$this->oMapper = Engine::GetMapper(__CLASS__);
	}
	
	public function UpdateCounter($oTopic) {
		$this->oMapper->UpdateCounter($oTopic);
		$this->Cache_Delete("topic_id_{$oTopic->getId()}");
	}
}
?>