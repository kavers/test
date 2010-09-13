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


class PluginSendtofriend_ActionSendtofriend extends ActionPlugin {
	
	public function Init() {
		$this->SetDefaultEvent('output');
	}
	
	protected function RegisterEvent() {
		$this->AddEvent('output','ajaxSendMessage');
	}
	
	protected function ajaxSendMessage() {
		$this->Viewer_SetResponseAjax('json');
		if(!$this->User_IsAuthorization()) {
			return Router::Action('error');
		}
		$iTopicId = (int)getRequest('topicId');
		$sEmail = trim((string)getRequest('email'));
		$sMessage = htmlspecialchars(getRequest('message'));
		if(preg_match('/^[a-z0-9](?:[-._a-z]+[a-z0-9])?@[a-z0-9][-_a-z]+[a-z0-9](?:\.[a-z0-9][-_a-z0-9]+[a-z0-9])?\.[a-z]{2,6}$/i',$sEmail)) {
			if($this->PluginSendtofriend_ModuleSendtofriend_SendMessage($sEmail, $sMessage, $iTopicId)) {
				$this->Message_AddNoticeSingle($this->Lang_Get('sendtofriend_msg_success'), $this->Lang_Get('sendtofriend_msgTitle_success'));
			
				return;
			}
		}
		
		$this->Message_AddErrorSingle($this->Lang_Get('sendtofriend_msg_error'), $this->Lang_Get('sendtofriend_msgTitle_error'));
	}
}

?>