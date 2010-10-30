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
class PluginAccesstotopic_HookTopicTpl extends Hook {

	public function RegisterHook() {
		/**
		* Хук для вставки HTML кода
		*/
		$this->AddHook('template_html_pluginAccesstotopic', 'AddSelectToChangeAccessLevel');
	}

	/**
	* Выводим HTML
	*
	*/
	public function AddSelectToChangeAccessLevel() {
		$this->Viewer_Assign('personalAccessLevels', PluginAccesstotopic_ModuleAccess::GetPersonalTopicAccessLevels());
		$this->Viewer_Assign('collectiveAccessLevels', PluginAccesstotopic_ModuleAccess::GetCollectiveTopicAccessLevels());
		return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__).'SelectToChangeAccessLevel.tpl');
	}
}
?>