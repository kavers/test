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
		$alreadyInstall=$this->Database_GetConnect()->query('SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
			WHERE COLUMN_NAME="access_level" AND TABLE_SCHEMA="'.Config::Get('db.params.dbname').'"
			AND TABLE_NAME = "'.Config::Get('db.table.prefix').'topic";
');

		if(!$alreadyInstall) $this->ExportSQL(dirname(__FILE__).'/sql.sql');
		return true;
	}
	
	/**
	 * Инициализация плагина Доступ к Топику
	 */
	public function Init() {
	}
}
?>