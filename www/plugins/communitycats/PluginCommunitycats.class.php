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

class PluginCommunitycats extends Plugin {
	protected $aInherits=array(
		'action' => array('ActionBlogs'=>'_ActionBlogs'),
		'entity'=>array('ModuleBlog_EntityBlog'=>'_ModuleBlog_EntityBlog'),
		'mapper'=>array('ModuleBlog_MapperBlog'=>'_ModuleBlog_MapperBlog')
	);
	
	/**
	 * Активация плагина
	 * Создание дополнительной колонки в таблицe _topic в базе.
	 */
	public function Activate() {
		if(!PluginLib_ModulePlugin::IsDBObjectExist(Config::Get('db.table.blog'), 'blog_cat')) {
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
		$this->Viewer_AppendScript($this->GetTemplateWebPath(__CLASS__) . 'js/communitycats.js');
	}
}
?>