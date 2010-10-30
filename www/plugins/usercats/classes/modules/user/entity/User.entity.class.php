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

class PluginUsercats_ModuleUser_EntityUser extends PluginUsercats_Inherit_ModuleUser_EntityUser {
	//Получаем полное имя категории (+ все промежуточные контейнеры)
	public function getCategory() {
		return $this->_aData['user_cat'];
	}
	
	//Получаем терминальное имя категории (без промежуточных контейнеров)
	public function getCategoryName() {
		$sFullCatName = $this->getCategory();
		$aCatPath = explode(':', $sFullCatName);
		return $aCatPath[count($aCatPath) - 1];
	}
	
//*************************************************************************************************
	
	public function setCategory($data) {
		$this->_aData['user_cat'] = $data;
	}
}
?>