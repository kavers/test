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


class PluginUsercats_ActionPeople extends PluginUsercats_Inherit_ActionPeople {
	//��� ����������� ����� ���� /people/cat1/cat2/.../catN
	protected function RegisterEvent() {
		parent::RegisterEvent();
		$this->AddEventPreg('/^[_a-zA-Z][_a-zA-Z0-9]*$/i','EventShowCatUsers');
	}
	
	//���������� ����� ����������� ���������
	protected function EventShowCatUsers() {
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
		
		//��������� ������������� ���������� �� ��������� ������ ������ cat
		$sFullBlogCatName = '';
		$sFullUserCatName = '';
		if(($iCatPosition = array_search('cat', $aParam)) !== false) {
			$aUserCats = array_slice($aParam, 0, $iCatPosition);
			$aBlogCats = array_slice($aParam, $iCatPosition + 1);
			$sFullBlogCatName = strtoupper(implode(':', $aBlogCats));
		} else {
			$aUserCats = $aParam;
		}
		$sFullUserCatName = strtoupper(implode(':', $aUserCats));

		if($sFullUserCatName && !$this->PluginUsercats_ModuleCategory_IsFullCategoryExist($sFullUserCatName)) {
			return parent::EventNotFound();
		}
		
		if($sFullBlogCatName && !$this->PluginCommunitycats_ModuleCategory_IsFullCategoryExist($sFullBlogCatName)) {
			return parent::EventNotFound();
		}
		
		if(!$sFullBlogCatName && !$sFullUserCatName) {
			return Router::Action('people','good');
		}
		

		//�������� ������ �������������
		$aFilters = array();
		if($sFullUserCatName) {
			$aFilters['aUserFilter'] = array(
				'beginLike' => array('user_cat' => $sFullUserCatName)
			);
		}
		
		if($sFullBlogCatName) {
			$aFilters['aBlogFilter'] = array(
				'beginLike' => array('blog_cat' => $sFullBlogCatName),
				'eq' => array('blog_type' => 'personal')
			);
		}
		
		$aUsers = $this->PluginUsercats_ModuleCategory_GetUsersByFilters(
			$aFilters,
			array(),
			array(
				'iPage' => $iPage,
				'iElementsPerPage' => Config::Get('module.user.per_page')
			),
			false
		);
		$iCountUsers = $this->PluginUsercats_ModuleCategory_GetCountUsersByFilters($aFilters);
		
		//��������� ��������������
		$aPaging = $this->Viewer_MakePaging($iCountUsers,$iPage,Config::Get('module.user.per_page'),4,Router::GetPath('people') . implode('/',$aParam));
		//��������� ���������� � ������
		$this->Viewer_Assign('aPath', $aParam);
		$this->Viewer_Assign('aPaging',$aPaging);
		$this->Viewer_Assign('aUsersRating',$aUsers);
		$this->GetStats();
		//������������� ������ ������
		$this->SetTemplateAction('index');
	}
}

?>