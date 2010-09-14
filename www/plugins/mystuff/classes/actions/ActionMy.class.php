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


class PluginMystuff_ActionMy extends PluginMystuff_Inherit_ActionMy {
	//Для организации путей вида /my/username/friends
	protected function RegisterEvent() {
		parent::RegisterEvent();
		$this->AddEventPreg('/^[\w\-\_]+$/i','/^friends$/i','EventMyStuff');
		$this->AddEventPreg('/^[\w\-\_]+$/i','/^friends$/i','/^new$/i','EventMyStuff');
	}
	
	//Проверка пользователя и вызов соответсвующего action mystuff
	protected function EventMyStuff() {
		if(!$this->User_IsAuthorization()) {
			return parent::EventNotFound();
		}
		
		$sUserLogin = $this->sCurrentEvent;
		$oUserCurrent = $this->User_GetUserCurrent();
		if($oUserCurrent->getLogin() != $sUserLogin) {
			return parent::EventNotFound();
		}
		
		if( strtolower($this->GetParam(1, 'noth')) == 'new') {
			return Router::Action('mine', 'new');
		}
		
		return Router::Action('mine', 'index');
	}
}

?>