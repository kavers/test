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
class PluginTopicstat_HookTopicTpl extends Hook {

	public function RegisterHook() {
		/**
		* Хук для вставки HTML кода
		*/
		$this->AddHook('template_html_pluginTopicstat', 'AddTopicstatBlock');
	}

	/**
	* Выводим HTML
	*
	*/
	public function AddTopicstatBlock() {
		return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__).'topicstat.tpl');
	}
}
?>