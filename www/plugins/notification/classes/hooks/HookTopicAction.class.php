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
* ����������� �����
*/
class PluginNotification_HookTopicAction extends Hook {

	public function RegisterHook() {
		/**
		* ��� �������������� ����������� ��� ������ ������.
		*/
		$this->AddHook('topic_add_after', 'SendNotifyAboutNewTopic');
	}

	/**
	* ���������� ����������� ���� ���� ����� ����� �����
	?  1. ����������� ����������.
	*  2. �������(����������� ������), ���� ��� �������� ������ ����� � ������� ���� �������.
	* 
	* @param	array		array('oTopic', 'oBlog')
	*/
	public function SendNotifyAboutNewTopic($data) {
		$aExceptUserId = array(
			$data['oTopic']->getUserId(),
		);
		
		$aBlogSubscriberId = $this->Notify_SendNotificationsToBlogSubscribers($data['oTopic'], $data['oBlog'], $aExceptUserId);
		if(is_array($aBlogSubscriberId)) {
			$aExceptUserId = array_merge($aExceptUserId, $aBlogSubscriberId);
		}
		
		$this->Notify_SendNotificationsToAuthorFriends($data['oTopic'], $data['oBlog'], $aExceptUserId);
	}
}
?>