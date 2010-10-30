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
class PluginTopicfix_HookTopicfix extends Hook {
	public function RegisterHook() {
		//��������� �������������� ���� � ������� oTopic
		$this->AddHook('topic_add_before','AddValuesToObject');
		$this->AddHook('topic_edit_before','AddValuesToObject');
		//��� ��� ���������� ������ � ����� �������������� ������
		$this->AddHook('topic_edit_show', 'AddFixedStatusToShowForm');
		
		//��� ��� ������� HTML ����
		$this->AddHook('template_html_pluginTopicfix_form', 'AddTopicfixForm');
		$this->AddHook('template_html_pluginTopicfix_show', 'AddTopicfixShow');
	}
	
	public function AddValuesToObject($data) {
		$data['oTopic']->setFixedStatus(0);
		if($this->checkUserRights($data['oBlog'])) {
			$data['oTopic']->setFixedStatus(getRequest('topic_fixed') ? 1 : 0);
		}
	}
	
	public function AddFixedStatusToShowForm($data) {
		if(!isset($_REQUEST['submit_topic_publish']) && !isset($_REQUEST['submit_topic_save'])) {
			$_REQUEST['topic_fixed'] = $data['oTopic']->getFixedStatus();
		}
	}
	
	//��������� ���� � ��������/�������������� ������
	public function AddTopicfixForm() {
		//�������� ������������
		$oUserCurrent=PluginLib_ModuleUser::GetUserCurrent();
		//�������� ��������� ����-����. � ������ ������������ � ������ �� ���� ������.
		$aUserBlog=$this->ModuleBlog_GetBlogUsersByUserId($oUserCurrent->getId());
		$aBlogRole=array();
		foreach($aUserBlog as $iBlogId=>$oBlogUser) {
			$aBlogRole[$iBlogId]=$oBlogUser->getUserRole();
		}
		//�������� ������ ���� ������, � ������� ������������ �������� ����������
		$aBlogOwner=$this->ModuleBlog_GetBlogsByOwnerId($oUserCurrent->getId(),true);
		//�������� �� ������������� �����
		$oPersonalBlog=$this->ModuleBlog_GetPersonalBlogByUserId($oUserCurrent->getId());
		$aBlogOwner[]=$oPersonalBlog->getBlogId();
		$aBlogsRights=$this->prepairBlogRightsArray($aBlogOwner, $aBlogRole);
		//��������� ���������� � ������
		$this->Viewer_Assign('aBlogsRights',$aBlogsRights);
		$this->Viewer_Assign('oUserCurrent',$oUserCurrent);
		return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__).'topicfix_form.tpl');
	}
	
	//������� ������������ ������
	public function AddTopicfixShow() {
		return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__).'topicfix_show.tpl');
	}
	
	//�������� ������������� ������ BlogId=>�����_��_�����������_������
	private function prepairBlogRightsArray($aBlogOwner, $aBlogRole) {
		foreach($aBlogRole as $iBlogId=>$iRole) {
			//���� ������������ ������������� ����� �� ��������� ����������� ������
			if($iRole==4) {
				$aBlogsRights[$iBlogId]=1;
			} else {
				$aBlogsRights[$iBlogId]=0;
			}
		}
		//��� ������� ����� ���������� �������� �������� ������������ - ���������
		foreach($aBlogOwner as $iBlogId=>$iPermission) {
			$aBlogsRights[$iBlogId]=1;
		}
		return $aBlogsRights;
	}
	
	//��������� ����� �� ����������� ������
	private function checkUserRights($oBlog) {
		//�������� �������� �����
		$oUserCurrent=PluginLib_ModuleUser::GetUserCurrent();
		//�������� ����� ����� ���������� ������
		if(($oBlog->getOwnerId()) == ($oUserCurrent->getId())) return true;
		//������������� ����� ���������� ������
		if($oUserCurrent->isAdministrator()) return true;
		//������������� ����� ����� ���������� ������
		$aUsersObjs=$this->ModuleBlog_GetBlogUsersByBlogId($oBlog->getId());
		if(isset($aUsersObjs[$oUserCurrent->getId()])) {
			$oUserBlog=$aUsersObjs[$oUserCurrent->getId()];
			if($oUserBlog->getIsAdministrator()) return true;
		}
		//� ��������� ������ ������������ �� ����� ���������� ������
		return false;
	}
}
?>