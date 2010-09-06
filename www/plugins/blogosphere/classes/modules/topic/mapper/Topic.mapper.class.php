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
	
	public function GetAllTopicsFilteredByDate($aFilter,$accessModuleAvalible=false) {
		$sWhere=$this->buildFilterDateAccess($aFilter);
		
		//фильтрация по уровню доступа к топикам
		if(($accessModuleAvalible)AND(isset($aFilter['user_id']))AND($aFilter['user_admin'])) {
			if($aFilter['user_id']==0) { //для анонимного юзера
				$sWhere.=' AND ';
				$sWhere.=PluginAccesstotopic_ModuleAccess::GetAccessWhereStatment();
			} else { //для авторизованного юзера
				if($aFilter['user_admin']==0) { //для не администратора
					$sWhere.=' AND ';
					$sWhere.=PluginAccesstotopic_ModuleAccess::GetAccessWhereStatment($aFilter['user_id']);
				} //для администратора не применяем фильтрацию по уровню доступа
			}
		}
		
		$sql = 'SELECT 
						t.topic_id
					FROM 
						'.Config::Get('db.table.topic').' as t
					WHERE 
						1=1
						'.$sWhere;
		$aTopics=array();
		if ($aRows=$this->oDb->select($sql)) {
			foreach ($aRows as $aTopic) {
				$aTopics[]=$aTopic['topic_id'];
			}
		}
		
		return $aTopics;
	}
	
	protected function buildFilterDateAccess($aFilter) {
		$sWhere='';
		
		if (isset($aFilter['date_begin'])) {  //Диапазон дат Начальная дата
			$sWhere.=" AND t.topic_date_add >= '".$aFilter['date_begin']."'";
		}
		if (isset($aFilter['date_end'])) {  //Диапазон дат Конечная дата
			$sWhere.=" AND t.topic_date_add <= '".$aFilter['date_end']."'";
		}
		return $sWhere;
	}
	
	protected function buildFilterAddtionalAccess($aAdditionalFilter) {
		foreach($aAdditionalFilter as $key => $value) {
			$sWhere.=" AND t.{$key} >= {$value}";
		}
	}
}
?>