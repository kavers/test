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
*
* ��� ���������� �������������� ������������ ����������� ����� ����������� ������� ��������������
*/
class PluginNotification_ModuleNotify extends PluginNotification_Inherit_ModuleNotify {
	const FREQUENCY_ATONCE = 1;
	const FREQUENCY_DAILY = 2;
	const FREQUENCY_WEEKLY = 3;
	
	protected $accessModuleAvailable = null;
	
	/**
	* ���������� ����� ���� ������������� �� �������������� �����
	* 
	* @param	string		���
	* @return	int			�����
	*/
	public function GetFrequencyNumber($sFrequency) {
		switch (strtolower($sFrequency)) {
			case 'daily':
				return self::FREQUENCY_DAILY;
			case 'weekly':
				return self::FREQUENCY_WEEKLY;
			case 'at_once':
			default:
				return self::FREQUENCY_ATONCE;

		}
	}
	
	/**
	* ��������� ��������� ���������� ���������� � ����� �����.
	* 
	* @param	oBlogUser
	* @param	array		id �������������, ������� �� ������ ������������ �����������
	* @return	array		id �������������, ���������� �����������
	*/
	public function SendNewBlogUser($oBlogUser, $aExceptUserId = array()) {
		if(!is_array($aExceptUserId)) {
			$aExceptUserId = array($aExceptUserId);
		}
		
		$oBlog = $this->Blog_GetBlogById($oBlogUser->getBlogId());
		$oUser = $this->User_GetUserById($oBlogUser->getUserId());
		
		$aSubscriberId = $this->oMapper->GetBlogNewUserSubscribersUids($oBlog);
		if(!$aSubscriberId) {
			return null;
		}
		
		$aSubscriberId = array_diff($aSubscriberId, $aExceptUserId);
		
		$aSubscriber = $this->User_GetUsersByArrayId($aSubscriberId);
		
		$aRecipientId = array();
		foreach($aSubscriber as $oSubscriber) {
			if($this->sendNotifyToBlogNewUserSubscriber($oSubscriber, $oBlog, $oUser)) {
				$aRecipientId[] = $oSubscriber->getId();
			}
		}
		
		return $aRecipientId;
	} 
	
	/**
	 * ���������� ����� ����������� � ����� ����������� � ��� ������
	 *
	 * @param ModuleUser_EntityUser $oUserTo
	 * @param ModuleTopic_EntityTopic $oTopic
	 * @param CommentEntity_TopicComment $oComment
	 * @param ModuleUser_EntityUser $oUserComment
	 */
	public function SendCommentNewToAuthorTopic(ModuleUser_EntityUser $oUserTo, ModuleTopic_EntityTopic $oTopic, ModuleComment_EntityComment $oComment, ModuleUser_EntityUser $oUserComment) {
		/**
		 * ��������� ����� �� ����� ��������� �����������
		 */
		if (!$oUserTo->getSettingsNoticeNewComment()) {
			return ;
		}
		
		$this->Send($oUserTo, 'notify.comment_new.tpl', $this->Lang_Get('notify_subject_comment_new'),
			$aAssigns = array(
				'oUserTo' => $oUserTo,
				'oTopic' => $oTopic,
				'oComment' => $oComment,
				'oUserComment' => $oUserComment
			)
		);
	}
	
	/**
	 * ���������� ����� ����������� �� ������ �� ��� �����������
	 *
	 * @param ModuleUser_EntityUser $oUserTo
	 * @param ModuleTopic_EntityTopic $oTopic
	 * @param CommentEntity_TopicComment $oComment
	 * @param ModuleUser_EntityUser $oUserComment
	 */
	public function SendCommentReplyToAuthorParentComment(ModuleUser_EntityUser $oUserTo, ModuleTopic_EntityTopic $oTopic, ModuleComment_EntityComment $oComment, ModuleUser_EntityUser $oUserComment) {
		/**
		 * ��������� ����� �� ����� ��������� �����������
		 */
		if (!$oUserTo->getSettingsNoticeReplyComment()) {
			return ;
		}
		
		$this->Send($oUserTo, 'notify.comment_reply.tpl', $this->Lang_Get('notify_subject_comment_reply'),
			$aAssigns = array(
				'oUserTo' => $oUserTo,
				'oTopic' => $oTopic,
				'oComment' => $oComment,
				'oUserComment' => $oUserComment
			)
		);
	}
	
