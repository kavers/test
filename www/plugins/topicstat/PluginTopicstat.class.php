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

class PluginTopicstat extends Plugin {
	protected $aInherits=array(
		'entity' =>array('ModuleTopic_EntityTopic'=>'_ModuleTopic_EntityTopic'),
	);
	
	
	/**
	 * Активация плагина.
	 */
	public function Activate() {
		$aSqlConf = array(
			array('field' => 'topic_count_unique_read', 'table' => Config::Get('db.table.topic'), 'file' => dirname(__FILE__). '/sql/topic.sql')
		);
		
		$this->exportSqlIfNeed($aSqlConf);
		$this->Cache_Clean();
		return true;
	}
	
	/**
	 * Инициализация плагина
	 */
	public function Init() { }
	
	protected function exportSQLIfNeed($aConf = array()) {
		foreach($aConf as $aSQLConf) {
			if(!PluginLib_ModulePlugin::IsDBObjectExist($aSQLConf['table'], $aSQLConf['field'])) {
				$this->ExportSQL($aSQLConf['file']);
			}
		}
	}
}
?>