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
* ����� ��������� ����������� ������ ��� ���������������� �������� ���������,
* � �� ������������� ������ ������� ��������.
* ������� ����� ������� ���������� �� �����.
*/
class PluginNotification_ModuleUser extends PluginNotification_Inherit_ModuleUser {
	public function UpdateFriend(ModuleUser_EntityFriend $oFriend) {
		$this->Notify_SendNotificationsAboutNewFriends($oFriend, array());
		return parent::UpdateFriend($oFriend);
	}
}

?>