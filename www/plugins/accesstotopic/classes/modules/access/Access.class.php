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