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

if (!class_exists('Plugin')) {
	die('Hacking attemp!');
}

class PluginTopicadditionalfields extends Plugin {
	protected $aInherits=array(
		'entity'=>array('ModuleTopic_EntityTopic'=>'_ModuleTopic_EntityTopic'),
		'mapper'=>array('ModuleTopic_MapperTopic'=>'_ModuleTopic_MapperTopic')
	);
	
	/**
	 * Активация плагина
	 * Создание дополнительной колонки в таблицe _topic в базе.
	 */
	public function Activate() {
		if(!PluginLib_ModulePlugin::IsDBObjectExist(Config::Get('db.table.topic'), 'now_listening')) {
			$this->ExportSQL(dirname(__FILE__).'/sql.sql');
		}
		$this->Cache_Clean();
		return true;
	}
	
	public function Deactivate() {
		$this->Cache_Clean();
	}
	
	/**
	 * Инициализация плагина
	 */
	public function Init() {
	}
}
?>