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
* ����� ��������� ����������� ������ ��� ���������������� �������� ���������,
* � �� ������������� ������ ������� ��������.
* ������� ����� ������� ���������� �� �����.
*/
class PluginNotification_ModuleNotify extends PluginNotification_Inherit_ModuleNotify {
	protected $accessModuleAvailable = null;
	/**
	* �������� ����������� ����, ��� ������������� ���� �����
	* � ���������� �� �������������� ������.
	*
	* @param	oTopic		�������������� �����
	* @param	oComment	����� �����������
	* @param	oComment	������������ ����������
	* @param	array		id �������������, ������� �� ����� �������� ����������� (������ � ������)
	* @return	array		id ���� ���� ���� ���������� �����������
	*/
	public function SendNotificationsToTopicCommentators($oTopic, $oCommentNew, $oCommentParent, $aExceptUserId) {
		if(!is_array($aExceptUserId)) {
			$aExceptUserId = array($aExceptUserId);
		}
		
		$aCommentatorId = $this->oMapper->GetCommentatorsUids($oTopic);
		//file_put_contents('C:\webservers\home\sl.1kz.ru\www\err.log', $oTopic->getTitle() . date('r') . ' ' . $aCommentatorId[0], FILE_APPEND);
		if(!$aCommentatorId) {
			return null;
		}
		
		$aCommentatorId = array_diff($aCommentatorId, $aExceptUserId);
		
		$aCommentator = $this->User_GetUsersByArrayId($aCommentatorId);
		
		
		$aRecipientId = array();
		foreach($aCommentator as $oUser) {
			if($this->isAccessModuleAvailable()) {
				if(!$this->PluginAccesstotopic_Access_CheckUserAccess($oUser, $oTopic, 'read')) {
					continue;
				}
			}
			if($this->sendNotifyToTopicCommentator($oUser, $oTopic, $oCommentNew, $this->User_GetUserCurrent())) {
				$aRecipientId[] = $oUser->getId();
			}
		}
		
		return $aRecipientId;
	}
	
	/**
	* ���������� ����������� ������ ������������� �����������, ���� �� �������� �� ������ ������
	* 
	* @param	oTopic		�������������� �����
	* @param	oComment	����� �����������
	* @param	oComment	������������ ����������
	* @param	array		id �������������, ������� �� ����� �������� ����������� (������ � ������)
	* @return	array|null		id ���� ���� ���� ���������� �����������
	*/
	public function SendNotificationsToParentCommentator($oTopic, $oCommentNew, $oCommentParent, $aExceptUserId) {
		if(!$oCommentParent) {
			return null;
		}
		
		$oCommentatorParent = $this->User_GetUserById($oCommentParent->getUserId());
		
		if(!$oCommentatorParent) {
			return null;
		}
		
		if($this->isAccessModuleAvailable()) {
			if(!$this->PluginAccesstotopic_Access_CheckUserAccess($oCommentatorParent, $oTopic, 'read')) {
				return null;
			}
		}
		
		$aRecipientId = array();
		if(!in_array($oCommentatorParent->getId(), $aExceptUserId)) {
			$this->sendNotifyToParentCommentator($oCommentatorParent, $oTopic, $oCommentNew, $this->User_GetUserCurrent());
			$aRecipientId[] = $oCommentatorParent->getId();
		}
		
		return $aRecipientId;
	}
	
	/**
	* �������� ����������� � ����� ������� ���� ���������� ����������,
	* ���������� �������������� �������.
	* 
	* @param	oTopic		����� �����
	* @param	oBlog		���� � ������� ����������� ������
	* @param	array		������ id �������������, ������� ����������� �� ����������
	* @return	array		������ id �������������, ������� ���� ������� �����������
	*/
	public function SendNotificationsToBlogSubscribers($oTopic, $oBlog, $aExceptUserId) {
		return array();
	}
	
	/**
	* �������� ����������� ����������� ������������ � ����� ������ ��������� ��,
	* ���� ��� �������� �������������� ������� � � ��� ���� ������ � ���� ������.
	* 
	* @param	oTopic		����� �����
	* @param	oBlog		���� � ������� ����������� ������
	* @param	array		������ id �������������, ������� ����������� �� ����������
	* @return	array|null		������ id �������������, ������� ���� ������� �����������
	*/
	public function SendNotificationsToAuthorFriends($oTopic, $oBlog, $aExceptUserId) {
		$aSubscriberId = $this->oMapper->GetUserSubscribers($oTopic->getUserId());
		
		if(!$aSubscriberId) {
			return null;
		}
		$aSubscriberId = array_diff($aSubscriberId, $aExceptUserId);
		
		$aSubscriber = $this->User_GetUsersByArrayId($aSubscriberId);
		
		$aRecipientId = array();
		foreach($aSubscriber as $oSubscriber) {
			if($this->isAccessModuleAvailable()) {
				if(!$this->PluginAccesstotopic_Access_CheckUserAccess($oSubscriber, $oTopic, 'read')) {
					continue;
				}
			}
			if($this->sendNotificationToAuthorFriend($oSubscriber, $oTopic, $oBlog, $this->User_GetUserCurrent())) {
				$aRecipientId[] = $oSubscriber->getId();
			}
		}
		
		return $aRecipientId;
	}
	
