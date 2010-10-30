<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright В© 2008 Mzhelskiy Maxim
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

class PluginTopicadditionalfields_ModuleTopic_MapperTopic extends PluginTopicadditionalfields_Inherit_ModuleTopic_MapperTopic {
		
	public function AddTopic(ModuleTopic_EntityTopic $oTopic) {
		if($iId=parent::AddTopic($oTopic)) {
			if($this->updateTopicadditionalfields($oTopic)) {
				return $iId;
			}
		}
		return false;
	}
	
	public function UpdateTopic(ModuleTopic_EntityTopic $oTopic) {
		if($this->updateTopicadditionalfields($oTopic)) {
			return parent::UpdateTopic($oTopic);
		}
		
		return false;
	}
	
	//обновляет поля "now_listening", "currnet_place", "mood"
	protected function updateTopicadditionalfields(ModuleTopic_EntityTopic $oTopic) {
		$sql = 'UPDATE '.Config::Get('db.table.topic').' 
			SET 
				now_listening = ? ,
				current_place = ? ,
				mood = ?
			WHERE
				topic_id = ?d';
		if($this->oDb->query($sql,$oTopic->getNowListening(),$oTopic->getCurrentPlace(),$oTopic->getMood(),$oTopic->getId()) !== null) {
			return true;
		}
		return false;
	}
}
?>