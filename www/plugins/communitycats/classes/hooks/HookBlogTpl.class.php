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
class PluginCommunitycats_HookBlogTpl extends Hook {

	public function RegisterHook() {
		/**
		* ��� ��� ������� HTML ����
		*/
		//Select ������ ���������
		$this->AddHook('template_html_pluginCommunitycats_form', 'AddSelectToChangeCategory');
		//���������� � ���������
		$this->AddHook('template_html_pluginCommunitycats_category', 'AddCategory');
		//���������� ��� bread cumbers
		$this->AddHook('template_html_pluginCommunitycats_bread_cumbers', 'AddBreadCrumbs');
		//������� ��������� ���������
		$this->AddHook('template_html_pluginCommunitycats_catalog', 'AddCatalog');
		//�������� ��������� �� ����������
		$this->AddHook('template_html_pluginCommunitycats_blogs_catalog', 'AddBlogsCatalog');
	}

	/**
	* ������� HTML
	*/
	public function AddSelectToChangeCategory() {
		$sCommunityCatsOptions = '
		{if $oBlogEdit}
			{assign var="sCurCat" value=$oBlogEdit->getCategoryName()}
		{else}
			{assign var="sCurCat" value=""}
		{/if}
		' . $this->prepareOptionsForSelect(Config::Get('plugin.communitycats.cats'));
		$this->Viewer_Assign('sCommunityCatsOptions', $sCommunityCatsOptions);
		return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__).'selectToChangeCategory.tpl');
	}
	
	public function AddCategory() {
		return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__).'communityCategory.tpl');
	}
	
	public function AddBreadCrumbs() {
		return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__).'breadCumbers.tpl');
	}
	
	public function AddCatalog() {
		$aCats = $this->PluginCommunitycats_ModuleCategory_GetCategoriesInfo(array('open', 'close'));
		$this->Viewer_Assign('aCats', $aCats);
		return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__).'categoryCatalog.tpl');
	}
	
	public function AddBlogsCatalog($aData = array()) {
		$sType = isset($aData['sType']) ? $aData['sType'] : '';
		$iCount = isset($aData['iCount']) ? (int)$aData['iCount'] : Config::Get('plugin.communitycats.blockBlogCount');
		
		if(!($sCatName = $this->PluginUsercats_ModuleCategory_GetFullCategoryName($sType)) && $sType) {
			return '';
		}
		
		if($sCatName) {
			$aFilter = array(
				'beginLike' => array('blog_cat' => $sCatName),
			);
		} else {
			$aFilter = array();
		}
		
		$aFilter['in'] = array('blog_type' => array('open', 'close'));
		$aOrder = array(
			'blog_count_user' => 'desc',
		);
		$aLimit = array(
			'iPage' => 1,
			'iElementsPerPage' => $iCount
		);
		
		$aBlogsCat = $this->PluginCommunitycats_ModuleCategory_GetBlogsByFilter($aFilter, $aOrder, $aLimit, false);
		$this->Viewer_Assign('aBlogsCatatalog', $aBlogsCat);
		
		$aCats = $this->PluginCommunitycats_ModuleCategory_GetCategoriesInfo(array('open', 'close'));
		$this->Viewer_Assign('aBlogCats', $aCats);
		
		$sFileName = str_replace(':', '_',strtolower($sType)) . 'Catalog.tpl';
		return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . $sFileName);
	}
	
	/**
	* ����������� ������ ��������� � ������ options ��� select
	* 
	* @param	array		������ ��������� (��. config)
	* @param	integer		������� ����������� ��������� (��� ����������� ���������)
	* @return	string		������ ����� option ��� ������� � �������
	*/
	protected function prepareOptionsForSelect($aCats, $iLevel = 0) {
		if(!is_array($aCats)) {
			return '';
		}
		
		$sResult = '';
		
		foreach($aCats as $sCatName => $val) {
			$sCatLabel = str_repeat('&mdash;', $iLevel) . $this->Lang_Get('communitycats_category_' . $sCatName, $sCatName);
			
			/*
			��� ��� �� ���� �� �� ����� ��� ���������� �������� �������� ������ ��������
			id �������� �����, �� ������� ������� smarty ����, ��� ��� ������������ 
			���������� � ����� �������
			*/
			$sResult .= '
				<option value="' . $sCatName . '" {if $sCurCat == '. $sCatName .'}selected="selected"{/if}>' . $sCatLabel . '</option>';
		
			//�������� �� ��������� ����������� ��� ������ ���������
			if(is_array($val)) {
				$sResult .= $this->prepareOptionsForSelect($val, $iLevel + 1);
			}
		}
		
		return $sResult;
	}
}
?>