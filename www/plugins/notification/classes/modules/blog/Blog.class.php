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

class PluginNotification_ModuleBlog extends PluginNotification_Inherit_ModuleBlog {
	/**
	* ������������� ��������� ����������� � ����� ��������� ���������� (���� ���������� ������� � ����)
	* ���� �����������, ���������� ������ ������.
	* 
	* @param ModuleBlog_EntityBlogUser $oBlogUser
	* @return unknown
	*/
	public function UpdateRelationBlogUser(ModuleBlog_EntityBlogUser $oBlogUser) {
		$oBlogUserOld = $this->Blog_GetBlogUserByBlogIdAndUserId($oBlogUser->getBlogId(), $oBlogUser->getUserId());
		if($oBlogUserOld) {
			//���������� ���������� ������ ����� ������������(��� ����������� �����������) ���������� ����������.
			if($oBlogUser->getUserRole() == ModuleBlog::BLOG_USER_ROLE_USER && 
				($oBlogUserOld->getUserRole() == ModuleBlog::BLOG_USER_ROLE_INVITE || $oBlogUserOld->getUserRole() == ModuleBlog::BLOG_USER_ROLE_REJECT)
			) {
				$this->Notify_SendNewBlogUser($oBlogUser, array($oBlogUser->getUserId()));
			}
		}
		
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("blog_relation_change_{$oBlogUser->getUserId()}","blog_relation_change_blog_{$oBlogUser->getBlogId()}"));		
		$this->Cache_Delete("blog_relation_user_{$oBlogUser->getBlogId()}_{$oBlogUser->getUserId()}");
		
		return $this->oMapperBlog->UpdateRelationBlogUser($oBlogUser);
	}
	
	/**
	 * ��������� ��������� ����� � �����, �� ���� ������������ � �����
	 *
	 * @param ModuleBlog_EntityBlogUser $oBlogUser
	 * @return unknown
	 */
	public function AddRelationBlogUser(ModuleBlog_EntityBlogUser $oBlogUser) {
		if ($this->oMapperBlog->AddRelationBlogUser($oBlogUser)) {
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("blog_relation_change_{$oBlogUser->getUserId()}","blog_relation_change_blog_{$oBlogUser->getBlogId()}"));
			$this->Cache_Delete("blog_relation_user_{$oBlogUser->getBlogId()}_{$oBlogUser->getUserId()}");
			
			//���� �������������� ������������ ��� ����������� ��������� �����������
			if($oBlogUser->getUserRole() == ModuleBlog::BLOG_USER_ROLE_USER) {
				$this->Notify_SendNewBlogUser($oBlogUser, array($oBlogUser->getUserId()));
			}
			return true;
		}
		return false;
	}
}

?>