<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright � 2008 Mzhelskiy Maxim
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
* ������ �������������� �������� ����� ������������� �� ������������ ������
*/
class PluginSendtofriend_ModuleSendtofriend extends Module {
	public function Init() {}
	
	/**
	* ������� ��������� ������ �� ����c�
	* 
	* @param	string		�����
	* @param	string		����� ���������
	* @param	int			id ������
	* @return	bool
	*/
	public function SendMessage($sEmail, $sMessage, $iTopicId) {
		$oTopic = $this->Topic_GetTopicById($iTopicId);
		if($oTopic) {
			$this->Notify_Send(
				$sEmail,
				'sendtofriend.topic.tpl',
				$this->Lang_Get('sendtofriend_email_subject'),
				array(
					'oUserFrom' => $this->User_GetUserCurrent(),
					'oTopic' => $oTopic,
					'oBlog' => $oTopic->getBlog(),
					'sMessage' => $sMessage
				),
				'sendtofriend'
			);
			
			return true;
		}
		
		return false;
	}
}
?>