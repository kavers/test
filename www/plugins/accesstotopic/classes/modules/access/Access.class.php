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
	
	//����������� �� ������� � ������� ������������� �����
	static protected $aPersonalBlogAccessLevel = array(
		'FOR_ALL' => 0,
		'FOR_REGISTERED' => 1,
		'FOR_FRIENDS' => 2,
		'FOR_TWOSIDE_FRIENDS' => 3,
		'FOR_OWNER_ONLY' => 4
	);
	
	//����������� �� ������� � ������� ������������� �����
	static protected $aCollectiveBlogAccessLevel = array(
		'FOR_ALL' => 100,
		'FOR_REGISTERED' => 101,
		'FOR_COLLECTIVE' => 102
	);
	
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
	public function GetUserAccessLevel($oUser, $oTopic) {
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
	* �������������� ������� � WHERE ��� ���������� ������� �������� ������ ������� ������������
	* id = 0 - ��������� ������������. ������������ ����� �� �������, ��� ���� ����� ���� ������ 
	* �� �������� ������ �����.
	* 
	* @param	int		ID ������������, ��� �������� ����������� ����������
	* @return	string	������ ��� ������� � where. ��������� � ������. �������� ����� � ����� ���.
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
	* ���������� ������ ����������� ������� ��� ������ ������������� �����
	* 
	* @return	array
	*/
	static public function GetPersonalTopicAccessLevels() {
		return self::$aPersonalBlogAccessLevel;
	}
	
	/**
	* ���������� ������ ����������� ������� ��� ������ ������������� �����
	* 
	* @return	array
	*/
	static public function GetCollectiveTopicAccessLevels() {
		return self::$aCollectiveBlogAccessLevel;
	}
	
	/**
	* ���������, ���� �� � ������������ ������ � ������
	* 
	* @param	object		����������� ������������
	* @param	object		����������� �����
	* @return	boolean		�������. true, ���� ������ ����.
	*/
	public function CheckAccessToTopic($oUser, $oTopic) {
		//������������ ������ ����� ������ � ����������� �������
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
				//������� �������� �� ����������, ��� ��� ����� ������ �� ��� ���� � ������ ������
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