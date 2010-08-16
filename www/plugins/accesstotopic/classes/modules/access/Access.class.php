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
* ������� ��� �������� ������ ������� ������������ � ������.
* ������ ��������� � Livestreet ���, ������� ��������� �������.
* 
* ������ �������
* 'none' - ������� ���,
* 'read' - ����� ������ ����� � �����������
* 'comment' - ����� ��������� �����������
*/

class PluginAccesstotopic_ModuleAccess extends Module {
	//��������� ������ �������
	const NONE = 'none';
	const READ = 'read';
	const COMMENT = 'comment';
	
	public function Init() { }
	
	/**
	* �������� ������ ������� � ������.
	* ���� ��� ������ ����� �� ������/���������������
	* 
	* 
	* @param	oUser		������������/����� ���� null ��� �������
	* @param	oTopic		�����
	* @return	array		������ �������
	*/
	public function GetUserAccessLevel($oUser, $oTopic) {
		if($oUser->isAdministrator()) {
			return array(self::READ, self::COMMENT);
		}
		
		$aAccessList = array();
		//���������, ���� �� � ������������ ������ � ����� � ������
		if(!$this->canRead($oTopic, $oUser)) {
			$aAccessList[] = self::NONE;
			return $aAccessList;
		}
		
		$aAccessList[] = self::READ;
		
		//��������� ����������� ��������������
		if($this->canComment($oTopic, $oUser)) {
			$aAccessList[] = self::COMMENT;
		}
		
		return $aAccessList;
	}
	
	/**
	* ��������� �������������� ������� ������� ������������ � ������
	* 
	* @param	oUser			������������/����� ���� null ��� �������
	* @param	oTopic			�����
	* @param	string			����������� ������� �������
	* @return	boolean|null	���� ������ �� ���������� - null
	*/
	public function CheckUserAccess($oUser, $oTopic, $sLevel) {
		if($oUser->isAdministrator()) {
			return true;
		}
		
		switch(strtolower($sLevel)) {
			case self::NONE:
				return $this->canNothing($oTopic, $oUser);
			case self::READ:
				return $this->canRead($oTopic, $oUser);
			case self::COMMENT:
				return $this->canComment($oTopic, $oUser);
		}
		
		return null;
	}
	
	protected function canComment($oTopic, $oUser) {
		return !$oTopic->getForbidComment() && $this->ACL_CanPostComment($oUser) && $this->ACL_CanPostCommentTime($oUser);
	}
	
	protected function canRead($oTopic, $oUser) {
		return !$this->inNotAccessibleBlog($oTopic, $oUser) && !$this->notAccessibleTopic($oTopic, $oUser);
	}
	
	protected function canNothing($oTopic, $oUser) {
		return !$this->canComment($oTopic, $oUser) && !$this->canRead($oTopic, $oUser);
	}
	
	protected function inNotAccessibleBlog($oTopic, $oUser) {
		$aInaccessibleBlog = $this->Blog_GetInaccessibleBlogsByUser($oUser);
		foreach($aInaccessibleBlog as $iBlogId) {
			if($iBlogId == $oTopic->getBlogId()) {
				return true;
			}
		}
		
		return false;
	}
	
	protected function notAccessibleTopic($oTopic, $oUser) {
		//�� ��� ������ ���� ������� �������� ���� ������ �� ������� � ���� �������� �� �������� �� ������.
		return !$this->Topic_CheckAccessToTopic($oUser, $oTopic);
	}
}

?>