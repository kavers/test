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
* Решил расширить стандартный модуль для непосредственной отправки сообщений,
* а не реализовывать данные функции отдельно.
* Большая часть функций вызывается из хуков.
*/
class PluginNotification_ModuleNotify extends PluginNotification_Inherit_ModuleNotify {
	protected $accessModuleAvailable = null;
	/**
	* Отправка уведомлений всем, кто комментировал этот топик
	* и подписался на соответсвующий сервис.
	*
	* @param	oTopic		комментируемый топик
	* @param	oComment	новый комментарий
	* @param	oComment	родительский комментрий
	* @param	array		id пользователей, которым не нужно высылать уведомления (авторы и прочая)
	* @return	array		id всех кому были отправлены уведомления
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
	* Отправляем уведомление автору родительского комментария, если он подписан на данный сервис
	* 
	* @param	oTopic		комментируемый топик
	* @param	oComment	новый комментарий
	* @param	oComment	родительский комментрий
	* @param	array		id пользователей, которым не нужно высылать уведомления (авторы и прочая)
	* @return	array|null		id всех кому были отправлены уведомления
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
	* Высылаем уведомления о новых топиках всем участникам сообщества,
	* включившим соответсвующую функцию.
	* 
	* @param	oTopic		новый топик
	* @param	oBlog		блог в который добавляется запись
	* @param	array		список id пользователей, которым уведомления не высылаются
	* @return	array		список id пользователей, которым были высланы уведомления
	*/
	public function SendNotificationsToBlogSubscribers($oTopic, $oBlog, $aExceptUserId) {
		return array();
	}
	
	/**
	* Высылаем уведомления подписчикам пользователя о новой записи сделанной им,
	* если они включили соответсвующую функцию и у них есть доступ к этой записи.
	* 
	* @param	oTopic		новый топик
	* @param	oBlog		блог в который добавляется запись
	* @param	array		список id пользователей, которым уведомления не высылаются
	* @return	array|null		список id пользователей, которым были высланы уведомления
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
	* Высылаем уведомления взаимным друзьям пользователя о новом друге,
	* если они включили соответсвующую функцию.
	* 
	* @param	oFriend
	* @param	array		список id пользователей, которым уведомления не высылаются
	* @return	array|null		список id пользователей, которым были высланы уведомления
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
	* Непосредственная отправка уведомления другу о новом друге друга :)
	*
	* @param	oUser		кому отправляем уведомление
	* @param	oUser		у кого новый друг
	* @param	oUser		что за друг
	* @return	boolean		результат отправки
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
	* Непосредственная отправка уведомления комментатору топика о новом комментарии
	*
	* @param	oUser		кому отправляем уведомление
	* @param	oTopic		в каком топике произошло событие
	* @param	oComment	новый комментарий
	* @param	oUser		автор комментария
	* @return	boolean		результат отправки
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
	* Непосредственная отправка уведомления автору родительского комментария о новом комментарии
	*
	* @param	oUser		кому отправляем уведомление
	* @param	oTopic		в каком топике произошло событие
	* @param	oComment	новый комментарий
	* @param	oUser		автор комментария
	* @return	boolean		результат отправки
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
	* Непосредственная отправка уведомления подписчику автора топика
	*
	* @param	oUser		кому отправляем уведомление
	* @param	oTopic		новый топик
	* @param	oComment	в каком блоге
	* @param	oUser		автор
	* @return	boolean		результат отправки
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
	* Проверяем установку плагина AccessToTopic для проверки прав доступа
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