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
	* Первым проверяем автора родительского комментария.
	* Вторыми остальных комментаторов топика
	* Последними различным подписчикам на новости этого топика, 
	* если они не вошли в первые две группы.
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
			$aParentCommentatorId = $this->Notify_SendNotificationsToParentCommentator($data['oTopic'], $data['oCommentNew'], $data['oCommentParent'], $aExceptUserId);
			if(is_array($aParentCommentatorId)) {
				$aExceptUserId = array_merge($aExceptUserId, $aParentCommentatorId);
			}
		}
		
		$this->Notify_SendNotificationsToTopicCommentators($data['oTopic'], $data['oCommentNew'], $data['oCommentParent'], $aExceptUserId);
	}
}
?>