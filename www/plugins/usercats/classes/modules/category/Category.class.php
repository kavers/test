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

class PluginUsercats_ModuleCategory extends Module {
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
		return PluginLib_ModuleLib::Multi_array_key_exists($sCatName, Config::Get('plugin.usercats.cats'));
	}
	
	/**
	* Возращаем полное имя категории вместе со всем промежуточными категорями
	* контейнерами в виде CatName1:CatName2:...:CatName
	*
	* @param	string			Имя категории
	* @return	string|bool		результат, false если категории не существует
	*/
	public function GetFullCategoryName($sCatName) {
		return PluginLib_ModuleLib::Multi_array_key_exists($sCatName, Config::Get('plugin.usercats.cats'), true);
	}
	
	/**
	* Проверяем существование полного имени категории
	* 
	* @param	string		полное имя категории
	* @return	bool		результат проверки
	*/
	public function IsFullCategoryExist($sFullCatName) {
		if(!is_array($aCats = Config::Get('plugin.usercats.cats'))) {
			return false;
		}
		
		$aCategoryPath = explode(':', $sFullCatName);
		return PluginLib_ModuleLib::CheckCategoryPath($aCats, $aCategoryPath);
	}
	
	/**
	* Получаем отфильтрованный массив пользователей
	* 
	* @param	array		массив фильтров ('aUserFilter', 'aBlogFilter')
	* @param	array		(поле=>направление для сортировки)
	* @param	array		параметры для браузера страниц ('iPage', 'iElementsPerPage')
	* @param	bool		Загрузить ли только массив ID
	* @return	array		Массив объектов или id.
	*/
	public function GetUsersByFilters($aFilters, $aOrder = array(), $aPaging = array(), $bIdOnly = true) {
		$s = serialize(array_merge($aFilters, $aOrder, $aPaging));
		if (false === ($aIdUser = $this->Cache_Get("users_id_filter_{$s}"))) {
			$aIdUser = $this->oMapper->GetUsersByFilters($aFilters, $aOrder, $aPaging);
			$this->Cache_Set($aIdUser, "users_id_filter_{$s}", array('user_update','user_new'), 60*60*24*3);
		}
		
		if($bIdOnly) {
			return $aIdUser;
		}
		
		return $this->User_GetUsersAdditionalData($aIdUser);
	}
	
	/**
	* Получаем общее число пользователей, соответсвующих фильтру
	* 
	* @param	array		массив фильтров ('aUserFilter', 'aBlogFilter')
	* @return	int			число пользователей
	*/
	public function GetCountUsersByFilters($aFilters) {
		$s = serialize($aFilters);
		if (false === ($iCount = $this->Cache_Get("users_filter_count_{$s}"))) {
			$iCount = $this->oMapper->GetCountUsersByFilters($aFilters);
			$this->Cache_Set($iCount, "users_filter_count_{$s}", array('user_new'), 60*60*24*3);
		}
		
		return $iCount;
	}
	
	/**
	* Получаем информацию (имя, метка, количество блогов) по всем категориям пользователей
	* 
	* @return	array		array('catName' => array(
	* 								'info' => array('title', 'count'),
	* 								'subCats' => array(...)
	* 							)
	* 						)
	*/
	public function GetCategoriesInfo() {
		$aCatsTree = Config::Get('plugin.usercats.cats');
		
		if (false === ($aCatInfo = $this->Cache_Get("user_categories_info"))) {
			$aCatInfo = $this->GetRecursiveCatInfo($aCatsTree);
			$this->Cache_Set($aCatInfo, "user_categories_info", array('user_new', 'user_update'), 60*60*24*3);
		}
		
		return $aCatInfo;
	}
	
	/**
	* Получаем информацию (имя, метка, количество блогов) по категориям пользователей из массива
	* 
	* @param	array		Дерево интересующих категорий
	* @return	array		array('catName' => array(
	* 								'info' => array('title', 'count', 'link'),
	* 								'subCats' => array(...)
	* 							)
	* 						)
	*/
	public function GetRecursiveCatInfo($aCat) {
		if(!is_array($aCat)) {
			return array();
		}
		
		$aCatInfo = array();
		$aFiltersForCat = array();
		foreach($aCat as $sCatName => $aSubcat) {
			$sFullCatName = $this->GetFullCategoryName($sCatName);
			$aFiltersForCat['aUserFilter']['beginLike']['user_cat'] = $sFullCatName;
			$aCatInfo[$sCatName]['info'] = array(
				'name' => $sCatName,
				'title' => $this->Lang_Get('usercats_category_'.strtoupper($sCatName)),
				'count' => $this->GetCountUsersByFilters($aFiltersForCat),
				'link' => '/people/' . implode('/', explode(':',strtolower($sFullCatName))) . '/',
			);
			$aCatInfo[$sCatName]['blogCats'] = $this->GetRecursiveBlogCatInfo(Config::Get('plugin.communitycats.cats'), $aFiltersForCat['aUserFilter']);
			if(is_array($aSubcat)) {
				$aCatInfo[$sCatName]['subCats'] = $this->GetRecursiveCatInfo($aSubcat, $aFilter);
			}
		}
		
		return $aCatInfo;
	}
	
	/**
	* Получаем информацию (имя, метка, количество блогов) по категориям блогов из массива
	* для определённой группы пользователей
	* 
	* @param	array		Дерево интересующих категорий
	* @param	array		фильтры определяющие группу пользователей
	* @return	array		array('catName' => array(
	* 								'info' => array('title', 'count', 'link'),
	* 								'subCats' => array(...)
	* 							)
	* 						)
	*/
	public function GetRecursiveBlogCatInfo($aCat, $aUserFilter = array()) {
		if(!is_array($aCat)) {
			return array();
		}
		
		$aCatInfo = array();
		$aFiltersForCat = array();
		foreach($aCat as $sCatName => $aSubcat) {
			$sFullCatName = $this->PluginCommunitycats_ModuleCategory_GetFullCategoryName($sCatName);
			$aFiltersForCat['aBlogFilter']['beginLike']['blog_cat'] = $sFullCatName;
			$aFiltersForCat['aBlogFilter']['eq']['blog_type'] = 'personal';
			$aFiltersForCat['aUserFilter'] = $aUserFilter;
			if(isset($aUserFilter['beginLike']['user_cat'])) {
				$sUserPartLink = implode('/', explode(':',strtolower($aUserFilter['beginLike']['user_cat']))) . '/';
			} else {
				$sUserPartLink = '';
			}
			$aCatInfo[$sCatName]['info'] = array(
				'name' => $sCatName,
				'title' => $this->Lang_Get('communitycats_category_'.strtoupper($sCatName)),
				'count' => $this->GetCountUsersByFilters($aFiltersForCat),
				'link' => '/people/'. $sUserPartLink . 'cat/' . implode('/', explode(':',strtolower($sFullCatName))) . '/'
			);
			
			if(is_array($aSubcat)) {
				$aCatInfo[$sCatName]['subCats'] = $this->GetRecursiveBlogCatInfo($aSubcat, $aFilter);
			}
		}
		
		return $aCatInfo;
	}
	
	/**
	* Получаем отфильтрованный массив топиков
	* 
	* @param	array		массив фильтров ('aUserFilter', 'aTopicFilter')
	* @param	array		(поле=>направление для сортировки)
	* @param	array		параметры для браузера страниц ('iPage', 'iElementsPerPage')
	* @param	bool		Загрузить ли только массив ID
	* @return	array		Массив объектов или id.
	*/
	public function GetTopicsByFilters($aFilters, $aOrder = array(), $aPaging = array(), $bIdOnly = true) {
		$s = serialize(array_merge($aFilters, $aOrder, $aPaging));
		if (false === ($aIdTopic = $this->Cache_Get("topics_id_filter_{$s}"))) {
			$aIdTopic = $this->oMapper->GetTopicsByFilters($aFilters, $aOrder, $aPaging);
			$this->Cache_Set($aIdTopic, "topics_id_filter_{$s}", array('topic_update','topic_new'), 60*60*24);
		}
		
		if($bIdOnly) {
			return $aIdTopic;
		}
		
		return $this->Topic_GetTopicsAdditionalData($aIdTopic);
	}

}
?>