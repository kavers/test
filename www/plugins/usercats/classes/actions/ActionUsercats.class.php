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


class PluginUsercats_ActionUsercats extends ActionPlugin {
	
	public function Init() {
		$this->SetDefaultEvent('users');
	}
	
	protected function RegisterEvent() {
		$this->AddEvent('users','ajaxGetUsers');
		$this->AddEvent('topics','ajaxGetTopics');
	}
	
	/**
	 * Возвращает html для вставки в блок пользователей
	 */
	protected function ajaxGetUsers() {
		$this->Viewer_SetResponseAjax('json');
		
		if(!($sCatName = $this->PluginUsercats_ModuleCategory_GetFullCategoryName(getRequest('user_cat', '')))) {
			return '';
		}
		
		//Исходим из того, что у нас двухуровненвые каталоги
		$aCatName = explode(':', $sCatName);
		if(count($aCatName) > 1) {
			array_pop($aCatName);
		}
		$sTemplateName = 'actions/' . strtolower(implode('_', $aCatName)) . 'Users.tpl';
		
		//загоняем данные в фильтр
		$aFilters['aUserFilter'] = array(
			'beginLike' => array('user_cat' => $sCatName),
		);
		$aOrder = array(
			'user_rating' => 'desc',
			'user_date_comment_last' => 'desc'
		);
		$aLimit = array(
			'iPage' => 1,
			'iElementsPerPage' => Config::Get('plugin.usercats.blockUserCount'),
		);
		$aUsersCat = $this->PluginUsercats_ModuleCategory_GetUsersByFilters($aFilters, $aOrder, $aLimit, false);
		$oViewerLocal = $this->Viewer_GetLocalViewer();
		$oViewerLocal->Assign('aUsersCatatalog',$aUsersCat);
		$this->Viewer_AssignAjax('sToggleText', $oViewerLocal->Fetch(Plugin::GetTemplatePath(__CLASS__) . $sTemplateName));
	}
	
	/**
	 * Возвращает html для вставки в блок топиков пользователей
	 */
	protected function ajaxGetTopics() {
		$this->Viewer_SetResponseAjax('json');
		
		if(!($sCatName = $this->PluginUsercats_ModuleCategory_GetFullCategoryName(getRequest('user_cat', '')))) {
			return '';
		}
		
		//Исходим из того, что у нас двухуровненвые каталоги
		$aCatName = explode(':', $sCatName);
		if(count($aCatName) > 1) {
			array_pop($aCatName);
		}
		$sTemplateName = 'actions/' . strtolower(implode('_', $aCatName)) . 'UserTopics.tpl';
		
		//загоняем данные в фильтр
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
			'iElementsPerPage' => Config::Get('plugin.usercats.blockTopicUserCount')
		);
		$aTopicsUserCat = $this->PluginUsercats_ModuleCategory_GetTopicsByFilters($aFilters, $aOrder, $aLimit, false);
		
		$oViewerLocal = $this->Viewer_GetLocalViewer();
		$oViewerLocal->Assign('aTopicsUserCatatalog',$aTopicsUserCat);
		$this->Viewer_AssignAjax('sToggleText', $oViewerLocal->Fetch(Plugin::GetTemplatePath(__CLASS__) . $sTemplateName));
	}
}