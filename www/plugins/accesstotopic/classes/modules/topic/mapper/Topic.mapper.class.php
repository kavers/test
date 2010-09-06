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
	public function GetTopicsRatingByDate($sDate,$iLimit,$aExcludeBlog=array(), $currentUserId = -1) {
		//Если метод вызывался в стандартном режим, то возвращаем управление
		if($currentUserId == -1) return parent::GetTopicsRatingByDate($sDate,$iLimit,$aExcludeBlog);
		
		$sql = 'SELECT 
						t.topic_id
					FROM 
						'.Config::Get('db.table.topic').' as t
					WHERE
						t.topic_publish = 1
						AND
						t.topic_date_add >= ?
						AND
						t.topic_rating >= 0
						AND
						'.PluginAccesstotopic_ModuleAccess::GetAccessWhereStatment($currentUserId).'
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
	
	public function GetTopicsByArrayId($aArrayId, $currentUserId = -1) {
		//Если метод вызывался в стандартном режим, то возвращаем управление
		if($currentUserId == -1) return parent::GetTopicsByArrayId($aArrayId);
		
		if(!is_array($aArrayId) or count($aArrayId)==0) {
			return array();
		}
				
		$sql = 'SELECT 
					t.*,
					tc.*
				FROM 
					'.Config::Get('db.table.topic').' as t	
					JOIN  '.Config::Get('db.table.topic_content').' AS tc ON t.topic_id=tc.topic_id
				WHERE 
					t.topic_id IN(?a)
					AND
					'.PluginAccesstotopic_ModuleAccess::GetAccessWhereStatment($currentUserId).'
				ORDER BY FIELD(t.topic_id,?a) ';
		$aTopics=array();
		if ($aRows=$this->oDb->select($sql,$aArrayId,$aArrayId)) {
			foreach ($aRows as $aTopic) {
				$aTopics[]=Engine::GetEntity('Topic',$aTopic);
			}
		}		
		return $aTopics;
	}
	
	public function GetTopics($aFilter,&$iCount,$iCurrPage,$iPerPage,$currentUserId = -1) {
		//Если метод вызывался в стандартном режим, то возвращаем управление
		if($currentUserId == -1) return parent::GetTopics($aFilter,&$iCount,$iCurrPage,$iPerPage);
		$sWhere=$this->buildFilter($aFilter);
		
		if(isset($aFilter['order']) and !is_array($aFilter['order'])) {
			$aFilter['order'] = array($aFilter['order']);
		} else {
			$aFilter['order'] = array('t.topic_date_add desc');
		}
		
		$sql = 'SELECT 
						t.topic_id
					FROM 
						'.Config::Get('db.table.topic').' as t,
						'.Config::Get('db.table.blog').' as b
					WHERE 
						1=1
						'.$sWhere.'
						AND
						t.blog_id=b.blog_id
						AND
						'.PluginAccesstotopic_ModuleAccess::GetAccessWhereStatment($currentUserId).'
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
	
	public function GetCountTopics($aFilter, $currentUserId = -1) {
		//Если метод вызывался в стандартном режим, то возвращаем управление
		if($currentUserId == -1) return parent::GetCountTopics($aFilter);
		
		$sWhere=$this->buildFilter($aFilter);
		$sql = 'SELECT 
					count(t.topic_id) as count
				FROM 
					'.Config::Get('db.table.topic').' as t,
					'.Config::Get('db.table.blog').' as b
				WHERE 
					1=1
					'.$sWhere.'
					AND
					'.PluginAccesstotopic_ModuleAccess::GetAccessWhereStatment($currentUserId).'
					AND
					t.blog_id=b.blog_id;';
		if ($aRow=$this->oDb->selectRow($sql)) {
			return $aRow['count'];
		}
		return false;
	}
	
	public function GetAllTopics($aFilter, $currentUserId = -1) {
		//Если метод вызывался в стандартном режим, то возвращаем управление
		if($currentUserId == -1) return parent::GetAllTopics($aFilter);
		
		$sWhere=$this->buildFilter($aFilter);
		
		$sql = 'SELECT 
						t.topic_id
					FROM 
						'.Config::Get('db.table.topic').' as t,
						'.Config::Get('db.table.blog').' as b
					WHERE 
						1=1
						'.$sWhere.'
						AND
						'.PluginAccesstotopic_ModuleAccess::GetAccessWhereStatment($currentUserId).'
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
		$sql = 'INSERT INTO '.Config::Get('db.table.topic').' 
			(blog_id,
			user_id,
			topic_type,
			topic_title,
			topic_tags,
			topic_date_add,
			topic_user_ip,
			topic_publish,
			topic_publish_draft,
			topic_publish_index,
			topic_cut_text,
			topic_forbid_comment,
			topic_text_hash,
			access_level
			)
			VALUES(?d,  ?d,	?,	?,	?,  ?, ?, ?d, ?d, ?d, ?, ?, ?, ?d)
		';
		if ($iId=$this->oDb->query($sql,$oTopic->getBlogId(),$oTopic->getUserId(),$oTopic->getType(),$oTopic->getTitle(),
			$oTopic->getTags(),$oTopic->getDateAdd(),$oTopic->getUserIp(),$oTopic->getPublish(),$oTopic->getPublishDraft(),$oTopic->getPublishIndex(),$oTopic->getCutText(),$oTopic->getForbidComment(),$oTopic->getTextHash(), $oTopic->getAccessLevel())) 
		{
			$oTopic->setId($iId);
			$this->AddTopicContent($oTopic);
			return $iId;
		}
		return false;
	}
	
	public function UpdateTopic(ModuleTopic_EntityTopic $oTopic) {
		$sql = "UPDATE ".Config::Get('db.table.topic')." 
			SET 
				blog_id= ?d,
				topic_title= ?,
				topic_tags= ?,
				topic_date_add = ?,
				topic_date_edit = ?,
				topic_user_ip= ?,
				topic_publish= ?d ,
				topic_publish_draft= ?d ,
				topic_publish_index= ?d,
				topic_rating= ?f,
				topic_count_vote= ?d,
				topic_count_read= ?d,
				topic_count_comment= ?d, 
				topic_cut_text = ? ,
				topic_forbid_comment = ? ,
				topic_text_hash = ? ,
				access_level = ?d
			WHERE
				topic_id = ?d
		";
		if ($this->oDb->query($sql,$oTopic->getBlogId(),$oTopic->getTitle(),$oTopic->getTags(),$oTopic->getDateAdd(),$oTopic->getDateEdit(),$oTopic->getUserIp(),$oTopic->getPublish(),$oTopic->getPublishDraft(),$oTopic->getPublishIndex(),$oTopic->getRating(),$oTopic->getCountVote(),$oTopic->getCountRead(),$oTopic->getCountComment(),$oTopic->getCutText(),$oTopic->getForbidComment(),$oTopic->getTextHash(),$oTopic->getAccessLevel(), $oTopic->getId())) {
			$this->UpdateTopicContent($oTopic);
			return true;
		}		
		return false;
	}
}
?>