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
		if (!$this->User_IsAuthorization()) {
			$this->Message_AddErrorSingle($this->Lang_Get('not_access'));
			return Router::Action('error'); 
		}

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
		$this->Viewer_SetResponseAjax();
		
		$sUserId = getRequest('userId');
		$oUser = $sUserId ? $this->User_GetUserById($sUserId) : null;
		$bNotificationResult = false;
		if($oUser) {
			$bNotificationResult = $this->Notify_SendRequestToUser($oUser, $this->oUserCurrent, array($this->oUserCurrent->getId()));
		}
		
		$oViewerLocal=$this->Viewer_GetLocalViewer();
		$oViewerLocal->Assign('bRequestResult',$bNotificationResult);
		$oViewerLocal->Assign('sDebug',$sDebug);
		$this->Viewer_AssignAjax('sRequestResultText',$oViewerLocal->Fetch($this->getTemplatePathPlugin()."/actions/ActionNotification/ajax/request_result.tpl", 'notification'));
	}
}