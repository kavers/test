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
* Переопределим ряд методов, для возможности фильтрации уведомлений
*/
class PluginNotification_ModuleTopic extends PluginNotification_Inherit_ModuleTopic {
	/**
	* Отправляем уведомление всем кому имеет смысл знать
	*  1. Подписчикам сообщества, если включили опцию и хватает прав доступа.
	*  2. Друзьям(подписчикам автора), если они включили данную опцию и хватает прав доступа.
	*  3. Владельцу блога
	*
	* @param unknown_type $oBlog
	* @param unknown_type $oTopic
	* @param unknown_type $oUserTopic
	*/
	public function SendNotifyTopicNew($oBlog,$oTopic,$oUserTopic) {
		$aExceptUserId = array(
			$oTopic->getUserId(),
			$oBlog->getOwnerId()
		);
		
		$aBlogSubscriberId = $this->Notify_SendNotificationsToBlogSubscribers($oTopic, $oBlog, $aExceptUserId);
		if(is_array($aBlogSubscriberId)) {
			$aExceptUserId = array_merge($aExceptUserId, $aBlogSubscriberId);
		}
		
		$this->Notify_SendNotificationsToAuthorFriends($oTopic, $oBlog, $aExceptUserId);
		
		//отправляем создателю блога
		if ($oBlog->getOwnerId()!=$oUserTopic->getId()) {
			$this->Notify_SendTopicNewToSubscribeBlog($oBlog->getOwner(),$oTopic,$oBlog,$oUserTopic);
		}
	}
}
?>