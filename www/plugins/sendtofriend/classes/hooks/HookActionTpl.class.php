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
class PluginSendtofriend_HookActionTpl extends Hook {

	public function RegisterHook() {
		/**
		* Хук вставки шаблона формы отправки письма. 
		*/
		$this->AddHook('template_html_pluginSendtofriend', 'InsertSendtofriendTpl');
	}
	
	/**
	* Выводим HTML
	*
	*/
	public function InsertSendtofriendTpl() {
		if($this->User_IsAuthorization()) {
			return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__).'sendtofriend.tpl');
		}
		
		return '';
	}
}
?>