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

class PluginAccesstotopic extends Plugin {
	protected $aInherits=array(
		'entity'  =>array('ModuleTopic_EntityTopic'=>'_ModuleTopic_EntityTopic'),
		'mapper'  =>array('ModuleTopic_MapperTopic'=>'_ModuleTopic_MapperTopic'),
		'module'  =>array('ModuleTopic'=>'_ModuleTopic')
	);
	
	/**
	 * Активация плагина Доступ к Топику.
	 * Создание дополнительной колонки в таблицe _topic в базе.
	 */
	public function Activate() {
		if(!PluginLib_ModulePlugin::IsDBObjectExist(Config::Get('db.table.topic'), 'access_level')) {
			$this->ExportSQL(dirname(__FILE__).'/sql.sql');
		}
		$this->Cache_Clean();
		return true;
	}
	
	public function Deactivate() {
		$this->Cache_Clean();
		return true;
	}
	
	/**
	 * Инициализация плагина Доступ к Топику
	 */
	public function Init() {
		$this->Viewer_AppendScript($this->GetTemplateWebPath(__CLASS__) . 'js/accesstotopic.js');
	}
}
?>