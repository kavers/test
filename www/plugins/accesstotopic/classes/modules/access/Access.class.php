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
* Функции для проверки уровня доступа пользователя к топику.
* Так же для создания фильтрующих запросов к БД. Ввиду явно выделенного ADB модуля.
* Ничего подобного в Livestreet нет, поэтому отдельным модулем.
* 
* Список доступа
* 'none' - доступа нет,
* 'read' - может читать топик и комментарии
* 'comment' - может оставлять комментарии
*/

class PluginAccesstotopic_ModuleAccess extends Module {
	//Константы уровня доступа
	const NONE = 'none';
	const READ = 'read';
	const COMMENT = 'comment';
	
	public function Init() { }
	
	/**
	* Получить список доступа к топику.
	* Пока что только права на чтение/комментирование
	* 
	* 
	* @param	oUser		Пользователь/может быть null для анонима
	* @param	oTopic		Топик
	* @return	array		Список доступа
	*/
	static public function GetUserAccessLevel($oUser, $oTopic) {
		if($oUser->isAdministrator()) {
			return array(self::READ, self::COMMENT);
		}
		
		$aAccessList = array();
		//Проверяем, есть ли у пользователя доступ к блогу и топику
		if(!$this->canRead($oTopic, $oUser)) {
			$aAccessList[] = self::NONE;
			return $aAccessList;
		}
		
		$aAccessList[] = self::READ;
		
		//Проверяем возможность комментировать
		if($this->canComment($oTopic, $oUser)) {
			$aAccessList[] = self::COMMENT;
		}
		
		return $aAccessList;
	}
	
	/**
	* Проверить соответсвующий уровень доступа пользователя к топику
	* 
	* @param	oUser			Пользователь/может быть null для анонима
	* @param	oTopic			Топик
	* @param	string			проверяемый уровень доступа
	* @return	boolean|null	Если уровня не существует - null
	*/
	static public function CheckUserAccess($oUser, $oTopic, $sLevel) {
		if($oUser->isAdministrator()) {
			return true;
		}
		
		switch(strtolower($sLevel)) {
			case self::NONE:
				return $this->canNothing($oTopic, $oUser);
			case self::READ:
				return $this->canRead($oTopic, $oUser);
			case self::COMMENT:
				return $this->canComment($oTopic, $oUser);
		}
		
		return null;
	}
	
