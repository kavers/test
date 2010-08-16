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
	 * @param  string $sUserId
	 * @param  int    $iStatus
	 * @return array
	 */
	public function GetUserSubscribers($sUserId) {
		$aSubscribers = array_merge($this->getUserSubscribersFromDB($sUserId, true), $this->getUserSubscribersFromDB($sUserId, false));
		return array_unique($aSubscribers);
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