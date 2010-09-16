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

class PluginLib extends Plugin {
	protected $aInherits=array(
		'entity'  =>array('ModuleUser_EntityUser' => '_ModuleUser_EntityUser'),
	);
	
	/**
	 * Активация плагина Доступ к Топику.
	 * Создание дополнительной колонки в таблицe _topic в базе.
	 */
	public function Activate() {
		return true;
	}
	
	/**
	 * Инициализация плагина Доступ к Топику
	 */
	public function Init() { }
}
?>