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

class PluginAvatars extends Plugin {
	protected $aInherits=array(
		'action' => array('ActionSettings' => '_ActionSettings'),
	);
	

	/**
	 * Активация плагина.
	 * Создание таблицы.
	 */
	public function Activate() {
		$aSqlConfig = array(
			array(
				'table' => Config::Get('db.table.prefix') . 'user_avatar',
				'file' => dirname(__FILE__) . '/sql/user_avatar.sql'
			),
		);
		$this->exportSQLIfNeed($this->aSqlConfig);
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
		//Подключаем скрипт для отправки запроса "Потыкать палочкой"
		//$this->Viewer_AppendScript($this->GetTemplateWebPath(__CLASS__) . 'js/notify.js');
	}
	
	protected function exportSQLIfNeed($aConf = array()) {
		foreach($aConf as $aSQLConf) {
			if(!PluginLib_ModulePlugin::IsDBObjectExist($aSQLConf['table'], $aSQLConf['field'])) {
				$this->ExportSQL($aSQLConf['file']);
			}
		}
	}
}
?>