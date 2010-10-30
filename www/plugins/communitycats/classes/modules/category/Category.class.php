<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright © 2008 Mzhelskiy Maxim
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
	* Проверяем существует ли данная категория в массиве конфига.
	* 
	* @param	string		название категории
	* @return	bool		результат
	*/
	public function IsCategoryExist($sCatName) {
		return PluginLib_ModuleLib::Multi_array_key_exists($sCatName, Config::Get('plugin.communitycats.cats'));
	}
	
	/**
	* Возращаем полное имя категории вместе со всем промежуточными категорями
	* контейнерами в виде CatName1:CatName2:...:CatName
	*
	* @param	string			Имя категории
	* @return	string|bool		результат, false если категории не существует
	*/
	public function GetFullCategoryName($sCatName) {
		return PluginLib_ModuleLib::Multi_array_key_exists($sCatName, Config::Get('plugin.communitycats.cats'), true);
	}
	
	/**
	* Проверяем существование полного имени категории
	* 
	* @param	string		полное имя категории
	* @return	bool		результат проверки
	*/
	public function IsFullCategoryExist($sFullCatName) {
		if(!is_array($aCats = Config::Get('plugin.communitycats.cats'))) {
			return false;
		}
		
		$aCategoryPath = explode(':', $sFullCatName);
		return PluginLib_ModuleLib::CheckCategoryPath($aCats, $aCategoryPath);
	}
	
	/**
	* Получаем массив блогов отфильтрованный массив блогов
	* 
	* @param	array		массив фильтров
	* @param	array		(поле=>направление для сортировки)
	* @param	array		параметры для браузера страниц ('iPage', 'iElementsPerPage')
	* @param	bool		Загрузить ли только массив ID
	* @return	array		Массив объектов или id.
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
	* Получаем общее число блогов, соответсвующих фильтру
	* 
	* @param	array		массив фильтров
	* @return	int			число блогов
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
	* Получаем информацию (имя, метка, количество блогов) по всем категориям блогов
	* 
	* @param	array		Список типов блогов, для которых собираем информацию
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
	* Получаем информацию (имя, метка, количество блогов) по категориям из массива
	* 
	* @param	array		Дерево интересующих категорий
	* @param	array		Массив фильтров
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