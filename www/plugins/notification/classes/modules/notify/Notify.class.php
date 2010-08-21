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
*
* Для управления переодичностью отправленных уведомлений часть стандартных методов переопределена
*/
class PluginNotification_ModuleNotify extends PluginNotification_Inherit_ModuleNotify {
	const FREQUENCY_ATONCE = 1;
	const FREQUENCY_DAILY = 2;
	const FREQUENCY_WEEKLY = 3;
	
	protected $accessModuleAvailable = null;
	
	/**
	* Возвращает номер типа переодичности по мнемоническому имени
	* 
	* @param	string		имя
	* @return	int			номер
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
	* Рассылаем сообщения участникам сообщества о новом члене.
	* 
	* @param	oBlogUser
	* @param	array		id пользователей, которым не должны отправляться уведомления
	* @return	array		id пользователей, получивших уведомления
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
	 * Отправляет юзеру уведомление о новом комментарии в его топике
	 *
	 * @param ModuleUser_EntityUser $oUserTo
	 * @param ModuleTopic_EntityTopic $oTopic
	 * @param CommentEntity_TopicComment $oComment
	 * @param ModuleUser_EntityUser $oUserComment
	 */
	public function SendCommentNewToAuthorTopic(ModuleUser_EntityUser $oUserTo, ModuleTopic_EntityTopic $oTopic, ModuleComment_EntityComment $oComment, ModuleUser_EntityUser $oUserComment) {
		/**
		 * Проверяем можно ли юзеру рассылать уведомление
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
	 * Отправляет юзеру уведомление об ответе на его комментарий
	 *
	 * @param ModuleUser_EntityUser $oUserTo
	 * @param ModuleTopic_EntityTopic $oTopic
	 * @param CommentEntity_TopicComment $oComment
	 * @param ModuleUser_EntityUser $oUserComment
	 */
	public function SendCommentReplyToAuthorParentComment(ModuleUser_EntityUser $oUserTo, ModuleTopic_EntityTopic $oTopic, ModuleComment_EntityComment $oComment, ModuleUser_EntityUser $oUserComment) {
		/**
		 * Проверяем можно ли юзеру рассылать уведомление
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
	 * Отправляет юзеру уведомление о новом топике в блоге, в котором он состоит
	 *
	 * @param ModuleUser_EntityUser $oUserTo
	 * @param ModuleTopic_EntityTopic $oTopic
	 * @param ModuleBlog_EntityBlog $oBlog
	 * @param ModuleUser_EntityUser $oUserTopic
	 */
	public function SendTopicNewToSubscribeBlog(ModuleUser_EntityUser $oUserTo, ModuleTopic_EntityTopic $oTopic, ModuleBlog_EntityBlog $oBlog, ModuleUser_EntityUser $oUserTopic) {
		/**
		 * Проверяем можно ли юзеру рассылать уведомление
		 */
		if (!$oUserTo->getSettingsNoticeNewTopic()) {
			return ;
		}
		
		$this->Send($oUserTo, 'notify.topic_new.tpl', $this->Lang_Get('notify_subject_topic_new').' «'.htmlspecialchars($oBlog->getTitle()).'»',
			$aAssigns = array(
				'oUserTo' => $oUserTo,
				'oTopic' => $oTopic,
				'oBlog' => $oBlog,
				'oUserTopic' => $oUserTopic
			)
		);
	}
	
		/**
	 * Отправляет уведомление при новом личном сообщении
	 *
	 * @param ModuleUser_EntityUser $oUserTo
	 * @param ModuleUser_EntityUser $oUserFrom
	 * @param ModuleTalk_EntityTalk $oTalk
	 */
	public function SendTalkNew(ModuleUser_EntityUser $oUserTo,ModuleUser_EntityUser $oUserFrom,ModuleTalk_EntityTalk $oTalk) {
		/**
		 * Проверяем можно ли юзеру рассылать уведомление
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
		 * Проверяем можно ли юзеру рассылать уведомление
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
	 * Отправляет пользователю сообщение о добавлении его в друзья
	 *
	 * @param ModuleUser_EntityUser $oUserTo
	 * @param ModuleUser_EntityUser $oUserFrom
	 */
	public function SendUserFriendNew(ModuleUser_EntityUser $oUserTo,ModuleUser_EntityUser $oUserFrom, $sText,$sPath) {		
		/**
		 * Проверяем можно ли юзеру рассылать уведомление
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
	* Отправка уведомлений всем подписчикам блога о новом комментарии
	* и подписался на соответсвующий сервис.
	*
	* @param	oTopic		комментируемый топик
	* @param	oComment	новый комментарий
	* @param	oComment	родительский комментрий
	* @param	array		id пользователей, которым не нужно высылать уведомления (авторы и прочая)
	* @return	array		id всех кому были отправлены уведомления
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
	* Отправка уведомлений всем, кто комментировал этот топик
	* и подписался на соответсвующий сервис.
	*
	* @param	oTopic		комментируемый топик
	* @param	oComment	новый комментарий
	* @param	oComment	родительский комментрий
	* @param	array		id пользователей, которым не нужно высылать уведомления (авторы и прочая)
	* @return	array		id всех кому были отправлены уведомления
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
	* Отправляем уведомление автору родительского комментария, если он подписан на данный сервис
	* 
	* @param	oTopic		комментируемый топик
	* @param	oComment	новый комментарий
	* @param	oComment	родительский комментрий
	* @param	array		id пользователей, которым не нужно высылать уведомления (авторы и прочая)
	* @return	array|null		id всех кому были отправлены уведомления
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
	* Высылаем уведомления о новых топиках всем участникам сообщества,
	* включившим соответсвующую функцию.
	* 
	* @param	oTopic		новый топик
	* @param	oBlog		блог в который добавляется запись
	* @param	array		список id пользователей, которым уведомления не высылаются
	* @return	array		список id пользователей, которым были высланы уведомления
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
	* Высылаем уведомления подписчикам пользователя о новой записи сделанной им,
	* если они включили соответсвующую функцию и у них есть доступ к этой записи.
	* 
	* @param	oTopic		новый топик
	* @param	oBlog		блог в который добавляется запись
	* @param	array		список id пользователей, которым уведомления не высылаются
	* @return	array|null		список id пользователей, которым были высланы уведомления
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
	* Высылаем уведомления взаимным друзьям пользователя о новом друге,
	* если они включили соответсвующую функцию.
	* 
	* @param	oFriend
	* @param	array		список id пользователей, которым уведомления не высылаются
	* @return	array|null		список id пользователей, которым были высланы уведомления
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
	* Высылаем уведомление пользователю что бы он написал в блог,
	* если он включил соответсвующую функцию.
	* 
	* @param	oUser		получатель сообщения
	* @param	oUser		отправитель сообщения
	* @param	array		список id пользователей, которым уведомления не высылаются
	* @return	boolean		результат попытки
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
	 * Универсальный метод отправки уведомлений на email. Модифицирован для раоты с переодичностью уведомлений
	 *
	 * @param ModuleUser_EntityUser | string $oUserTo - кому отправляем (пользователь или email)
	 * @param unknown_type $sTemplate - шаблон для отправки
	 * @param unknown_type $sSubject - тема письма
	 * @param unknown_type $aAssign - ассоциативный массив для загрузки переменных в шаблон письма
	 * @param unknown_type $sPluginName - плагин из которого происходит отправка
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
		 * Передаём в шаблон переменные
		 */
		foreach ($aAssign as $k=>$v) {
			$this->oViewerLocal->Assign($k,$v);
		}
		/**
		 * Формируем шаблон
		 */
		$sBody=$this->oViewerLocal->Fetch($this->GetTemplatePath($sTemplate,$sPluginName));
		/**
		 * Если в конфигураторе указан отложенный метод отправки или получатель выбрал переодическую дсотавку, 
		 * то добавляем задание в массив. В противном случае,
		 * сразу отсылаем на email
		 */
		if(Config::Get('module.notify.delayed') || 
			$oUserTo->getSettingsNoticeFrequency() == self::FREQUENCY_DAILY ||
			$oUserTo->getSettingsNoticeFrequency() == self::FREQUENCY_WEEKLY
		) {
			//Проверяем сущесвтование задачи
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
			 * Отправляем мыло
			 */
			$this->Mail_SetAdress($sMail,$sName);
			$this->Mail_SetSubject($sSubject);
			$this->Mail_SetBody($sBody);
			$this->Mail_setHTML();
			$this->Mail_Send();
		}
	}
	
	/**
	* Непосредственная отправка запроса сделать запись
	*
	* @param	oUser		кому отправляем уведомление
	* @param	oUser		кто отправляет
	* @return	boolean		результат отправки
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
	* Непосредственная отправка уведомления о новом члене в сообществе
	*
	* @param	oUser		кому отправляем уведомление
	* @param	oBlog		в каком сообществе
	* @param	oUser		что за новый член
	* @return	boolean		результат отправки
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
	* Непосредственная отправка уведомления подписчику блога
	*
	* @param	oUser		кому отправляем уведомление
	* @param	oTopic		новый топик
	* @param	oComment	в каком блоге
	* @param	oUser		автор
	* @return	boolean		результат отправки
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
	* Непосредственная отправка уведомления подписчику на комментарии блога
	*
	* @param	oUser		кому отправляем уведомление
	* @param	oTopic		новый топик
	* @param	oComment	в каком блоге
	* @param	oUser		автор
	* @return	boolean		результат отправки
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