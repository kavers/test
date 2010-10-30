<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright В© 2008 Mzhelskiy Maxim
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

class PluginTopicfix_ModuleTofix_MapperTofix extends Mapper {
	/**
	* Получаем массив id топиков удовлетворяющих фильтру
	* @param	array		параметры фильтра array(
	* 				'eq' => array('fieldName' => val) //Значение поля равно
	* 				'more' => array('fieldName' => val) //Значение поля больше чем
	* 				'less' => array('fieldName' => val) //Значение поля меньше чем
	*				'in' => array('fieldName' => array(val)) //Значение поля принадлежит множеству
	* 												)
	* @param	array(поле=>направление для сортировки)
	* @param	array('iPage', 'iElementsPerPage') параметры для браузера страниц
	* @return	array		массив id
	*/
	public function GetTopicsByFilter($aFilter, $aOrder,&$iCount=0, $aPaging=null) {
		if(isset($aFilter['eq'])) {
			$aFilterEq['eq']=$aFilter['eq'];
			$sWhere = PluginLib_ModuleMapper::BuildFilter($aFilterEq, 't');
		}
		if(isset($aFilter['in'])) {
			$aFilterIn['in']=$aFilter['in'];
			$sWhere .= PluginLib_ModuleMapper::BuildFilter($aFilterIn, 'b');
		}
		$sOrder = PluginLib_ModuleMapper::BuildOrder($aOrder, 't');
		if($aPaging!==null) $sLimit = PluginLib_ModuleMapper::BuildLimit($aPaging);
		else $sLimit='';
		
		$sql = 'SELECT 
						t.topic_id
					FROM 
						'.Config::Get('db.table.topic').' as t, 
						'.Config::Get('db.table.blog').' as b
					WHERE t.blog_id=b.blog_id
						'. $sWhere . ' 
						'. $sOrder . '
						'. $sLimit;
		$aTopics=array();
		if ($aRows=$this->oDb->selectPage($iCount,$sql)) {
			foreach ($aRows as $aTopic) {
				$aTopics[]=$aTopic['topic_id'];
			}
		}
		return $aTopics;
	}
}
?>