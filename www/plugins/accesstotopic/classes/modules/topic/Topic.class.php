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
* ������������� ��� �������, ��� ����������� ���������� �������
* �������� ������ ������� ������������.
*/
class PluginAccesstotopic_ModuleTopic extends PluginAccesstotopic_Inherit_ModuleTopic {
	//��������� ���������� ������������
	const ANONIM_USER_ID = 0;
	
	/**
	* ��������� �� ��������������� Topic.mapper
	*/
	public function GetTopicsRatingByDate($sDate,$iLimit=20) {
		$oCurrentUser = $this->getCurrentUserObject();
		if($oCurrentUser->isAdministrator()) return parent::GetTopicsRatingByDate($sDate, $iLimit);
		/**
		 * �������� ������ ������, ������ ������� ����� ��������� �� ������
		 */
		$aCloseBlogs = ($this->oUserCurrent)
			? $this->Blog_GetInaccessibleBlogsByUser($this->oUserCurrent)
			: $this->Blog_GetInaccessibleBlogsByUser();	
		
		$s=serialize($aCloseBlogs);
		
		if (false === ($data = $this->Cache_Get("topic_rating_{$sDate}_{$iLimit}_{$oCurrentUser->getId()}_{$s}"))) {
			$data = $this->oMapperTopic->GetTopicsRatingByDate($sDate,$iLimit,$aCloseBlogs, $oCurrentUser->getId());
			$this->Cache_Set($data, "topic_rating_{$sDate}_{$iLimit}_{$oCurrentUser->getId()}_{$s}", array('topic_update'), 60*60*24*2);
		}
		$data=$this->GetTopicsAdditionalData($data);
		return $data;
	}
	
	public function GetTopicsByFilter($aFilter,$iPage=0,$iPerPage=0,$aAllowData=array('user'=>array(),'blog'=>array('owner'=>array(),'relation_user'),'vote','favourite','comment_new')) {
		$oCurrentUser = $this->getCurrentUserObject();
		if($oCurrentUser->isAdministrator()) return parent::GetTopicsByFilter($aFilter,$iPage,$iPerPage,$aAllowData);
		
		$s=serialize($aFilter);
		if (false === ($data = $this->Cache_Get("topic_filter_{$s}_{$iPage}_{$iPerPage}_{$oCurrentUser->getId()}"))) {
			$data = ($iPage*$iPerPage!=0) 
				? array(
						'collection'=>$this->oMapperTopic->GetTopics($aFilter,$iCount,$iPage,$iPerPage, $oCurrentUser->getId()),
						'count'=>$iCount
					)
				: array(
						'collection'=>$this->oMapperTopic->GetAllTopics($aFilter, $oCurrentUser->getId()),
						'count'=>$this->GetCountTopicsByFilter($aFilter)
					);
			$this->Cache_Set($data, "topic_filter_{$s}_{$iPage}_{$iPerPage}_{$oCurrentUser->getId()}", array('topic_update','topic_new'), 60*60*24*3);
		}
		$data['collection']=$this->GetTopicsAdditionalData($data['collection'],$aAllowData);
		return $data;
	}
	
	public function GetCountTopicsByFilter($aFilter) {
		$oCurrentUser = $this->getCurrentUserObject();
		if($oCurrentUser->isAdministrator()) return parent::GetCountTopicsByFilter($aFilter);
		
		$s=serialize($aFilter);
		if (false === ($data = $this->Cache_Get("topic_count_{$s}_{$oCurrentUser->getId()}"))) {
			$data = $this->oMapperTopic->GetCountTopics($aFilter, $oCurrentUser->getId());
			$this->Cache_Set($data, "topic_count_{$s}_{$oCurrentUser->getId()}", array('topic_update','topic_new'), 60*60*24*1);
		}
		return 	$data;
	}
	
	public function GetTopicsByArrayId($aTopicId) {
		$oCurrentUser = $this->getCurrentUserObject();
		$aTopics = parent::GetTopicsByArrayId($aTopicId);
		$aTopics = $oCurrentUser->isAdministrator() ? $aTopics : $this->deleteTopicsWithoutNeccessaryAccess($aTopics);
		return $aTopics;
	}
	
	public function GetTopicsByArrayIdSolid($aTopicId) {
		$oCurrentUser = $this->getCurrentUserObject();
		$aTopics = parent::GetTopicsByArrayIdSolid($aTopicId);
		
		$aTopics = $oCurrentUser->isAdministrator() ? $aTopics : $this->deleteTopicsWithoutNeccessaryAccess($aTopics);
		return $aTopics;
	}
	
