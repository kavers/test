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


class PluginCommunitycats_ModuleCategory_MapperCategory extends Mapper {
	/**
	* Получаем массив id блогов удовлетворяющих фильтру
	* 
	* @param	array		параметры фильтра array(
	* 											'more' => array('fieldName' => 'value'),
	* 											'less' => ...,
	* 											'in' => ...
	* 										)
	* @param	array		(поле=>направление для сортировки)
	* @param	array		параметры для браузера страниц ('iPage', 'iElementsPerPage')
	* @return	array		массив id
	*/
	public function GetBlogsByFilter($aFilter, $aOrder, $aPaging) {
		$sWhere = PluginLib_ModuleMapper::BuildFilter($aFilter, 'b');
		$sOrder = PluginLib_ModuleMapper::BuildOrder($aOrder, 'b');
		$sLimit = PluginLib_ModuleMapper::BuildLimit($aPaging);
		
		$sql = 'SELECT 
						b.blog_id
					FROM 
						'.Config::Get('db.table.blog').' as b
					WHERE 1 = 1
						'. $sWhere . ' 
						'. $sOrder . '
						'. $sLimit;
		
		$aBlogs=array();
		if ($aRows=$this->oDb->select($sql)) {
			foreach ($aRows as $aBlog) {
				$aBlogs[]=$aBlog['blog_id'];
			}
		}
		
		return $aBlogs;
	}
	
	/**
	* Получаем число блогов удовлетворяющих фильтру
	* 
	* @param	array		параметры фильтра array(
	* 											'more' => array('fieldName' => 'value'),
	* 											'less' => ...,
	* 											'in' => ...
	* 										)
	* @return	int
	*/
	public function GetCountBlogsByFilter($aFilter) {
		$sWhere = PluginLib_ModuleMapper::BuildFilter($aFilter, 'b');
		
		$sql = 'SELECT 
						COUNT(b.blog_id) as blogs_count
					FROM 
						'.Config::Get('db.table.blog').' as b
					WHERE 1 = 1
						'. $sWhere;
		
		if ($aRow = $this->oDb->selectRow($sql)) {
			return $aRow['blogs_count'];
		}
		
		return false;
	}
}
?>