	/**
	* �������� ����������� �������� ������� ������������ � ����� �����,
	* ���� ��� �������� �������������� �������.
	* 
	* @param	oFriend
	* @param	array		������ id �������������, ������� ����������� �� ����������
	* @return	array|null		������ id �������������, ������� ���� ������� �����������
	*/
	public function SendNotificationsAboutNewFriends($oFriend, $aExceptUserId) {
		if(!$oFriend) {
			return null;
		}
		
		if($oFriend->getFriendStatus() != ModuleUser::USER_FRIEND_ACCEPT+ModuleUser::USER_FRIEND_OFFER) {
			return null;
		}
		
		$oUserFrom = $this->User_GetUserById($oFriend->getUserFrom());
		$oUserTo = $this->User_GetUserById($oFriend->getUserTo());
		
		$aFriendFrom = $this->User_GetUsersFriend($oFriend->getUserFrom());
		$aExceptFrom = $this->sendNotifycationToFriends($oUserFrom, $oUserTo, $aFriendFrom, $aExceptUserId);
		
		$aExceptUserId = array_merge($aExceptUserId, $aExceptFrom);
		
		$aFriendTo = $this->User_GetUsersFriend($oFriend->getUserTo());
		$aExceptTo = $this->sendNotifycationToFriends($oUserTo, $oUserFrom, $aFriendTo, $aExceptUserId);
		
		$aExceptUserId = array_merge($aExceptUserId, $aExceptTo);
		
		return $aExceptUserId;
	}
	
	protected function sendNotifycationToFriends($oUser, $oNewFriend, $aFriend, $aExceptUserId) {
		$aRecipientId = array();
		foreach($aFriend as $oUserFriend) {
			if($this->sendNotifyToFriend($oUserFriend, $oUser, $oNewFriend)) {
				$aRecipientId[] = $oUserFriend->getId();
			}
		}
		
		return $aRecipientId;
	}
	
	/**
	* ���������������� �������� ����������� ����� � ����� ����� ����� :)
	*
	* @param	oUser		���� ���������� �����������
	* @param	oUser		� ���� ����� ����
	* @param	oUser		��� �� ����
	* @return	boolean		��������� ��������
	*/
	protected function sendNotifyToFriend($oUserTo, $oUser, $oNewFriend) {
		if(!$oUserTo->getSettingsNoticeFriendNews()) {
			return false;
		}
		
		$this->Send($oUserTo, 'notification.new_friend_friend.tpl', $this->Lang_Get('notification_subject_friend_news'),
			$aAssigns = array(
				'oUserTo' => $oUserTo,
				'oUser' => $oUser,
				'oNewFriend' => $oNewFriend
			),
			PLUGIN_NOTIFICATION_NAME
		);
		
		return true;
	}
	/**
	* ���������������� �������� ����������� ������������ ������ � ����� �����������
	*
	* @param	oUser		���� ���������� �����������
	* @param	oTopic		� ����� ������ ��������� �������
	* @param	oComment	����� �����������
	* @param	oUser		����� �����������
	* @return	boolean		��������� ��������
	*/
	protected function sendNotifyToTopicCommentator($oUserTo, $oTopic, $oComment, $oUserComment) {
		if(!$oUserTo->getSettingsNoticeNewTopicCommented()) {
			return false;
		}
		
		$this->Send($oUserTo, 'notification.new_topic_comment.tpl', $this->Lang_Get('notification_subject_topic_comment_new'),
			$aAssigns = array(
				'oUserTo' => $oUserTo,
				'oTopic' => $oTopic,
				'oComment' => $oComment,
				'oUserComment' => $oUserComment
			),
			PLUGIN_NOTIFICATION_NAME
		);
		
		return true;
	}
	
	/**
	* ���������������� �������� ����������� ������ ������������� ����������� � ����� �����������
	*
	* @param	oUser		���� ���������� �����������
	* @param	oTopic		� ����� ������ ��������� �������
	* @param	oComment	����� �����������
	* @param	oUser		����� �����������
	* @return	boolean		��������� ��������
	*/
	protected function sendNotifyToParentCommentator($oUserTo, $oTopic, $oComment, $oUserComment) {
		if(!$oUserTo->getSettingsNoticeNewCommentCommented()) {
			if(isset($oUserComment)) {
				if(!$oUserComment->isAdministartor()) {
					return false;
				}
			} else {
				return false;
			}
		}
		
		$this->Send($oUserTo, 'notification.new_comment_comment.tpl', $this->Lang_Get('notification_subject_comment_comment_new'),
			$aAssigns = array(
				'oUserTo' => $oUserTo,
				'oTopic' => $oTopic,
				'oComment' => $oComment,
				'oUserComment' => $oUserComment
			),
			PLUGIN_NOTIFICATION_NAME
		);
		
		return true;
	}
	
	/**
	* ���������������� �������� ����������� ���������� ������ ������
	*
	* @param	oUser		���� ���������� �����������
	* @param	oTopic		����� �����
	* @param	oComment	� ����� �����
	* @param	oUser		�����
	* @return	boolean		��������� ��������
	*/
	protected function sendNotificationToAuthorFriend($oUserTo, $oTopic, $oBlog, $oTopicAuthor) {
		if(!$oUserTo->getSettingsNoticeFriendNews()) {
			return false;
		}
		
		$this->Send($oUserTo, 'notification.new_friend_topic.tpl', $this->Lang_Get('notification_subject_friend_news'),
			$aAssigns = array(
				'oUserTo' => $oUserTo,
				'oTopic' => $oTopic,
				'oBlog' => $oBlog,
				'oTopicAuthor' => $oTopicAuthor
			),
			PLUGIN_NOTIFICATION_NAME
		);
		
		return true;
	}
	
	/**
	* ��������� ��������� ������� AccessToTopic ��� �������� ���� �������
	* 
	* @return	boolean
	*/
	protected function isAccessModuleAvailable() {
		if($this->accessModuleAvailable === null) {
			$aActivePlugin = $this->Plugin_GetActivePlugins();
			$this->accessModuleAvailable = in_array('accesstotopic', $aActivePlugin);
		}
		
		return $this->accessModuleAvailable;
	}
}
?>