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

class PluginCommunitycats_ModuleCategory extends Module {
	protected $oMapper;
	
	public function Init() {
		$this->oMapper = Engine::GetMapper(__CLASS__);
	}
	
	/**
	* ��������� ���������� �� ������ ��������� � ������� �������.
	* 
	* @param	string		�������� ���������
	* @return	bool		���������
	*/
	public function IsCategoryExist($sCatName) {
		return PluginLib_ModuleLib::Multi_array_key_exists($sCatName, Config::Get('plugin.communitycats.cats'));
	}
	
	/**
	* ��������� ������ ��� ��������� ������ �� ���� �������������� ����������
	* ������������ � ���� CatName1:CatName2:...:CatName
	*
	* @param	string			��� ���������
	* @return	string|bool		���������, false ���� ��������� �� ����������
	*/
	public function GetFullCategoryName($sCatName) {
		return PluginLib_ModuleLib::Multi_array_key_exists($sCatName, Config::Get('plugin.communitycats.cats'), true);
	}
	
	/**
	* ��������� ������������� ������� ����� ���������
	* 
	* @param	string		������ ��� ���������
	* @return	bool		��������� ��������
	*/
	public function IsFullCategoryExist($sFullCatName) {
		if(!is_array($aCats = Config::Get('plugin.communitycats.cats'))) {
			return false;
		}
		
		$aCategoryPath = explode(':', $sFullCatName);
		return PluginLib_ModuleLib::CheckCategoryPath($aCats, $aCategoryPath);
	}
	
	/**
	* �������� ������ ������ ��������������� ������ ������
	* 
	* @param	array		������ ��������
	* @param	array		(����=>����������� ��� ����������)
	* @param	array		��������� ��� �������� ������� ('iPage', 'iElementsPerPage')
	* @param	bool		��������� �� ������ ������ ID
	* @return	array		������ �������� ��� id.
	*/
	public function GetBlogsByFilter($aFilter, $aOrder = array(), $aPaging = array(), $bIdOnly = true) {
		$s = serialize(array_merge($aFilter, $aOrder, $aPaging));
		if (false === ($aIdBlog = $this->Cache_Get("blogs_filter_{$s}"))) {
			$aIdBlog = $this->oMapper->GetBlogsByFilter($aFilter, $aOrder, $aPaging);
			$this->Cache_Set($aIdBlog, "blogs_filter_{$s}", array('blog_update','blog_new'), 60*60*24*3);
		}
		
		if($bIdOnly) {
			return $aIdBlog;
		}
		
		return $this->Blog_GetBlogsAdditionalData($aIdBlog,array('owner'=>array(),'relation_user'));
	}
	
	/**
	* �������� ����� ����� ������, �������������� �������
	* 
	* @param	array		������ ��������
	* @return	int			����� ������
	*/
	public function GetCountBlogsByFilter($aFilter) {
		$s = serialize($aFilter);
		if (false === ($iCount = $this->Cache_Get("blogs_filter_count_{$s}"))) {
			$iCount = $this->oMapper->GetCountBlogsByFilter($aFilter);
			$this->Cache_Set($iCount, "blogs_filter_count_{$s}", array('blog_new'), 60*60*24*3);
		}
		
		return $iCount;
	}
	
	/**
	* �������� ���������� (���, �����, ���������� ������) �� ���� ���������� ������
	* 
	* @param	array		������ ����� ������, ��� ������� �������� ����������
	* @return	array		array('catName' => array(
	* 								'info' => array('title', 'count'),
									'subCats' => array(...)
	* 							)
	* 						)
	*/
	public function GetCategoriesInfo($aBlogType) {
		if(is_array($aBlogType)) {
			$aFilter = array(
				'in' => array('blog_type' => $aBlogType)
			);
		} else {
			$aFilter = array();
		}
		
		$aCatsTree = Config::Get('plugin.communitycats.cats');
		
		$s = serialize($aFilter);
		if (false === ($aCatInfo = $this->Cache_Get("categories_info_{$s}"))) {
			$aCatInfo = $this->GetRecursiveCatInfo($aCatsTree, $aFilter);
			$this->Cache_Set($aCatInfo, "categories_info_{$s}", array('blog_new', 'blog_update'), 60*60*24*3);
		}
		
		return $aCatInfo;
	}
	
	/**
	* �������� ���������� (���, �����, ���������� ������) �� ���������� �� �������
	* 
	* @param	array		������ ������������ ���������
	* @param	array		������ ��������
	* @return	array		array('catName' => array(
	* 								'info' => array('title', 'count', 'link'),
	* 								'subCats' => array(...)
	* 							)
	* 						)
	*/
	public function GetRecursiveCatInfo($aCat, $aFilter) {
		if(!is_array($aCat)) {
			return array();
		}
		
		$aCatInfo = array();
		$aFilterForCat = $aFilter;
		foreach($aCat as $sCatName => $aSubcat) {
			$sFullCatName = $this->GetFullCategoryName($sCatName);
			$aFilterForCat['beginLike']['blog_cat'] = $sFullCatName;
			$aCatInfo[$sCatName]['info'] = array(
				'title' => $this->Lang_Get('communitycats_category_'.strtoupper($sCatName)),
				'count' => $this->GetCountBlogsByFilter($aFilterForCat),
				'link' => '/blogs/' . implode('/', explode(':',strtolower($sFullCatName))) . '/'
			);
			
			if(is_array($aSubcat)) {
				$aCatInfo[$sCatName]['subCats'] = $this->GetRecursiveCatInfo($aSubcat, $aFilter);
			}
		}
		
		return $aCatInfo;
	}
}
?>