	/**
	* Дополнительные условия к WHERE для фильтрации топиков согласно уровню доступа пользователя
	* id = 0 - анонимный пользователь. Адмнистратор никак не выделен, для него имеет смыл просто 
	* не вызывать данный метод.
	* 
	* @param	int		ID пользователя, для которого выполняется фильтрация
	* @return	string	Строка для вставки в where. Заключена в скобки. Пробелов слева и права нет.
	*/
	static public function GetAccessWhereStatment($currentUserId = 0) {
		$currentUserId = (int) $currentUserId;
		$statmentPersonal = '
							(t.user_id = '.$currentUserId.')
							OR
							(t.access_level = '.Config::Get('plugin.accesstotopic.personalBlog.accessLevels.FOR_ALL').')
							OR
							(
								(t.access_level = '.Config::Get('plugin.accesstotopic.personalBlog.accessLevels.FOR_OWNER_ONLY').')
								AND
								(t.user_id = '.$currentUserId.')
							)
							OR
							(
								(t.access_level = '.Config::Get('plugin.accesstotopic.personalBlog.accessLevels.FOR_REGISTERED').')
								AND
								('.$currentUserId.' > 0)
							)
							OR
							(
								(t.access_level = '.Config::Get('plugin.accesstotopic.personalBlog.accessLevels.FOR_FRIENDS').')
								AND
								(
								0 < 
									(
										SELECT COUNT(f.user_to) FROM '.Config::Get('db.table.friend').' as f
										WHERE
										(
											t.user_id = f.user_from AND f.user_to = '.$currentUserId.'
											AND 
											(
												f.status_from = '.ModuleUser::USER_FRIEND_OFFER.'
												OR
												f.status_from = '.ModuleUser::USER_FRIEND_ACCEPT.'
											)
										)
										OR 
										(
											t.user_id = f.user_to AND f.user_from = '.$currentUserId.' 
											AND
											(
												f.status_to = '.ModuleUser::USER_FRIEND_OFFER.'
												OR
												f.status_to = '.ModuleUser::USER_FRIEND_ACCEPT.'
											)
										)
									)
								)
							)
							OR
							(
								(t.access_level = '.Config::Get('plugin.accesstotopic.personalBlog.accessLevels.FOR_TWOSIDE_FRIENDS').')
								AND
								(
								0 <
									(
										SELECT COUNT(f.user_from) FROM '.Config::Get('db.table.friend').' as f
										WHERE 
										(
											t.user_id = f.user_from AND f.user_to = '.$currentUserId.' 
											AND 
											(
												(f.status_from + f.status_to) = '. (ModuleUser::USER_FRIEND_OFFER + ModuleUser::USER_FRIEND_ACCEPT) .'
												OR
												(
													f.status_to = '.ModuleUser::USER_FRIEND_ACCEPT.'
													AND
													f.status_from = '.ModuleUser::USER_FRIEND_ACCEPT.'
												)
											)
										)
										OR 
										(
											t.user_id = f.user_to AND f.user_from = '.$currentUserId.' 
											AND 
											(
												(f.status_from + f.status_to) = '. (ModuleUser::USER_FRIEND_OFFER + ModuleUser::USER_FRIEND_ACCEPT) .'
												OR
												(
													f.status_to = '.ModuleUser::USER_FRIEND_ACCEPT.'
													AND
													f.status_from = '.ModuleUser::USER_FRIEND_ACCEPT.'
												)
											)
										)
									)
								)
							)';
		
		$statmentCollective = '
							(t.access_level = '.Config::Get('plugin.accesstotopic.collectiveBlog.accessLevels.FOR_ALL').')
							OR
							(
								(t.access_level = '.Config::Get('plugin.accesstotopic.collectiveBlog.accessLevels.FOR_REGISTERED').')
								AND
								('.$currentUserId.' > 0)
							)
							OR
							(
								(t.access_level = '.Config::Get('plugin.accesstotopic.collectiveBlog.accessLevels.FOR_COLLECTIVE').')
								AND
								(
								0 < 
									(
									SELECT COUNT(bu.user_id) FROM '.Config::Get('db.table.blog_user').' as bu
									WHERE bu.user_id = '.$currentUserId.' AND bu.blog_id = t.blog_id
									)
								)
							)';
		return '(' . $statmentPersonal . ' OR ' . $statmentCollective . ')';
	}
	
	protected function canComment($oTopic, $oUser) {
		return !$oTopic->getForbidComment() && $this->ACL_CanPostComment($oUser) && $this->ACL_CanPostCommentTime($oUser);
	}
	
	protected function canRead($oTopic, $oUser) {
		return !$this->inNotAccessibleBlog($oTopic, $oUser) && !$this->notAccessibleTopic($oTopic, $oUser);
	}
	
	protected function canNothing($oTopic, $oUser) {
		return !$this->canComment($oTopic, $oUser) && !$this->canRead($oTopic, $oUser);
	}
	
	protected function inNotAccessibleBlog($oTopic, $oUser) {
		$aInaccessibleBlog = $this->Blog_GetInaccessibleBlogsByUser($oUser);
		foreach($aInaccessibleBlog as $iBlogId) {
			if($iBlogId == $oTopic->getBlogId()) {
				return true;
			}
		}
		
		return false;
	}
	
	protected function notAccessibleTopic($oTopic, $oUser) {
		//На тот случай если придётся выделять этот модуль из плагина с этой функцией не работаем на прямую.
		return !$this->Topic_CheckAccessToTopic($oUser, $oTopic);
	}
}

?>