	/**
	 * ���������� ����� ����������� � ����� ������ � �����, � ������� �� �������
	 *
	 * @param ModuleUser_EntityUser $oUserTo
	 * @param ModuleTopic_EntityTopic $oTopic
	 * @param ModuleBlog_EntityBlog $oBlog
	 * @param ModuleUser_EntityUser $oUserTopic
	 */
	public function SendTopicNewToSubscribeBlog(ModuleUser_EntityUser $oUserTo, ModuleTopic_EntityTopic $oTopic, ModuleBlog_EntityBlog $oBlog, ModuleUser_EntityUser $oUserTopic) {
		/**
		 * ��������� ����� �� ����� ��������� �����������
		 */
		if (!$oUserTo->getSettingsNoticeNewTopic()) {
			return ;
		}
		
		$this->Send($oUserTo, 'notify.topic_new.tpl', $this->Lang_Get('notify_subject_topic_new').' �'.htmlspecialchars($oBlog->getTitle()).'�',
			$aAssigns = array(
				'oUserTo' => $oUserTo,
				'oTopic' => $oTopic,
				'oBlog' => $oBlog,
				'oUserTopic' => $oUserTopic
			)
		);
	}
	
		/**
	 * ���������� ����������� ��� ����� ������ ���������
	 *
	 * @param ModuleUser_EntityUser $oUserTo
	 * @param ModuleUser_EntityUser $oUserFrom
	 * @param ModuleTalk_EntityTalk $oTalk
	 */
	public function SendTalkNew(ModuleUser_EntityUser $oUserTo,ModuleUser_EntityUser $oUserFrom,ModuleTalk_EntityTalk $oTalk) {
		/**
		 * ��������� ����� �� ����� ��������� �����������
		 */
		if (!$oUserTo->getSettingsNoticeNewTalk()) {
			return ;
		}
		
		$this->Send($oUserTo, 'notify.talk_new.tpl', $this->Lang_Get('notify_subject_talk_new'),
			$aAssigns = array(
				'oUserTo' => $oUserTo,
				'oUserFrom' => $oUserFrom,
				'oTalk' => $oTalk
			)
		);
	}
	
	public function SendTalkCommentNew(ModuleUser_EntityUser $oUserTo,ModuleUser_EntityUser $oUserFrom,ModuleTalk_EntityTalk $oTalk,ModuleComment_EntityComment $oTalkComment) {
		/**
		 * ��������� ����� �� ����� ��������� �����������
		 */
		if (!$oUserTo->getSettingsNoticeNewTalk()) {
			return ;
		}
		
		$this->Send($oUserTo, 'notify.talk_comment_new.tpl', $this->Lang_Get('notify_subject_talk_comment_new'),
			$aAssigns = array(
				'oUserTo' => $oUserTo,
				'oUserFrom' => $oUserFrom,
				'oTalk' => $oTalk,
				'oTalkComment' => $oTalkComment
			)
		);
	}
	
	/**
	 * ���������� ������������ ��������� � ���������� ��� � ������
	 *
	 * @param ModuleUser_EntityUser $oUserTo
	 * @param ModuleUser_EntityUser $oUserFrom
	 */
	public function SendUserFriendNew(ModuleUser_EntityUser $oUserTo,ModuleUser_EntityUser $oUserFrom, $sText,$sPath) {		
		/**
		 * ��������� ����� �� ����� ��������� �����������
		 */
		if (!$oUserTo->getSettingsNoticeNewFriend()) {
			return ;
		}
		
		$this->Send($oUserTo, 'notify.user_friend_new.tpl', $this->Lang_Get('notify_subject_user_friend_new'),
			$aAssigns = array(
				'oUserTo' => $oUserTo,
				'oUserFrom' => $oUserFrom,
				'sText' => $sText,
				'sPath' => $sPath
			)
		);
	}
	
