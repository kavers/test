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


class PluginBlogosphere_ModuleTopic_MapperTopic extends PluginBlogosphere_Inherit_ModuleTopic_MapperTopic {
	/**
	* Получаем массив id топиков удовлетворяющих фильтру
	* 
	* @param	array		параметры фильтра array(
	* 											'more' => array('fieldName' => 'value'),
	* 											'less' => ...,
	* 											'in' => ...
	* 										)
	* @param	bool		доступен ли плагин для проверки на доступ к топикам
	* @return	array		массив id
	*/
	public function GetTopicsForBlogosphereByFilter($aFilter) {
		$sWhere = PluginLib_ModuleMapper::BuildFilter($aFilter, 't');
		
		//фильтрация по уровню доступа к топикам
		if(PluginLib_ModulePlugin::IsPluginAvailable('accesstotopic') && !$aFilter['oUser']->isAdministrator()) {
			$sWhere .= ' AND ' . PluginAccesstotopic_ModuleAccess::GetAccessWhereStatment($aFilter['oUser']->getId());
		}
		
		$sql = 'SELECT 
						t.topic_id
					FROM 
						'.Config::Get('db.table.topic').' as t
					WHERE 
						1 = 1
						'.$sWhere;
		$aTopics=array();
		if ($aRows=$this->oDb->select($sql)) {
			foreach ($aRows as $aTopic) {
				$aTopics[]=$aTopic['topic_id'];
			}
		}
		
		return $aTopics;
	}
}
?>