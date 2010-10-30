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

class PluginUsercats extends Plugin {
	protected $aInherits=array(
		'action' => array('ActionPeople'=>'_ActionPeople'),
		'entity'=>array('ModuleUser_EntityUser'=>'_ModuleUser_EntityUser'),
		'mapper'=>array('ModuleUser_MapperUser'=>'_ModuleUser_MapperUser')
	);
	
	/**
	 * Активация плагина
	 * Создание дополнительной колонки в таблицe _topic в базе.
	 */
	public function Activate() {
		if(!PluginLib_ModulePlugin::IsDBObjectExist(Config::Get('db.table.user'), 'user_cat')) {
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
	 * Инициализация плагина
	 */
	public function Init() {
		//Подключаем js необходимые для работы плагина
		$this->Viewer_AppendScript($this->GetTemplateWebPath(__CLASS__) . 'js/usercats.js');
	}
}
?>