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

class PluginNotification_ModuleNotify_MapperNotify extends  PluginNotification_Inherit_ModuleNotify_MapperNotify {
	/**
	 * Возвращает список id пользователей оставивших комментарии к топику и включившие соответсвующие
	 * уведомления.
	 * 
	 * @param  oTopic $oTopic
	 * @return array
	 */
	public function GetCommentatorsUids($oTopic) {
		$sql = '
				SELECT 
					DISTINCT c.user_id
				FROM '.Config::Get('db.table.comment').' as c,
					'.Config::Get('db.table.user').' as u
				WHERE 
					c.target_id = ?d AND
					c.user_id = u.user_id AND
					'. (Config::Get('plugin.notification.unionCommentsNotification') ? 
							'user_settings_notice_new_comment = 1' : 
							'user_settings_notice_new_topic_commented = 1') .'
					';
		if (
			$aRows=$this->oDb->select($sql, $oTopic->getId())
		) {
			foreach($aRows as $row) {
				$aResult[] = $row['user_id'];
			}
			return $aResult;
		}
		return null;
	}
	
	/**
	 * Получить список подписчиков указанного пользователя
	 * Метод GetUsersFriend модуля User не применим, так как возвращает
	 * только взаимных друзей.
	 *
	 * @param	string		$sUserId
	 * @return	array
	 */
	public function GetUserSubscribers($sUserId) {
		$aSubscribers = array_merge($this->getUserSubscribersFromDB($sUserId, true), $this->getUserSubscribersFromDB($sUserId, false));
		return array_unique($aSubscribers);
	}
	
	/**
	 * Получить список подписчиков указанного блога
	 *
	 * @param	oBlog 
	 * @return array
	 */
	public function GetBlogSubscribersUids($oBlog) {
		$sql = '
				SELECT 
					DISTINCT bu.user_id
				FROM '.Config::Get('db.table.blog_user').' as bu
				WHERE 
					bu.blog_id = ?d AND
					bu.user_settings_notice_new_topic_subscribe = 1 AND
					(bu.user_role = ? OR bu.user_role = ? OR bu.user_role = ?)
					';
		if (
			$aRows=$this->oDb->select($sql,
				$oBlog->getId(),
				ModuleBlog::BLOG_USER_ROLE_USER,
				ModuleBlog::BLOG_USER_ROLE_MODERATOR,
				ModuleBlog::BLOG_USER_ROLE_ADMINISTRATOR
			)
		) {
			foreach($aRows as $row) {
				$aResult[] = $row['user_id'];
			}
			return $aResult;
		}
		return null;
	}
	
	/**
	 * Получить список подписчиков указанного блога подписавшихся на уведомления о комментах
	 *
	 * @param	oBlog 
	 * @return array
	 */
	public function GetBlogCommentSubscribersUids($oBlog) {
		$sql = '
				SELECT 
					DISTINCT bu.user_id
				FROM '.Config::Get('db.table.blog_user').' as bu,
					'.Config::Get('db.table.user').' as u
				WHERE 
					u.user_id = bu.user_id AND
					bu.blog_id = ?d AND
					'. (Config::Get('plugin.notification.oneSettingForBlogsComments') ? 
						'u.user_settings_notice_new_comment_blogs_subscribe = 1' :
						'bu.user_settings_notice_new_comment_subscribe = 1') .'
					AND
					(bu.user_role = ? OR bu.user_role = ? OR bu.user_role = ?)
					';
		if (
			$aRows=$this->oDb->select($sql,
				$oBlog->getId(),
				ModuleBlog::BLOG_USER_ROLE_USER,
				ModuleBlog::BLOG_USER_ROLE_MODERATOR,
				ModuleBlog::BLOG_USER_ROLE_ADMINISTRATOR
			)
		) {
			foreach($aRows as $row) {
				$aResult[] = $row['user_id'];
			}
			return $aResult;
		}
		return null;
	}
	
	/**
	 * Получить список подписчиков указанного блога подписавшихся на уведомления о новых членах
	 *
	 * @param	oBlog
	 * @return array
	 */
	public function GetBlogNewUserSubscribersUids($oBlog) {
		$sql = '
				SELECT 
					DISTINCT bu.user_id
				FROM '.Config::Get('db.table.blog_user').' as bu,
					'.Config::Get('db.table.user').' as u
				WHERE 
					u.user_id = bu.user_id AND
					bu.blog_id = ?d AND
					u.user_settings_notice_new_user_blogs_subscribe = 1
					AND
					(bu.user_role = ? OR bu.user_role = ? OR bu.user_role = ?)
					';
		if (
			$aRows=$this->oDb->select($sql,
				$oBlog->getId(),
				ModuleBlog::BLOG_USER_ROLE_USER,
				ModuleBlog::BLOG_USER_ROLE_MODERATOR,
				ModuleBlog::BLOG_USER_ROLE_ADMINISTRATOR
			)
		) {
			foreach($aRows as $row) {
				$aResult[] = $row['user_id'];
			}
			return $aResult;
		}
		return null;
	}
	
	/**
	* Получить список отложенных задач по логину и типу переодичности
	* 
	* @param	oUser
	* @return	array	массив oTask
	*/
	public function GetTasksByUser($oUser) {
		$sql = 'SELECT *
				FROM '.Config::Get('db.table.notify_task').' as nt
				WHERE
					nt.user_login = ? AND nt.user_mail = ? AND nt.notify_freq_type = ?d';
		$aTasks=array();
		if ($aRows=$this->oDb->select($sql,
				$oUser->getUserLogin(),
				$oUser->getUserMail(),
				$oUser->getSettingsNoticeFrequency()
			)
		) {
			foreach ($aRows as $aTask) {
				$aTasks[]=Engine::GetEntity('Notify_Task',$aTask);
			}
		}		
		return $aTasks;
	}
	
	/**
	* Получить список отложенных задач по типу переодичности
	* 
	* @param	string
	* @param	int
	* @return	array	массив oTask
	*/
	public function GetTasksByFreq($sFreqType, $iLimit) {
		$sql = 'SELECT *
				FROM '.Config::Get('db.table.notify_task').' as nt
				WHERE
					nt.notify_freq_type = ?d
				ORDER BY date_created ASC
				LIMIT ?d';
		$aTasks=array();
		if ($aRows=$this->oDb->select($sql,
				$oUser->getSettingsNoticeFrequency(),
				$iLimit
			)
		) {
			foreach ($aRows as $aTask) {
				$aTasks[]=Engine::GetEntity('Notify_Task',$aTask);
			}
		}		
		return $aTasks;
	}
	
	public function GetDailyTasks($iLimit) {
		return $this->GetTasksByFreq(PluginNotification_ModuleNotify::FREQUENCY_DAILY, $iLimit);
	}
	
	public function GetWeeklyTasks($iLimit) {
		return $this->GetTasksByFreq(PluginNotification_ModuleNotify::FREQUENCY_WEEKLY, $iLimit);
	}
	
	public function UpdateTask(ModuleNotify_EntityTask $oNotifyTask) {
		$sql = '
			UPDATE '.Config::Get('db.table.notify_task').'
			SET
				user_login = ?,
				user_mail = ?,
				notify_subject = ?,
				notify_text = ?,
				date_created = ?,
				notify_task_status = ?,
				notify_freq_type = ?
			WHERE
				notify_task_id = ?d
		';
				
		if ($this->oDb->query(
			$sql,
			$oNotifyTask->getUserLogin(),
			$oNotifyTask->getUserMail(),
			$oNotifyTask->getNotifySubject(),
			$oNotifyTask->getNotifyText(),
			$oNotifyTask->getDateCreated(),
			$oNotifyTask->getTaskStatus(),
			$oNotifyTask->getNotifyFreqType(),
			$oNotifyTask->getTaskId()
		)===0) {
			return true;
		}
		return false;
	}
	
	public function AddTask(ModuleNotify_EntityTask $oNotifyTask) {
		$sql = '
			INSERT INTO '.Config::Get('db.table.notify_task').' 
				( user_login, user_mail, notify_subject, notify_text, date_created, notify_task_status, notify_freq_type )
			VALUES
				( ?, ?, ?, ?, ?, ?d, ?d )
		';
				
		if ($this->oDb->query(
			$sql,
			$oNotifyTask->getUserLogin(),
			$oNotifyTask->getUserMail(),
			$oNotifyTask->getNotifySubject(),
			$oNotifyTask->getNotifyText(),
			$oNotifyTask->getDateCreated(),
			$oNotifyTask->getTaskStatus(),
			$oNotifyTask->getNotifyFreqType()
		)===0) {
			return true;
		}
		return false;
	}
	
	public function AddTaskArray($aTasks) {
		if(!is_array($aTasks)&&count($aTasks)==0) {
			return false;
		}
		
		$aValues=array();
		foreach ($aTasks as $oTask) {
			$aValues[]='('. implode(',', 
				array(
					$this->oDb->escape($oTask->getUserLogin()),
					$this->oDb->escape($oTask->getUserMail()),
					$this->oDb->escape($oTask->getNotifySubject()),
					$this->oDb->escape($oTask->getNotifyText()),
					$this->oDb->escape($oTask->getDateCreated()),
					$this->oDb->escape($oTask->getTaskStatus()),
					$this->oDb->escape($oTask->getNotifyFreqType())
				)
			).')';
		}
		$sql = '
			INSERT INTO '.Config::Get('db.table.notify_task').' 
				( user_login, user_mail, notify_subject, notify_text, date_created, notify_task_status, notify_freq_type )
			VALUES 
				'.implode(', ', $aValues);

		return $this->oDb->query($sql);
	}
	
	/**
	* Обновить время отправки последнего запроса для пользователя
	* 
	* @param	oUser
	*/
	public function UpdateRequestTime($oUser) {
		$sql = '
			UPDATE '.Config::Get('db.table.user').'
			SET
				user_settings_notice_request_last = ?d
			WHERE
				user_id = ?d
		';
		
		if($this->oDb->query($sql, time(), $oUser->getId())) {
			return true;
		}
		
		return false;
	}
	
	protected function getUserSubscribersFromDB($sUserId, $bTo = true) {
		$sUserDirection = $bTo ? 'from' : 'to';
		$sSubscribersDirection = $bTo ? 'to' : 'from';
		$sql = 'SELECT 
					DISTINCT uf.user_'. $sSubscribersDirection .'
				FROM 
					'.Config::Get('db.table.friend').' as uf,
					'.Config::Get('db.table.user').' as u
				WHERE
					uf.user_'. $sUserDirection .' = ?d
					AND
					uf.user_'. $sSubscribersDirection .' = u.user_id
					AND
					u.user_settings_notice_friend_news = 1
					AND
					uf.status_'. $sSubscribersDirection .' < ?d 
					';
		$aUsers=array();
		if ($aRows=$this->oDb->select(
				$sql,
				$sUserId,
				ModuleUser::USER_FRIEND_ACCEPT+ModuleUser::USER_FRIEND_OFFER
			)
		) {
			foreach ($aRows as $aUser) {
				$aUsers[]= $aUser['user_'.$sSubscribersDirection];
			}
		}
		return $aUsers;
	}
}
?>