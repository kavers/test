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
 * Отправляет пользователю уведомления
 */
class PluginNotification_ActionNotification extends ActionPlugin {
	protected $oUserCurrent = null;
	/**
	 * Инициализация 
	 */
	public function Init() {
		$this->oUserCurrent=$this->User_GetUserCurrent();
		
		$this->SetDefaultEvent('request');
	}
	
	protected function RegisterEvent() {
		$this->AddEvent('request','EventAjaxRequest');
	}
	
	/**
	* Пробуем отправить уведомление пользователю
	* и рапортуем о результате
	*/
	protected function EventAjaxRequest() {
		$this->Viewer_SetResponseAjax('json');
		
		if(!$this->User_IsAuthorization()) {
			$this->Message_AddErrorSingle($this->Lang_Get('notification_request_msg_anonim'), $this->Lang_Get('notification_request_msgTitle_error'));
			
			return;
		}
		
		$sUserId = getRequest('userId');
		$oUser = $sUserId ? $this->User_GetUserById($sUserId) : null;
		
		if(!$oUser) {
			$this->Message_AddErrorSingle($this->Lang_Get('notification_request_msg_user_not_found'), $this->Lang_Get('notification_request_msgTitle_error'));
			
			return;
		}
		
		if(!$oUser->getSettingsNoticeRequest()) {
			$this->Message_AddNoticeSingle($this->Lang_Get('notification_request_msg_request_denied'), $this->Lang_Get('notification_request_msgTitle_error'));
			
			return;
		}
		
		$bNotificationResult = $this->Notify_SendRequestToUser($oUser, $this->oUserCurrent, array($this->oUserCurrent->getId()));
		
		if(!$bNotificationResult) {
			$this->Message_AddNoticeSingle($this->Lang_Get('notification_request_msg_request_not_accept'), $this->Lang_Get('notification_request_msgTitle_error'));
			
			return;
		}
		
		$this->Message_AddNoticeSingle($this->Lang_Get('notification_request_msg_request_success'),  $this->Lang_Get('notification_request_msgTitle_success'));
	}
}