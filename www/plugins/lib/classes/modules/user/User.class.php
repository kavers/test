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
* Вспомогтаельные функции для работы с пользователями
*/

class PluginLib_ModuleUser extends Module {
	//Константы анонимного пользователя
	const ANONIM_USER_ID = 0;
	
	public function Init() { }
	
	/**
	* Возвращает текущего пользователя. Если пользователь "аноним", то создаёт для него объект
	* с id = 0 и возвращает его.
	* 
	* @return	oUser		Результат проверки
	*/
	static public function GetUserCurrent() {
		$oEngine = Engine::getInstance();
		list($oModuleUser, $sModuleName, $sMethod) = $oEngine->GetModule('User_IsAuthorization');

		//Проверяем является находистя ли пользователь в системе
		if(!$oModuleUser->IsAuthorization()) {
			//Создаём анонима
			$oUserCurrent = Engine::GetEntity('User_User', array('user_id' => self::ANONIM_USER_ID, 'user_is_administrator' => false));
		} else {
			$oUserCurrent = $oModuleUser->GetUserCurrent();
		}
		
		return $oUserCurrent;
	}
}

?>