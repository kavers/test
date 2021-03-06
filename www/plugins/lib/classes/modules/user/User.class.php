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
* ��������������� ������� ��� ������ � ��������������
*/

class PluginLib_ModuleUser extends Module {
	//��������� ���������� ������������
	const ANONIM_USER_ID = 0;
	
	static protected $oUserCurrent = null;
	
	public function Init() { }
	
	/**
	* ���������� �������� ������������. ���� ������������ "������", �� ������ ��� ���� ������
	* � id = 0 � ���������� ���.
	* 
	* @return	oUser		��������� ��������
	*/
	static public function GetUserCurrent() {
		if(!self::$oUserCurrent) {
			$oEngine = Engine::getInstance();
			list($oModuleUser, $sModuleName, $sMethod) = $oEngine->GetModule('User_IsAuthorization');

			//��������� �������� ��������� �� ������������ � �������
			if(!$oModuleUser->IsAuthorization()) {
				//������ �������
				self::$oUserCurrent = Engine::GetEntity('User_User', array('user_id' => self::ANONIM_USER_ID, 'user_is_administrator' => false));
			} else {
				self::$oUserCurrent = $oModuleUser->GetUserCurrent();
			}
		}
		
		return self::$oUserCurrent;
	}
}

?>