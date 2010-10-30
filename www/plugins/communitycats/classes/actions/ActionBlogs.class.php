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


class PluginCommunitycats_ActionBlogs extends PluginCommunitycats_Inherit_ActionBlogs {
	//��� ����������� ����� ���� /blogs/cat1/cat2/.../catN
	protected function RegisterEvent() {
		parent::RegisterEvent();
		$this->AddEventPreg('/^[_a-zA-Z][_a-zA-Z0-9]*$/i','EventShowCatBlogs');
	}
	
	//���������� ����� ����������� ���������
	protected function EventShowCatBlogs() {
		//�������� ��������� �� �������� ������
		$sFirstCat = $this->sCurrentEvent; //������ ��������� ������������ ����� ������
		$aParam = $this->GetParams();
		//��������� �������� ����� ��������� ������� ��������, ���������
		$iPage = 1;
		if(isset($aParam[count($aParam) - 1])) {
			if(preg_match("/^page\d+$/i",$aParam[count($aParam) - 1])) {
				$sPage = array_pop($aParam);
				$iPage = (int)(substr($sPage,4));
			}
		}
		
		array_unshift($aParam, $sFirstCat);
		$sFullCatName = strtoupper(implode(':', $aParam));
		if(!$this->PluginCommunitycats_ModuleCategory_IsFullCategoryExist($sFullCatName)) {
			return parent::EventNotFound();
		}
		
		//�������� ������ ������
		$aFilter = array(
			'in' => array('blog_type' => array('open', 'close')),
			'beginLike' => array('blog_cat' => $sFullCatName)
		);
		$aBlogs = $this->PluginCommunitycats_ModuleCategory_GetBlogsByFilter($aFilter, array(), array('iPage' => $iPage, 'iElementsPerPage'=> Config::Get('module.blog.per_page')), false);
		$iCountBlogs = $this->PluginCommunitycats_ModuleCategory_GetCountBlogsByFilter($aFilter);
		//��������� ��������������
		$aPaging = $this->Viewer_MakePaging($iCountBlogs,$iPage,Config::Get('module.blog.per_page'),4,Router::GetPath('blogs') . implode('/',$aParam));
		//��������� ���������� � ������
		$this->Viewer_Assign('aPath', $aParam);
		$this->Viewer_Assign('aPaging',$aPaging);
		$this->Viewer_Assign('aBlogs',$aBlogs);
		$this->Viewer_AddHtmlTitle($this->Lang_Get('blog_menu_all_list'));
		//������������� ������ ������
		$this->SetTemplateAction('index');
	}
}

?>