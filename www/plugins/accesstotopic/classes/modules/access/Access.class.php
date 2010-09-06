<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright � 2008 Mzhelskiy Maxim
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
* ������� ��� �������� ������ ������� ������������ � ������.
* ��� �� ��� �������� ����������� �������� � ��. ����� ���� ����������� ADB ������.
* ������ ��������� � Livestreet ���, ������� ��������� �������.
* 
* ������ �������
* 'none' - ������� ���,
* 'read' - ����� ������ ����� � �����������
* 'comment' - ����� ��������� �����������
*/

class PluginAccesstotopic_ModuleAccess extends Module {
	//��������� ������ �������
	const NONE = 'none';
	const READ = 'read';
	const COMMENT = 'comment';
	
	public function Init() { }
	
	/**
	* �������� ������ ������� � ������.
	* ���� ��� ������ ����� �� ������/���������������
	* 
	* 
	* @param	oUser		������������/����� ���� null ��� �������
	* @param	oTopic		�����
	* @return	array		������ �������
	*/
	static public function GetUserAccessLevel($oUser, $oTopic) {
		if($oUser->isAdministrator()) {
			return array(self::READ, self::COMMENT);
		}
		
		$aAccessList = array();
		//���������, ���� �� � ������������ ������ � ����� � ������
		if(!$this->canRead($oTopic, $oUser)) {
			$aAccessList[] = self::NONE;
			return $aAccessList;
		}
		
		$aAccessList[] = self::READ;
		
		//��������� ����������� ��������������
		if($this->canComment($oTopic, $oUser)) {
			$aAccessList[] = self::COMMENT;
		}
		
		return $aAccessList;
	}
	
	/**
	* ��������� �������������� ������� ������� ������������ � ������
	* 
	* @param	oUser			������������/����� ���� null ��� �������
	* @param	oTopic			�����
	* @param	string			����������� ������� �������
	* @return	boolean|null	���� ������ �� ���������� - null
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
	* �������������� ������� � WHERE ��� ���������� ������� �������� ������ ������� ������������
	* id = 0 - ��������� ������������. ������������ ����� �� �������, ��� ���� ����� ���� ������ 
	* �� �������� ������ �����.
	* 
	* @param	int		ID ������������, ��� �������� ����������� ����������
	* @return	string	������ ��� ������� � where. ��������� � ������. �������� ����� � ����� ���.
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
		//�� ��� ������ ���� ������� �������� ���� ������ �� ������� � ���� �������� �� �������� �� ������.
		return !$this->Topic_CheckAccessToTopic($oUser, $oTopic);
	}
}

?>