	/**
	* �������� ����������� ���� ����������� ����� � ����� �����������
	* � ���������� �� �������������� ������.
	*
	* @param	oTopic		�������������� �����
	* @param	oComment	����� �����������
	* @param	oComment	������������ ����������
	* @param	array		id �������������, ������� �� ����� �������� ����������� (������ � ������)
	* @return	array		id ���� ���� ���� ���������� �����������
	*/
	public function SendNotificationsToBlogCommentSubscribers($oTopic, $oCommentNew, $oCommentParent, $aExceptUserId = array()) {
		if(!is_array($aExceptUserId)) {
			$aExceptUserId = array($aExceptUserId);
		}
		
		$oBlog = $this->Blog_GetBlogById($oTopic->getBlogId());
		
		$aSubscriberId = $this->oMapper->GetBlogCommentSubscribersUids($oBlog);
		if(!$aSubscriberId) {
			return null;
		}
		
		$aSubscriberId = array_diff($aSubscriberId, $aExceptUserId);
		
		$aSubscriber = $this->User_GetUsersByArrayId($aSubscriberId);
		
		$aRecipientId = array();
		foreach($aSubscriber as $oSubscriber) {
			if($this->sendNotifyToBlogCommentSubscriber($oSubscriber, $oTopic, $oBlog, $this->User_GetUserCurrent(), $oCommentNew)) {
				$aRecipientId[] = $oSubscriber->getId();
			}
		}
		
		return $aRecipientId;
	}
	
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
	public function SendNotificationsToTopicCommentators($oTopic, $oCommentNew, $oCommentParent, $aExceptUserId = array()) {
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
	public function SendNotificationsToParentCommentator($oTopic, $oCommentNew, $oCommentParent, $aExceptUserId = array()) {
		if(!is_array($aExceptUserId)) {
			$aExceptUserId = array($aExceptUserId);
		}

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
	public function SendNotificationsToBlogSubscribers($oTopic, $oBlog, $aExceptUserId = array()) {
		if(!is_array($aExceptUserId)) {
			$aExceptUserId = array($aExceptUserId);
		}
		
		$aSubscriberId = $this->oMapper->GetBlogSubscribersUids($oBlog);
		if(!$aSubscriberId) {
			return null;
		}
		
		$aSubscriberId = array_diff($aSubscriberId, $aExceptUserId);
		
		$aSubscriber = $this->User_GetUsersByArrayId($aSubscriberId);
		
		$aRecipientId = array();
		foreach($aSubscriber as $oSubscriber) {
			if($this->sendNotifyToBlogSubscriber($oSubscriber, $oTopic, $oBlog, $this->User_GetUserCurrent())) {
				$aRecipientId[] = $oSubscriber->getId();
			}
		}
		
		return $aRecipientId;
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
	public function SendNotificationsToAuthorFriends($oTopic, $oBlog, $aExceptUserId = array()) {
		if(!is_array($aExceptUserId)) {
			$aExceptUserId = array($aExceptUserId);
		}

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
	public function SendNotificationsAboutNewFriends($oFriend, $aExceptUserId = array()) {
		if(!is_array($aExceptUserId)) {
			$aExceptUserId = array($aExceptUserId);
		}

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
	
	/**
	* �������� ����������� ������������ ��� �� �� ������� � ����,
	* ���� �� ������� �������������� �������.
	* 
	* @param	oUser		���������� ���������
	* @param	oUser		����������� ���������
	* @param	array		������ id �������������, ������� ����������� �� ����������
	* @return	boolean		��������� �������
	*/
	public function SendRequestToUser($oUserTo, $oUserFrom, $aExceptUserId = array()) {
		if(!is_array($aExceptUserId)) {
			$aExceptUserId = array($aExceptUserId);
		}
		if(!in_array($oUserTo->getId(), $aExceptUserId)) {
			return $this->sendNotifyRequestToUser($oUserTo, $oUserFrom);
		}
		
		return false;
	}
	
	/**
	 * ������������� ����� �������� ����������� �� email. ������������� ��� ����� � �������������� �����������
	 *
	 * @param ModuleUser_EntityUser | string $oUserTo - ���� ���������� (������������ ��� email)
	 * @param unknown_type $sTemplate - ������ ��� ��������
	 * @param unknown_type $sSubject - ���� ������
	 * @param unknown_type $aAssign - ������������� ������ ��� �������� ���������� � ������ ������
	 * @param unknown_type $sPluginName - ������ �� �������� ���������� ��������
	 */
	public function Send($oUserTo,$sTemplate,$sSubject,$aAssign=array(),$sPluginName=null) {		
		if ($oUserTo instanceof ModuleUser_EntityUser) {
			$sMail=$oUserTo->getMail();
			$sName=$oUserTo->getLogin();
		} else {
			$sMail=$oUserTo;
			$sName='';
			$oUserTo = Engine::GetEntity('User', array('settings_notice_frequency' => self::FREQUENCY_ATONCE));
		}
		/**
		 * ������� � ������ ����������
		 */
		foreach ($aAssign as $k=>$v) {
			$this->oViewerLocal->Assign($k,$v);
		}
		/**
		 * ��������� ������
		 */
		$sBody=$this->oViewerLocal->Fetch($this->GetTemplatePath($sTemplate,$sPluginName));
		/**
		 * ���� � ������������� ������ ���������� ����� �������� ��� ���������� ������ ������������� ��������, 
		 * �� ��������� ������� � ������. � ��������� ������,
		 * ����� �������� �� email
		 */
		if(Config::Get('module.notify.delayed') || 
			$oUserTo->getSettingsNoticeFrequency() == self::FREQUENCY_DAILY ||
			$oUserTo->getSettingsNoticeFrequency() == self::FREQUENCY_WEEKLY
		) {
			//��������� ������������� ������
			$aCurTasks = $this->oMapper->GetTasksByUser($oUserTo);
			
			if($aCurTasks) {
				$oNotifyTask = $aCurTasks[0];
				$oNotifyTask->setNotifyText(
					$oNotifyTask->getNotifyText() . '<br /><hr /><br />' . $sBody
				);
				$this->oMapper->UpdateTask($oNotifyTask);
			} else {
				switch($oUserTo->getSettingsNoticeFrequency()) {
					case self::FREQUENCY_DAILY:
						$sMailSubject = $this->Lang_Get('notification_subject_daily');
						break;
					case self::FREQUENCY_WEEKLY:
						$sMailSubject = $this->Lang_Get('notification_subject_weekly');
						break;
					case self::FREQUENCY_ATONCE:
					default:
						$sMailSubject = $sSubject;
				}
				
				$oNotifyTask=Engine::GetEntity(
					'Notify_Task', 
					array(
						'user_mail'      => $sMail,
						'user_login'     => $sName,
						'notify_text'    => $sBody,
						'notify_subject' => $sMailSubject,
						'date_created'   => date("Y-m-d H:i:s"),
						'notify_task_status' => self::NOTIFY_TASK_STATUS_NULL,
						'notify_freq_type' => $oUserTo->getSettingsNoticeFrequency()
					)
				);
				
				if(Config::Get('module.notify.insert_single')) {
					$this->aTask[] = $oNotifyTask;
				} else {
					$this->oMapper->AddTask($oNotifyTask);
				}
			}
		} else {
			/**
			 * ���������� ����
			 */
			$this->Mail_SetAdress($sMail,$sName);
			$this->Mail_SetSubject($sSubject);
			$this->Mail_SetBody($sBody);
			$this->Mail_setHTML();
			$this->Mail_Send();
		}
	}
	
	/**
	* ���������������� �������� ������� ������� ������
	*
	* @param	oUser		���� ���������� �����������
	* @param	oUser		��� ����������
	* @return	boolean		��������� ��������
	*/
	protected function sendNotifyRequestToUser($oUserTo, $oUserFrom) {
		if(!$oUserTo->getSettingsNoticeRequest()) {
			return false;
		}
		$this->Send($oUserTo, 'notification.request.tpl', $this->Lang_Get('notification_subject_request'),
			$aAssigns = array(
				'oUserTo' => $oUserTo,
				'oUser' => $oUserFrom
			),
			PLUGIN_NOTIFICATION_NAME
		);
		
		return true;
	}
	
	protected function sendNotifycationToFriends($oUser, $oNewFriend, $aFriend, $aExceptUserId = array()) {
		if(!is_array($aExceptUserId)) {
			$aExceptUserId = array($aExceptUserId);
		}

		$aRecipientId = array();
		foreach($aFriend as $oUserFriend) {
			if(!in_array($oUserFriend->getId(), $aExceptUserId)) {
				if($this->sendNotifyToFriend($oUserFriend, $oUser, $oNewFriend)) {
					$aRecipientId[] = $oUserFriend->getId();
				}
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
	* ���������������� �������� ����������� � ����� ����� � ����������
	*
	* @param	oUser		���� ���������� �����������
	* @param	oBlog		� ����� ����������
	* @param	oUser		��� �� ����� ����
	* @return	boolean		��������� ��������
	*/
	protected function sendNotifyToBlogNewUserSubscriber($oUserTo, $oBlog, $oUserNew) {
		if(!$oUserTo->getSettingsNoticeNewUserBlogsSubscribe()) {
			return false;
		}
		
		$this->Send($oUserTo, 'notification.new_user_blog.tpl', $this->Lang_Get('notification_subject_new_user_blog'),
			$aAssigns = array(
				'oUserTo' => $oUserTo,
				'oBlog' => $oBlog,
				'oUserNew' => $oUserNew
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
	* ���������������� �������� ����������� ���������� �����
	*
	* @param	oUser		���� ���������� �����������
	* @param	oTopic		����� �����
	* @param	oComment	� ����� �����
	* @param	oUser		�����
	* @return	boolean		��������� ��������
	*/
	protected function sendNotifyToBlogSubscriber($oUserTo, $oTopic, $oBlog, $oTopicAuthor) {
		$this->Send($oUserTo, 'notification.new_blog_topic.tpl', $this->Lang_Get('notification_subject_new_blog_topic'),
			$aAssigns = array(
				'oUserTo' => $oUserTo,
				'oTopic' => $oTopic,
				'oBlog' => $oBlog,
				'oUserTopic' => $oTopicAuthor
			),
			PLUGIN_NOTIFICATION_NAME
		);
		
		return true;
	}
	
	/**
	* ���������������� �������� ����������� ���������� �� ����������� �����
	*
	* @param	oUser		���� ���������� �����������
	* @param	oTopic		����� �����
	* @param	oComment	� ����� �����
	* @param	oUser		�����
	* @return	boolean		��������� ��������
	*/
	protected function sendNotifyToBlogCommentSubscriber($oUserTo, $oTopic, $oBlog, $oCommentAuthor, $oCommentNew) {
		$this->Send($oUserTo, 'notification.new_blog_comment.tpl', $this->Lang_Get('notification_subject_new_blog_comment'),
			$aAssigns = array(
				'oUserTo' => $oUserTo,
				'oTopic' => $oTopic,
				'oBlog' => $oBlog,
				'oUserComment' => $oCommentAuthor,
				'oComment' => $oCommentNew
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