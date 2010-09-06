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
class PluginBlogosphere_HookActionTpl extends Hook {

	public function RegisterHook() {
		/**
		* Хук вставки щаблона блогосферы. 
		*/
		$this->AddHook('template_html_pluginBlogosphere', 'InsertBlogosphereTpl');
	}
	
	/**
	* Выводим HTML
	*
	*/
	public function InsertBlogosphereTpl() {
		$aConfig = PluginBlogosphere::getTimePeriod();
		for($iCurTime = $aConfig['timeStart']; $iCurTime < $aConfig['timeEnd']; $iCurTime += Config::Get('plugin.blogosphere.interval')) {
			$aConfig['aTimeStamp'][] = $iCurTime;
		}
		$this->Viewer_Assign('aBlogosphere', $aConfig);
		return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__).'Blogosphere.tpl');
	}
}
?>