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
* Вспомогтаельные функции для работы с плагинами
*/

class PluginLib_ModulePlugin extends Module {
	public function Init() { }
	
	/**
	* Проверяет доступность плагина в системе по его имени
	* 
	* @param	string		Название плагина
	* @return	bool		Результат проверки
	*/
	static public function IsPluginAvailable($sPluginName) {
		$oEngine = Engine::getInstance();
		$aPlugin = $oEngine->GetPlugins();
		$bPluginAvailable = isset($aPlugin[strtolower($sPluginName)]);
		
		return $bPluginAvailable;
	}
}

?>