	/**
	* �������� ������ �������� ������������, ��� ������� ������ � user_id = 0 ��� �������
	* 
	* @return	object		������-�������� ������������
	*/
	protected function getCurrentUserObject() {
		//��������� �������� ��������� �� ������������ � �������
		if(!$this->User_IsAuthorization()) {
			$oUserCurrent = Engine::GetEntity('User_User', array('user_id' => self::ANONIM_USER_ID, 'user_is_administrator' => false));
		} else {
			$oUserCurrent = $this->User_GetUserCurrent();
		}
		
		return $oUserCurrent;
	}
	
	/**
	* ������� �� ���������� ������� ��, � ������� ������� ������������ �� ������ �������� ������.
	* @param	array		������������ ������ ����������� ������� � ����������.
	*/
	protected function deleteTopicsWithoutNeccessaryAccess($aTopics) {
		if(!is_array($aTopics)) {
			return $aTopics;
		}
		
		$oUserCurrent = $this->getCurrentUserObject();
		
		//��� ������ ������� �����������
		if($oUserCurrent->isAdministrator()) {
			return $aTopics;
		}
		
		//������� ��� ������ � ������� � ��������� ������� ��������� �� ������ �� ���.
		$aNewResult = array();
		foreach($aTopics as $oTopic) {
			//echo('Title ' . $oTopic->getTitle() . ' Level ' . $oTopic->getAccessLevel() .' | '. $this->CheckAccessToTopic($oUserCurrent, $oTopic));
			if($this->CheckAccessToTopic($oUserCurrent, $oTopic)) $aNewResult[$oTopic->getId()] = $oTopic;
		}
		return $aNewResult;
	}
	
	/**
	* ���������, ���� �� � ������������ ������ � ������
	* 
	* @param	object		����������� ������������
	* @param	object		����������� �����
	* @return	boolean		�������. true, ���� ������ ����.
	*/
	public function CheckAccessToTopic($oUser, $oTopic) {
		//������������ ������ ����� ������ � ����������� ������� � ������ �����
		if($oUser->getId() == $oTopic->getUserId()) {
			return true;
		}
		
		
		switch($oTopic->getAccessLevel()) {
			case Config::Get('plugin.accesstotopic.personalBlog.accessLevels.FOR_ALL'):
				return true;
			case Config::Get('plugin.accesstotopic.personalBlog.accessLevels.FOR_REGISTERED'):
				if(!$this->isUserAnon($oUser)) {
					return true;
				}
				break;
			case Config::Get('plugin.accesstotopic.personalBlog.accessLevels.FOR_FRIENDS'):
				if($oFriend = $this->User_GetFriend($oTopic->getUserId(), $oUser->getId())) {
					if($oFriend->getStatusByUserId($oTopic->getUserId()) < ModuleUser::USER_FRIEND_OFFER + ModuleUser::USER_FRIEND_ACCEPT && $oFriend->getStatusByUserId($oTopic->getUserId()) > 0) {
						return true;
					}
				}
				break;
			case Config::Get('plugin.accesstotopic.personalBlog.accessLevels.FOR_TWOSIDE_FRIENDS'):
				if($oFriend = $this->User_GetFriend($oTopic->getUserId(), $oUser->getId())) {
					if($oFriend->getFriendStatus() == ModuleUser::USER_FRIEND_OFFER + ModuleUser::USER_FRIEND_ACCEPT 
						||
						$oFriend->getFriendStatus() == ModuleUser::USER_FRIEND_ACCEPT + ModuleUser::USER_FRIEND_ACCEPT 
						) 
					{
						return true;
					}
				}
				break;
			case Config::Get('plugin.accesstotopic.personalBlog.accessLevels.FOR_OWNER_ONLY'):
				//������� �������� �� ����������, ��� ��� ����� ������ �� ��� ���� � ������ ������
				return false;
			case Config::Get('plugin.accesstotopic.collectiveBlog.accessLevels.FOR_ALL'):
				return true;
			case Config::Get('plugin.accesstotopic.collectiveBlog.accessLevels.FOR_REGISTERED'):
				if(!$this->isUserAnon($oUser)) {
					return true;
				}
				break;
			case Config::Get('plugin.accesstotopic.collectiveBlog.accessLevels.FOR_COLLECTIVE'):
				$aBlogUsers = $this->Blog_GetBlogUsersByBlogId($oTopic->getBlogId());
				foreach($aBlogUsers as $oBlogUser) {
					if($oBlogUser->getUserId() == $oUser->getId()) {
						return true;
					}
				}
				break;
		}
		return false;
	}
	
	/**
	* ��������� ��������� �� ������������.
	* ����� �� ��������� ����� ModuleUser_EntityUser, ���� �� ������������� ���������
	* ��������� ��-�� ���������� ������� � �������� ���������� ������������.
	* 
	* @param	object		������-�������� ������������
	* @return	boolean		true, ���� ������� ������ ���������� ������������.
	*/
	protected function isUserAnon($oUser) {
		return $oUser->getId() == self::ANONIM_USER_ID ? true : false;
	}
}
?>