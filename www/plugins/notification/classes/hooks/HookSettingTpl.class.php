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
class PluginNotification_HookSettingTpl extends Hook {

	public function RegisterHook() {
		/**
		* Хук для вставки HTML кода
		*/
		$this->AddHook('template_form_settings_tuning_end', 'AddAdditionCheckBoxForNotification');
	}

	/**
	* Выводим HTML
	*/
	public function AddAdditionCheckBoxForNotification() {
	//	if(strtolower(Router::GetActionEvent()) == 'tuning' ) {
	//		return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__).'notification_checkbox.tpl');
	//	}
		
		return '';
	}
}
?>