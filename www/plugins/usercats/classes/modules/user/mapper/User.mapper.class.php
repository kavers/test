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

class PluginUsercats_ModuleUser_MapperUser extends PluginUsercats_Inherit_ModuleUser_MapperUser {
	public function Add(ModuleUser_EntityUser $oUser) {
		if($iId = parent::Add($oUser)) {
			$oUser->setId($iId);
			if($this->updateUserCat($oUser)) {
				return $iId;
			}
		}
		
		return false;
	}
	
	public function Update(ModuleUser_EntityUser $oUser) {
		if($this->updateUserCat($oUser)) {
			return parent::Update($oUser);
		}
		
		return false;
	}
	
	protected function updateUserCat(ModuleUser_EntityUser $oUser) {
		$sql = 'UPDATE '.Config::Get('db.table.user').' 
			SET 
				user_cat = ?
			WHERE
				user_id = ?d';
		
		if($this->oDb->query($sql,$oUser->getCategory(),$oUser->getId()) !== null) {
			return true;
		}
		
		return false;
	}
}
?>