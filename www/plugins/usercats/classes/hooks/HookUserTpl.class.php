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
class PluginUsercats_HookUserTpl extends Hook {
	protected $oUserEdit = null;
	protected $oBlogUserEdit = null;
	
	public function RegisterHook() {
		/**
		* ��� ��� ������� HTML ����
		*/
		//Select ������ ���������
		$this->AddHook('template_html_pluginUsercats_blog_form', 'AddSelectToChangeBlogCategory');
		$this->AddHook('template_html_pluginUsercats_user_form', 'AddSelectToChangeUserCategory');
		//���������� � ���������
		$this->AddHook('template_html_pluginUsercats_category', 'AddCategory');
		//���������� ��� bread crumbs
		$this->AddHook('template_html_pluginUsercats_bread_crumbs', 'AddBreadCrumbs');
		//������� ��������� �������������
		$this->AddHook('template_html_pluginUsercats_catalog', 'AddCatalog');
		//�������� ������������� �� ����������
		$this->AddHook('template_html_pluginUsercats_users_catalog', 'AddUsersCatalog');
		//�������� ������� ������������� �� ���������� �������������
		$this->AddHook('template_html_pluginUsercats_topics_catalog', 'AddTopicsCatalog');
	}

	/**
	* ������� HTML
	*/
	public function AddSelectToChangeUserCategory() {
		$sUserCatsOptions = $this->prepareOptionsForUserCatSelect(Config::Get('plugin.usercats.cats'));
		$this->Viewer_Assign('sUserCatsOptions', $sUserCatsOptions);
		return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__).'selectToChangeUserCategory.tpl');
	}
	
	public function AddSelectToChangeBlogCategory() {
		$sBlogCatsOptions = $this->prepareOptionsForBlogCatSelect(Config::Get('plugin.communitycats.cats'));
		$this->Viewer_Assign('sBlogCatsOptions', $sBlogCatsOptions);
		$this->Viewer_Assign('oUserBlog', $this->getBlogUserForEdit());
		return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__).'selectToChangeBlogCategory.tpl');
	}
	
	public function AddCategory() {
		return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__).'userCategory.tpl');
	}
	
	public function AddBreadCrumbs() {
		return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__).'breadCrumbs.tpl');
	}
	
	public function AddCatalog() {
		$aCats = $this->PluginUsercats_ModuleCategory_GetCategoriesInfo();
		$this->Viewer_Assign('aUserCats', $aCats);
		return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__).'userCatalog.tpl');
	}
	
	public function AddUsersCatalog($aData = array()) {
		if(!isset($aData['sType'])) {
			return '';
		} else {
			$sType = $aData['sType'];
		}
		$iCount = isset($aData['iCount']) ? (int)$aData['iCount'] : Config::Get('plugin.usercats.blockUserCount');
		
		if(!($sCatName = $this->PluginUsercats_ModuleCategory_GetFullCategoryName($sType))) {
			return '';
		}
		
		$aFilter['aUserFilter'] = array(
			'beginLike' => array('user_cat' => $sCatName),
		);
		$aOrder = array(
			'user_rating' => 'desc',
			'user_date_comment_last' => 'desc'
		);
		$aLimit = array(
			'iPage' => 1,
			'iElementsPerPage' => $iCount
		);
		$aUsersCat = $this->PluginUsercats_ModuleCategory_GetUsersByFilters($aFilter, $aOrder, $aLimit, false);
		$this->Viewer_Assign('aUsersCatatalog', $aUsersCat);
		
		$aCats = $this->PluginUsercats_ModuleCategory_GetCategoriesInfo();
		$this->Viewer_Assign('aUserCats', $aCats);
		
		$sFileName = str_replace(':', '_',strtolower($sType)) . 'Catalog.tpl';
		return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . $sFileName);
	}
	
	public function AddTopicsCatalog($aData = array()) {
		if(!isset($aData['sType'])) {
			return '';
		} else {
			$sType = $aData['sType'];
		}
		$iCount = isset($aData['iCount']) ? (int)$aData['iCount'] : Config::Get('plugin.usercats.blockTopicUserCount');
		
		if(!($sCatName = $this->PluginUsercats_ModuleCategory_GetFullCategoryName($sType))) {
			return '';
		}
		
		$aFilters['aUserFilter'] = array(
			'beginLike' => array('user_cat' => $sCatName),
		);
		$aFilters['aTopicFilter'] = array(
			'eq' => array('topic_type' => 'topic'),
		);

		$aOrder = array(
			'topic_date_add' => 'desc',
		);
		$aLimit = array(
			'iPage' => 1,
			'iElementsPerPage' => $iCount
		);
		$aTopicsUserCat = $this->PluginUsercats_ModuleCategory_GetTopicsByFilters($aFilters, $aOrder, $aLimit, false);
		$this->Viewer_Assign('aTopicsUserCatatalog', $aTopicsUserCat);
		
		$aCats = $this->PluginUsercats_ModuleCategory_GetCategoriesInfo();
		$this->Viewer_Assign('aUserCats', $aCats);
		
		$sFileName = str_replace(':', '_',strtolower($sType)) . 'TopicCatalog.tpl';
		return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . $sFileName);
	}

	
	/**
	* �������� ������ ������������, ��� ������� �� ������ ������ ��������� �� ���������
	* 
	* @return	oUser
	*/
	protected function getUserForEdit() {
		if(!$this->oUserEdit) {
			//�������� ��� ������������ �� �������� ������
			if(!($sUserLogin = Router::GetParam(1))) {
				return null;
			}
			
			$this->oUserEdit = $this->User_GetUserByLogin($sUserLogin);
		}
		
		return $this->oUserEdit;
	}
	
	/**
	* �������� ������ ����� ������������, ��� ������� �� ������ ������ ��������� �� ���������
	* 
	* @return	oUser
	*/
	protected function getBlogUserForEdit() {
		if(!$this->oBlogUserEdit) {
			if(!($oUser = $this->getUserForEdit())) {
				return null;
			}
			$this->oBlogUserEdit = $this->Blog_GetPersonalBlogByUserId($oUser->getId());
		}
		
		return $this->oBlogUserEdit;
	}

	
	/**
	* ����������� ������ ��������� ������������� � ������ options ��� select
	* 
	* @param	array		������ ��������� (��. config)
	* @param	integer		������� ����������� ��������� (��� ����������� ���������)
	* @return	string		������ ����� option ��� ������� � �������
	*/
	protected function prepareOptionsForUserCatSelect($aCats, $iLevel = 0) {
		if(!is_array($aCats)) {
			return '';
		}
		
		if(!($oUser = $this->getUserForEdit()));
		
		$sResult = '';
		
		foreach($aCats as $sCatName => $val) {
			$sCatLabel = str_repeat('&mdash;', $iLevel) . $this->Lang_Get('usercats_category_' . $sCatName, $sCatName);
			$sResult .= '
				<option value="' . $sCatName . '"' . ($oUser->getCategoryName() == $sCatName ? ' selected="selected"' : '') . '>' . $sCatLabel . '</option>';
		
			//�������� �� ��������� ����������� ��� ������ ���������
			if(is_array($val)) {
				$sResult .= $this->prepareOptionsForUserCatSelect($val, $iLevel + 1);
			}
		}
		
		return $sResult;
	}
	
	/**
	* ����������� ������ ��������� ������ � ������ options ��� select
	* 
	* @param	array		������ ��������� (��. config)
	* @param	integer		������� ����������� ��������� (��� ����������� ���������)
	* @return	string		������ ����� option ��� ������� � �������
	*/
	protected function prepareOptionsForBlogCatSelect($aCats, $iLevel = 0) {
		if(!is_array($aCats)) {
			return '';
		}
		
		if(!($oBlog = $this->getBlogUserForEdit())) {
			return '';
		}
		
		$sResult = '';
		
		foreach($aCats as $sCatName => $val) {
			$sCatLabel = str_repeat('&mdash;', $iLevel) . $this->Lang_Get('communitycats_category_' . $sCatName, $sCatName);
			$sResult .= '
				<option value="' . $sCatName . '"' . ($oBlog->getCategoryName() == $sCatName ? ' selected="selected"' : '') . '>' . $sCatLabel . '</option>';
		
			//�������� �� ��������� ����������� ��� ������ ���������
			if(is_array($val)) {
				$sResult .= $this->prepareOptionsForBlogCatSelect($val, $iLevel + 1);
			}
		}
		
		return $sResult;
	}
}
?>