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

class PluginTopicstat_ModuleCounter_MapperCounter extends Mapper {
	public function UpdateCounter($oTopic) {
		$sql = 'SELECT
					DISTINCT user_id
				FROM 
					'.Config::Get('db.table.topic_read').'
				WHERE
					topic_id = ?d';
		
		$aUserId = $this->oDb->selectCol($sql, $oTopic->getId());
		$oUserCurrent = PluginLib_ModuleUser::GetUserCurrent();
		
		if(in_array($oUserCurrent->getId(), $aUserId)) {
			$iUsersRead = $oTopic->getCountUsersRead() + 1;
		} else {
			$iUsersRead = $oTopic->getCountUsersRead();
		}
		
		$sql = 'UPDATE '.Config::Get('db.table.topic').'
					SET
						topic_count_read = ?d,
						topic_count_unique_read = ?d,
						topic_count_users_read = ?d
					WHERE
						topic_id = ?d
				';
		
		if ($this->oDb->query($sql, $oTopic->getCountRead() + 1, count($aUserId), $iUsersRead, $oTopic->getId())) {
			return true;
		}
		return false;
	}
}
?>
