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

class PluginTopicfix_ModuleTopic_MapperTopic extends PluginTopicfix_Inherit_ModuleTopic_MapperTopic {
		
	public function AddTopic(ModuleTopic_EntityTopic $oTopic) {
		$iId=parent::AddTopic($oTopic);
		if($this->updateTopicFixedStatus($oTopic)) {
			return $iId;
		} else {
			return false;
		}
	}
	
	public function UpdateTopic(ModuleTopic_EntityTopic $oTopic) {
		if($this->updateTopicFixedStatus($oTopic)) {
			return parent::UpdateTopic($oTopic);
		} else {
			return false;
		}
	}
	
	//обновляем поле отвечающее за фиксацию топика в БД
	protected function updateTopicFixedStatus(ModuleTopic_EntityTopic $oTopic) {
		$sql = 'UPDATE '.Config::Get('db.table.topic').' 
			SET 
				topic_fixed = ?
			WHERE
				topic_id = ?d';
		if($this->oDb->query($sql,$oTopic->getFixedStatus(),$oTopic->getId()) !== null) {
			return true;
		}
		return false;
	}
}
?>