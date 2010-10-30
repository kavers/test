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


class PluginUsercats_ModuleCategory_MapperCategory extends Mapper {
	/**
	* Получаем массив id пользователей удовлетворяющих фильтру
	* 
	* @param	array		параметры фильтра 'aUserFilter' array(
	* 												'more' => array('fieldName' => 'value'),
	* 												'less' => ...,
	* 												'in' => ...
	* 												...
	* 											),
	* 											'aBlogFilter' => (...)
	* @param	array		(поле=>направление для сортировки)
	* @param	array		параметры для браузера страниц ('iPage', 'iElementsPerPage')
	* @return	array		массив id
	*/
	public function GetUsersByFilters($aFilters, $aOrder, $aPaging) {
		if(isset($aFilters['aUserFilter'])) {
			$sUserWhere = PluginLib_ModuleMapper::BuildFilter($aFilters['aUserFilter'], 'u');
		} else {
			$sUserWhere = '';
		}
		
		if(isset($aFilters['aBlogFilter'])) {
			$sBlogWhere = PluginLib_ModuleMapper::BuildFilter($aFilters['aBlogFilter'], 'b');
		} else {
			$sBlogWhere = '';
		}
		$sOrder = PluginLib_ModuleMapper::BuildOrder($aOrder, 'u');
		$sLimit = PluginLib_ModuleMapper::BuildLimit($aPaging);
		
		$sql = 'SELECT 
						u.user_id
					FROM 
						'.Config::Get('db.table.user').' as u,
						'.Config::Get('db.table.blog').' as b
					WHERE b.user_owner_id = u.user_id
						'. $sUserWhere .'
						'. $sBlogWhere .'
						'. $sOrder . '
						'. $sLimit;
		$aUsers = array();
		if ($aRows=$this->oDb->select($sql)) {
			foreach ($aRows as $aUser) {
				$aUsers[]=$aUser['user_id'];
			}
		}
		
		return $aUsers;
	}
	
	/**
	* Получаем число пользователей удовлетворяющих фильтру
	* 
	* @param	array		параметры фильтра 'aUserFilter' array(
	* 												'more' => array('fieldName' => 'value'),
	* 												'less' => ...,
	* 												'in' => ...
	* 												...
	* 											),
	* 											'aBlogFilter' => (...)
	* @return	int
	*/
	public function GetCountUsersByFilters($aFilters) {
		if(isset($aFilters['aUserFilter'])) {
			$sUserWhere = PluginLib_ModuleMapper::BuildFilter($aFilters['aUserFilter'], 'u');
		} else {
			$sUserWhere = '';
		}
		
		if(isset($aFilters['aBlogFilter'])) {
			$sBlogWhere = PluginLib_ModuleMapper::BuildFilter($aFilters['aBlogFilter'], 'b');
		} else {
			$sBlogWhere = '';
		}
		$sql = 'SELECT 
						COUNT(u.user_id) as users_count
					FROM 
						'.Config::Get('db.table.user').' as u,
						'.Config::Get('db.table.blog').' as b
					WHERE b.user_owner_id = u.user_id
						'. $sUserWhere .'
						'. $sBlogWhere;
		if ($aRow = $this->oDb->selectRow($sql)) {
			return $aRow['users_count'];
		}
		return false;
	}
	
	/**
	* Получаем массив id топиков удовлетворяющих фильтру
	* 
	* @param	array		параметры фильтра 'aUserFilter' array(
	* 												'more' => array('fieldName' => 'value'),
	* 												'less' => ...,
	* 												'in' => ...
	* 												...
	* 											),
	* 											'aTopicFilter' => (...)
	* @param	array		(поле=>направление для сортировки)
	* @param	array		параметры для браузера страниц ('iPage', 'iElementsPerPage')
	* @return	array		массив id
	*/
	public function GetTopicsByFilters($aFilters, $aOrder, $aPaging) {
		if(isset($aFilters['aUserFilter'])) {
			$sUserWhere = PluginLib_ModuleMapper::BuildFilter($aFilters['aUserFilter'], 'u');
		} else {
			$sUserWhere = '';
		}
		
		if(isset($aFilters['aTopicFilter'])) {
			$sTopicWhere = PluginLib_ModuleMapper::BuildFilter($aFilters['aTopicFilter'], 't');
		} else {
			$sTopicWhere = '';
		}
		$oUserCurrent = PluginLib_ModuleUser::GetUserCurrent();
		if(!$oUserCurrent->isAdministrator() && PluginLib_ModulePlugin::IsPluginAvailable('accesstotopic')) {
			$sTopicWhere .= ' AND ' . PluginAccesstotopic_ModuleAccess::GetAccessWhereStatment($oUserCurrent->getId());
		}
		
		$sOrder = PluginLib_ModuleMapper::BuildOrder($aOrder, 't');
		$sLimit = PluginLib_ModuleMapper::BuildLimit($aPaging);
		
		$sql = 'SELECT 
						t.topic_id
					FROM 
						'.Config::Get('db.table.user').' as u,
						'.Config::Get('db.table.topic').' as t
					WHERE t.user_id = u.user_id
						'. $sUserWhere .'
						'. $sTopicWhere .'
						'. $sOrder . '
						'. $sLimit;
		$aTopics = array();
		if ($aRows = $this->oDb->select($sql)) {
			foreach ($aRows as $aTopic) {
				$aTopics[]=$aTopic['topic_id'];
			}
		}
		
		return $aTopics;
	}
}
?>