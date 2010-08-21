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
* Регистрация хуков
*/
class PluginNotification_HookBlogAction extends Hook {

	public function RegisterHook() {
		/**
		* Хук дополнительные уведомления для комментариев.
		*/
		$this->AddHook('comment_add_after', 'SendNotifyForCommentedTopic');
	}

	/**
	* По-умолчанию уведомления о комментариях отправляется только автору топика
	* Здесь отправляем уведомления всем, кто комментировал топик и включил функции их доставки.
	* 
	* Первыми остальных комментаторов топика
	* Потом различным подписчикам на новости этого топика.
	* 
	*
	* Автору топика и автору комментария уведомления не высылаются. Для автора комментария это 
	* лишено смысла, а автор топика получит уведомление встроенными средствами LiveStreet.
	* 
	* @param	array		array('oCommentNew','oCommentParent','oTopic')
	*/
	public function SendNotifyForCommentedTopic($data) {
		$aExceptUserId = array(
			$data['oTopic']->getUserId(),
			$data['oCommentNew']->getUserId()
		);
		
		if($data['oCommentParent']) {
			$oParentCommentator = $this->User_GetUserById($data['oCommentParent']->getUserId());
			//Если автор родительского комментария, включил уведомления для комментов к его 
			//комментарию, то ему будет выслано уведомление стандартными средствами
			if($oParentCommentator->getSettingsNoticeReplyComment()) {
				$aExceptUserId[]=$oParentCommentator->getId();
			}
		}
		
		$aCommentatorId = $this->Notify_SendNotificationsToTopicCommentators($data['oTopic'], $data['oCommentNew'], $data['oCommentParent'], $aExceptUserId);
		if(is_array($aCommentatorId)) {
			$aExceptUserId = array_merge($aExceptUserId, $aCommentatorId);
		}
		
		$this->Notify_SendNotificationsToBlogCommentSubscribers($data['oTopic'], $data['oCommentNew'], $data['oCommentParent'], $aExceptUserId);
	}
}
?>