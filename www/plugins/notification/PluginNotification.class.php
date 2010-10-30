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
		'entity' => array('ModuleUser_EntityUser' => '_ModuleUser_EntityUser',
							'ModuleBlog_EntityBlogUser' => '_ModuleBlog_EntityBlogUser',
							'ModuleNotify_EntityTask' => '_ModuleNotify_EntityTask'),
		'module' => array('ModuleNotify' => '_ModuleNotify',
							'ModuleUser' => '_ModuleUser',
							'ModuleTopic'=>'_ModuleTopic',
							'ModuleBlog' => '_ModuleBlog'),
		'mapper' => array('ModuleNotify_MapperNotify' => '_ModuleNotify_MapperNotify',
							'ModuleUser_MapperUser' => '_ModuleUser_MapperUser',
							'ModuleBlog_MapperBlog' => '_ModuleBlog_MapperBlog'),
		'action' => array('ActionSettings' => '_ActionSettings')
	);
	

	/**
	 * Активация плагина Дополнительные уведомления.
	 * Создание дополнительных колонок в таблицах.
	 */
	public function Activate() {
		$aSqlConfig = array(
			array(
				'field' => 'user_settings_notice_new_topic_commented',
				'table' => Config::Get('db.table.user'),
				'file' => dirname(__FILE__) . '/sql/topic_comment.sql'
			),
			array(
				'field' => 'user_settings_notice_friend_news',
				'table' => Config::Get('db.table.user'),
				'file' => dirname(__FILE__) . '/sql/friend_news.sql'
			),
			array(
				'field' => 'user_settings_notice_request',
				'table' => Config::Get('db.table.user'),
				'file' => dirname(__FILE__) . '/sql/request.sql'
			),
			array(
				'field' => 'user_settings_notice_new_topic_subscribe',
				'table' => Config::Get('db.table.blog_user'),
				'file' => dirname(__FILE__) . '/sql/blog_topic_subscriber.sql'
			),
			array(
				'field' => 'user_settings_notice_new_comment_subscribe',
				'table' => Config::Get('db.table.blog_user'), 
				'file' => dirname(__FILE__) . '/sql/blog_comment_subscriber.sql'
			),
			array(
				'field' => 'user_settings_notice_new_comment_blogs_subscribe', 
				'table' => Config::Get('db.table.user'), 
				'file' => dirname(__FILE__) . '/sql/user_blog_comment_subscriber.sql'
			),
			array(
				'field' => 'user_settings_notice_new_gift',
				'table' => Config::Get('db.table.user'),
				'file' => dirname(__FILE__) . '/sql/new_gift.sql'
			),
			array(
				'field' => 'user_settings_notice_frequency',
				'table' => Config::Get('db.table.user'),
				'file' => dirname(__FILE__) . '/sql/notice_frequency.sql'
			),
			array(
				'field' => 'notify_freq_type',
				'table' => Config::Get('db.table.notify_task'),
				'file' => dirname(__FILE__) . '/sql/notify_task_frequency.sql'
			),
			array(
				'field' => 'user_settings_notice_new_user_blogs_subscribe',
				'table' => Config::Get('db.table.user'),
				'file' => dirname(__FILE__) . '/sql/user_blog_new_user_subscriber.sql'
			)
		);
		/*
			Выделять для каждого вида подписки отдельное поле в базе не очень красиво и удобно с 
			точки зрения расширяемости,
			но такой вариант выбрали авторы LiveStreet, так что не выделываюсь и следую ему.
		*/
		$this->exportSQLIfNeed($this->aSqlConfig);
		$this->Cache_Clean();
		return true;
	}
	
	public function Deactivate() {
		$this->Cache_Clean();
		return true;
	}
	
	/**
	 * Инициализация плагина Дополнительные уведомления
	 */
	public function Init() {
		//Подключаем скрипт для отправки запроса "Потыкать палочкой"
		$this->Viewer_AppendScript($this->GetTemplateWebPath(__CLASS__) . 'js/notify.js');
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