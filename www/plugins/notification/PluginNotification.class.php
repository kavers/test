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

//Не нашёл системных функций на получение имени текущего плагина, а для разрешение путей оно необходимо.
define('PLUGIN_NOTIFICATION_NAME', 'notification');

class PluginNotification extends Plugin {
	protected $aInherits=array(
		'entity' => array('ModuleUser_EntityUser' => '_ModuleUser_EntityUser'),
		'module' => array('ModuleNotify' => '_ModuleNotify',
							'ModuleUser' => '_ModuleUser'),
		'mapper' => array('ModuleNotify_MapperNotify' => '_ModuleNotify_MapperNotify',
							'ModuleUser_MapperUser' => '_ModuleUser_MapperUser'),
		'action' => array('ActionSettings' => '_ActionSettings')
	);
	
	/**
	 * Активация плагина Доступ к Топику.
	 * Создание дополнительной колонки в таблицe _topic в базе.
	 */
	public function Activate() {
		//Дополнительно поле для уведомлений о прокомментированных топиках
		$topicCommentAlreadyInstall=$this->Database_GetConnect()->query('SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
			WHERE COLUMN_NAME="user_settings_notice_new_topic_commented" AND TABLE_SCHEMA="'.Config::Get('db.params.dbname').'"
			AND TABLE_NAME LIKE "'.Config::Get('db.table.prefix').'user";');
		if(!$topicCommentAlreadyInstall) $this->ExportSQL(dirname(__FILE__).'/sql/topic_comment.sql');
		
		//Дополнительно поле для уведомлений о прокомментированных комментариях
		$commentCommentAlreadyInstall=$this->Database_GetConnect()->query('SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
			WHERE COLUMN_NAME="user_settings_notice_new_comment_commented" AND TABLE_SCHEMA="'.Config::Get('db.params.dbname').'"
			AND TABLE_NAME LIKE "'.Config::Get('db.table.prefix').'user";');
		if(!$commentCommentAlreadyInstall) $this->ExportSQL(dirname(__FILE__).'/sql/comment_comment.sql');
		
		//Дополнительно поле для уведомлений о прокомментированных комментариях
		$friendNewsAlreadyInstall=$this->Database_GetConnect()->query('SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
			WHERE COLUMN_NAME="user_settings_notice_friend_news" AND TABLE_SCHEMA="'.Config::Get('db.params.dbname').'"
			AND TABLE_NAME LIKE "'.Config::Get('db.table.prefix').'user";');
		if(!$friendNewsAlreadyInstall) $this->ExportSQL(dirname(__FILE__).'/sql/friend_news.sql');
		
		return true;
	}
	
	/**
	 * Инициализация плагина Дополнительные уведомления
	 */
	public function Init() {
	}
}
?>