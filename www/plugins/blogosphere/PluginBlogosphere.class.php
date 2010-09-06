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
 * Запрещаем напрямую через браузер обращение к этому файлу.
 */
if (!class_exists('Plugin')) {
	die('Hacking attemp!');
}

class PluginBlogosphere extends Plugin {
	protected $aInherits=array(
		'module'  =>array('ModuleTopic'=>'_ModuleTopic'),
		'mapper'  =>array('ModuleTopic_MapperTopic'=>'_ModuleTopic_MapperTopic')
	);
	
	/**
	 * Активация плагина.
	 */
	public function Activate() {
		return true;
	}
	
	/**
	 * Инициализация плагина
	 */
	public function Init() {
		//Подключаем js необходимые для работы плагина
		$this->Viewer_AppendScript($this->GetTemplateWebPath(__CLASS__) . 'js/blogosphereProto.js');
		$this->Viewer_AppendScript($this->GetTemplateWebPath(__CLASS__) . 'js/blogosphere.js');
	}
	
	/**
	 * Возвращает ассоциативный массив с начальной и конечной датой
	 */
	static public function getTimePeriod() {
		$aTimePeriod = array (
			'timeStart' => time() - Config::Get('plugin.blogosphere.period'),
			'timeEnd' => time()
		);
		return $aTimePeriod;
	}
}
?>