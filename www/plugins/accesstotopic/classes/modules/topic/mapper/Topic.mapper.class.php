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

/**
* Переопределим ряд методов, для возможности фильтрации выборки
* согласно уровню доступа пользователя.
*/
class PluginAccesstotopic_ModuleTopic_MapperTopic extends PluginAccesstotopic_Inherit_ModuleTopic_MapperTopic {
	public function GetTopicsRatingByDate($sDate,$iLimit,$aExcludeBlog=array()) {
		$oUserCurrent = PluginLib_ModuleUser::GetUserCurrent();
		$sAccessWhere = $oUserCurrent->isAdministrator() ? '' : ' AND ' . PluginAccesstotopic_ModuleAccess::GetAccessWhereStatment($oUserCurrent->getId());
		
		$sql = 'SELECT 
						t.topic_id
					FROM 
						'. Config::Get('db.table.topic') .' as t
					WHERE
						t.topic_publish = 1
						AND
						t.topic_date_add >= ?
						AND
						t.topic_rating >= 0
						'. $sAccessWhere .'
						{ AND t.blog_id NOT IN(?a) }
					ORDER by t.topic_rating desc, t.topic_id desc
					LIMIT 0, ?d ';
		$aTopics=array();
		if ($aRows=$this->oDb->select(
				$sql,$sDate,
				(is_array($aExcludeBlog)&&count($aExcludeBlog)) ? $aExcludeBlog : DBSIMPLE_SKIP,
				$iLimit
			)
		) {
			foreach ($aRows as $aTopic) {
				$aTopics[]=$aTopic['topic_id'];
			}
		}
		return $aTopics;
	}
	
	public function GetTopicsByArrayId($aArrayId) {
		$oUserCurrent = PluginLib_ModuleUser::GetUserCurrent();
		$sAccessWhere = $oUserCurrent->isAdministrator() ? '' : ' AND ' . PluginAccesstotopic_ModuleAccess::GetAccessWhereStatment($oUserCurrent->getId());
		
		if(!is_array($aArrayId) or count($aArrayId)==0) {
			return array();
		}
				
		$sql = 'SELECT 
					t.*,
					tc.*
				FROM 
					'. Config::Get('db.table.topic') .' as t	
					JOIN  '. Config::Get('db.table.topic_content') .' as tc ON t.topic_id=tc.topic_id
				WHERE 
					t.topic_id IN(?a)
					'. $sAccessWhere .'
				ORDER BY FIELD(t.topic_id,?a) ';
		$aTopics=array();
		if ($aRows=$this->oDb->select($sql,$aArrayId,$aArrayId)) {
			foreach ($aRows as $aTopic) {
				$aTopics[]=Engine::GetEntity('Topic',$aTopic);
			}
		}		
		return $aTopics;
	}
	
	public function GetTopics($aFilter,&$iCount,$iCurrPage,$iPerPage) {
		$oUserCurrent = PluginLib_ModuleUser::GetUserCurrent();
		$sAccessWhere = $oUserCurrent->isAdministrator() ? '' : ' AND ' . PluginAccesstotopic_ModuleAccess::GetAccessWhereStatment($oUserCurrent->getId());
		
		$sWhere=$this->buildFilter($aFilter);
		
		if(isset($aFilter['order']) and !is_array($aFilter['order'])) {
			$aFilter['order'] = array($aFilter['order']);
		} else {
			$aFilter['order'] = array('t.topic_date_add desc');
		}
		
		$sql = 'SELECT 
						t.topic_id
					FROM 
						'. Config::Get('db.table.topic') .' as t,
						'. Config::Get('db.table.blog') .' as b
					WHERE 
						1=1
						'. $sWhere .'
						AND
						t.blog_id=b.blog_id
						'. $sAccessWhere .'
					ORDER BY '.
						implode(', ', $aFilter['order'])
				.'
					LIMIT ?d, ?d';
		$aTopics=array();
		if ($aRows=$this->oDb->selectPage($iCount,$sql,($iCurrPage-1)*$iPerPage, $iPerPage)) {
			foreach ($aRows as $aTopic) {
				$aTopics[]=$aTopic['topic_id'];
			}
		}
		return $aTopics;
	}
	
	public function GetCountTopics($aFilter) {
		$oUserCurrent = PluginLib_ModuleUser::GetUserCurrent();
		$sAccessWhere = $oUserCurrent->isAdministrator() ? '' : ' AND ' . PluginAccesstotopic_ModuleAccess::GetAccessWhereStatment($oUserCurrent->getId());
		
		$sWhere=$this->buildFilter($aFilter);
		$sql = 'SELECT 
					count(t.topic_id) as count
				FROM 
					'. Config::Get('db.table.topic') .' as t,
					'. Config::Get('db.table.blog') .' as b
				WHERE 
					1=1
					'. $sWhere .'
					'. $sAccessWhere.'
					AND
					t.blog_id=b.blog_id;';
		if ($aRow=$this->oDb->selectRow($sql)) {
			return $aRow['count'];
		}
		return false;
	}
	
	public function GetAllTopics($aFilter) {
		$oUserCurrent = PluginLib_ModuleUser::GetUserCurrent();
		$sAccessWhere = $oUserCurrent->isAdministrator() ? '' : ' AND ' . PluginAccesstotopic_ModuleAccess::GetAccessWhereStatment($oUserCurrent->getId());
		
		$sWhere=$this->buildFilter($aFilter);
		
		$sql = 'SELECT 
						t.topic_id
					FROM 
						'. Config::Get('db.table.topic') .' as t,
						'. Config::Get('db.table.blog') .' as b
					WHERE 
						1=1
						'. $sWhere .'
						'. $sAccessWhere .'
						AND
						t.blog_id=b.blog_id
					ORDER by t.topic_id desc';
		$aTopics=array();
		if ($aRows=$this->oDb->select($sql)) {
			foreach ($aRows as $aTopic) {
				$aTopics[]=$aTopic['topic_id'];
			}
		}

		return $aTopics;
	}

	public function AddTopic(ModuleTopic_EntityTopic $oTopic) {
		if($sId = parent::AddTopic($oTopic)) {
			if($this->updateAccessLevel($oTopic)) {
				return $sId;
			}
		}
		return false;
	}

	public function UpdateTopic(ModuleTopic_EntityTopic $oTopic) {
		if($this->updateAccessLevel($oTopic)) {
			return parent::UpdateTopic($oTopic);
		}
		
		return false; 
	}
	
	protected function updateAccessLevel(ModuleTopic_EntityTopic $oTopic) {
		$sql = 'UPDATE '.Config::Get('db.table.topic').' 
			SET 
				access_level = ?d
			WHERE
				topic_id = ?d
		';

		if($this->oDb->query($sql,$oTopic->getAccessLevel(), $oTopic->getId()) !== null) {
			return true;
		}

		return false;
	}
}
?>