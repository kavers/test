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
	
	//Ограничения по доступу к топикам персонального блога
	static protected $aPersonalBlogAccessLevel = array(
		'FOR_ALL' => 0,
		'FOR_REGISTERED' => 1,
		'FOR_FRIENDS' => 2,
		'FOR_TWOSIDE_FRIENDS' => 3,
		'FOR_OWNER_ONLY' => 4
	);
	
	//Ограничения по доступу к топикам коллективного блогу
	static protected $aCollectiveBlogAccessLevel = array(
		'FOR_ALL' => 100,
		'FOR_REGISTERED' => 101,
		'FOR_COLLECTIVE' => 102
	);
	
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
	public function GetUserAccessLevel($oUser, $oTopic) {
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
	public function CheckUserAccess($oUser, $oTopic, $sLevel) {
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
		$sFriendTable = Config::Get('db.table.friend');
		$sBlogUserTable = Config::Get('db.table.blog_user');
		$aPersonalAccessLevel = self::GetPersonalTopicAccessLevels();
		$aCollectiveAccessLevel = self::GetCollectiveTopicAccessLevels();
		$iFullFriendStatus = ModuleUser::USER_FRIEND_OFFER + ModuleUser::USER_FRIEND_ACCEPT;
		$iUserFriendOffer = ModuleUser::USER_FRIEND_OFFER;
		$iUserFriendAccept = ModuleUser::USER_FRIEND_ACCEPT;
		
		$statmentPersonal = "
							(t.user_id = {$currentUserId})
							OR
							(t.access_level = {$aPersonalAccessLevel['FOR_ALL']})
							OR
							(
								(t.access_level = {$aPersonalAccessLevel['FOR_OWNER_ONLY']})
								AND
								(t.user_id = {$currentUserId})
							)
							OR
							(
								(t.access_level = {$aPersonalAccessLevel['FOR_REGISTERED']})
								AND
								({$currentUserId} > 0)
							)
							OR
							(
								(t.access_level = {$aPersonalAccessLevel['FOR_FRIENDS']})
								AND
								(
								0 < 
									(
										SELECT COUNT(f.user_to) FROM {$sFriendTable} as f
										WHERE
										(
											t.user_id = f.user_from AND f.user_to = {$currentUserId}
											AND 
											(
												f.status_from = {$iUserFriendOffer}
												OR
												f.status_from = {$iUserFriendAccept}
											)
										)
										OR 
										(
											t.user_id = f.user_to AND f.user_from = {$currentUserId} 
											AND
											(
												f.status_to = {$iUserFriendOffer}
												OR
												f.status_to = {$iUserFriendAccept}
											)
										)
									)
								)
							)
							OR
							(
								(t.access_level = {$aPersonalAccessLevel['FOR_TWOSIDE_FRIENDS']})
								AND
								(
								0 <
									(
										SELECT COUNT(f.user_from) FROM {$sFriendTable} as f
										WHERE 
										(
											t.user_id = f.user_from AND f.user_to = {$currentUserId} 
											AND 
											(
												(f.status_from + f.status_to) = {$iFullFriendStatus}
												OR
												(
													f.status_to = {$iUserFriendAccept}
													AND
													f.status_from = {$iUserFriendAccept}
												)
											)
										)
										OR 
										(
											t.user_id = f.user_to AND f.user_from = {$currentUserId} 
											AND 
											(
												(f.status_from + f.status_to) = {$iFullFriendStatus}
												OR
												(
													f.status_to = {$iUserFriendAccept}
													AND
													f.status_from = {$iUserFriendAccept}
												)
											)
										)
									)
								)
							)";
		
		$statmentCollective = "
							(t.access_level = {$aCollectiveAccessLevel['FOR_ALL']})
							OR
							(
								(t.access_level = {$aCollectiveAccessLevel['FOR_REGISTERED']})
								AND
								({$currentUserId} > 0)
							)
							OR
							(
								(t.access_level = {$aCollectiveAccessLevel['FOR_COLLECTIVE']})
								AND
								(
								0 < 
									(
									SELECT COUNT(bu.user_id) FROM {$sBlogUserTable} as bu
									WHERE bu.user_id = {$currentUserId} AND bu.blog_id = t.blog_id
									)
								)
							)";
		return '(' . $statmentPersonal . ' OR ' . $statmentCollective . ')';
	}
	
	/**
	* Возвращает массив ограничений доступа для топика персонального блога
	* 
	* @return	array
	*/
	static public function GetPersonalTopicAccessLevels() {
		return self::$aPersonalBlogAccessLevel;
	}
	
	/**
	* Возвращает массив ограничений доступа для топика коллективного блога
	* 
	* @return	array
	*/
	static public function GetCollectiveTopicAccessLevels() {
		return self::$aCollectiveBlogAccessLevel;
	}
	
	/**
	* Проверяем, есть ли у пользователя доступ к топику
	* 
	* @param	object		Проверяемый пользователь
	* @param	object		Проверяемый топик
	* @return	boolean		Решение. true, если доступ есть.
	*/
	public function CheckAccessToTopic($oUser, $oTopic) {
		//Пользователь всегда имеет доступ к собственным топикам
		if($oUser->getId() == $oTopic->getUserId()) {
			return true;
		}
		
		
		switch($oTopic->getAccessLevel()) {
			case self::$aPersonalBlogAccessLevel['FOR_ALL']:
			case self::$aCollectiveBlogAccessLevel['FOR_ALL']:
				return true;
			case self::$aPersonalBlogAccessLevel['FOR_REGISTERED']:
			case self::$aCollectiveBlogAccessLevel['FOR_REGISTERED']:
				if(!$oUser->isAnonim()) {
					return true;
				}
				break;
			case self::$aPersonalBlogAccessLevel['FOR_FRIENDS']:
				if($oFriend = $this->User_GetFriend($oTopic->getUserId(), $oUser->getId())) {
					if($oFriend->getStatusByUserId($oTopic->getUserId()) < ModuleUser::USER_FRIEND_OFFER + ModuleUser::USER_FRIEND_ACCEPT && $oFriend->getStatusByUserId($oTopic->getUserId()) > 0) {
						return true;
					}
				}
				break;
			case self::$aPersonalBlogAccessLevel['FOR_TWOSIDE_FRIENDS']:
				if($oFriend = $this->User_GetFriend($oTopic->getUserId(), $oUser->getId())) {
					if($oFriend->getFriendStatus() == ModuleUser::USER_FRIEND_OFFER + ModuleUser::USER_FRIEND_ACCEPT 
						||
						$oFriend->getFriendStatus() == ModuleUser::USER_FRIEND_ACCEPT + ModuleUser::USER_FRIEND_ACCEPT 
						) 
					{
						return true;
					}
				}
				break;
			case self::$aPersonalBlogAccessLevel['FOR_OWNER_ONLY']:
				//Никаких проверок не происходит, так как такие топики мы уже учли в начале метода
				return false;
			case self::$aCollectiveBlogAccessLevel['FOR_COLLECTIVE']:
				$aBlogUsers = $this->Blog_GetBlogUsersByBlogId($oTopic->getBlogId());
				foreach($aBlogUsers as $oBlogUser) {
					if($oBlogUser->getUserId() == $oUser->getId()) {
						return true;
					}
				}
				break;
		}
		return false;
	}
	
	protected function canComment($oTopic, $oUser) {
		return !$oTopic->getForbidComment() && $this->ACL_CanPostComment($oUser) && $this->ACL_CanPostCommentTime($oUser);
	}
	
	protected function canRead($oTopic, $oUser) {
		return !$this->inNotAccessibleBlog($oTopic, $oUser) && !$this->CheckAccessToTopic($oTopic, $oUser);
	}
	
	protected function canNothing($oTopic, $oUser) {
		return !$this->canComment($oTopic, $oUser) && !$this->canRead($oTopic, $oUser);
	}
	
	protected function inNotAccessibleBlog($oTopic, $oUser) {
		$aInaccessibleBlog = $this->Blog_GetInaccessibleBlogsByUser($oUser);
		if(in_array($oTopic->getBlogId(), $aInaccessibleBlog)) {
			return true;
		}
		
		return false;
	}
}

?>