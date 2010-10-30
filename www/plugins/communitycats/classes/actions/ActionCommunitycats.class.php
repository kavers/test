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


class PluginCommunitycats_ActionCommunitycats extends ActionPlugin {
	
	public function Init() {
		$this->SetDefaultEvent('blogs');
	}
	
	protected function RegisterEvent() {
		$this->AddEvent('blogs','ajaxGetBlogs');
	}
	
	/**
	 * Возвращает html для вставки в блок сообществ
	 */
	protected function ajaxGetBlogs() {
		$this->Viewer_SetResponseAjax('json');
		$sCatName = getRequest('blog_cat', '');
		$sCatName = $sCatName == 'ALL' ? '' : $sCatName;
		
		if($sCatName) {
			if(!($sCatName = $this->PluginCommunitycats_ModuleCategory_GetFullCategoryName($sCatName))) {
				return '';
			}
		}
		
		//Исходим из того, что у нас двухуровненвые каталоги
		$aCatName = explode(':', $sCatName);
		array_pop($aCatName);
		$sTemplateName = 'actions/' . strtolower(implode('_', $aCatName)) . 'blogs.tpl';
		
		//загоняем данные в фильтр
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
			'iElementsPerPage' => Config::Get('plugin.communitycats.blockBlogCount'),
		);
		
		$aBlogsCat = $this->PluginCommunitycats_ModuleCategory_GetBlogsByFilter($aFilter, $aOrder, $aLimit, false);
		$oViewerLocal = $this->Viewer_GetLocalViewer();
		$oViewerLocal->Assign('aBlogsCatatalog', $aBlogsCat);
		$this->Viewer_AssignAjax('sToggleText', $oViewerLocal->Fetch(Plugin::GetTemplatePath(__CLASS__) . $sTemplateName));
	}
}