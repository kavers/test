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
class PluginAccesstotopic_ModuleTopic extends PluginAccesstotopic_Inherit_ModuleTopic {
	/**
	* Опирается на переопределённый Topic.mapper
	*/
	public function GetTopicsRatingByDate($sDate,$iLimit=20) {
		$oUserCurrent = PluginLib_ModuleUser::GetUserCurrent();
		/**
		 * Получаем список блогов, топики которых нужно исключить из выдачи
		 */
		$aCloseBlogs = ($this->oUserCurrent)
			? $this->Blog_GetInaccessibleBlogsByUser($this->oUserCurrent)
			: $this->Blog_GetInaccessibleBlogsByUser();	
		
		$s=serialize($aCloseBlogs);
		
		if (false === ($data = $this->Cache_Get("topic_rating_{$sDate}_{$iLimit}_{$oUserCurrent->getId()}_{$s}"))) {
			$data = $this->oMapperTopic->GetTopicsRatingByDate($sDate,$iLimit,$aCloseBlogs);
			$this->Cache_Set($data, "topic_rating_{$sDate}_{$iLimit}_{$oUserCurrent->getId()}_{$s}", array('topic_update'), 60*60*24*2);
		}
		$data=$this->GetTopicsAdditionalData($data);
		return $data;
	}
	
	public function GetTopicsByFilter($aFilter,$iPage=0,$iPerPage=0,$aAllowData=array('user'=>array(),'blog'=>array('owner'=>array(),'relation_user'),'vote','favourite','comment_new')) {
		$oUserCurrent = PluginLib_ModuleUser::GetUserCurrent();
		
		$s=serialize($aFilter);
		if (false === ($data = $this->Cache_Get("topic_filter_{$s}_{$iPage}_{$iPerPage}_{$oUserCurrent->getId()}"))) {
			$data = ($iPage*$iPerPage!=0) 
				? array(
						'collection'=>$this->oMapperTopic->GetTopics($aFilter,$iCount,$iPage,$iPerPage),
						'count'=>$iCount
					)
				: array(
						'collection'=>$this->oMapperTopic->GetAllTopics($aFilter),
						'count'=>$this->GetCountTopicsByFilter($aFilter)
					);
			$this->Cache_Set($data, "topic_filter_{$s}_{$iPage}_{$iPerPage}_{$oUserCurrent->getId()}", array('topic_update','topic_new'), 60*60*24*3);
		}
		$data['collection']=$this->GetTopicsAdditionalData($data['collection'],$aAllowData);
		return $data;
	}
	
	public function GetCountTopicsByFilter($aFilter) {
		$oUserCurrent = PluginLib_ModuleUser::GetUserCurrent();
		
		$s=serialize($aFilter);
		if (false === ($data = $this->Cache_Get("topic_count_{$s}_{$oUserCurrent->getId()}"))) {
			$data = $this->oMapperTopic->GetCountTopics($aFilter);
			$this->Cache_Set($data, "topic_count_{$s}_{$oUserCurrent->getId()}", array('topic_update','topic_new'), 60*60*24*1);
		}
		return 	$data;
	}
	
	public function GetTopicsByArrayId($aTopicId) {
		$oUserCurrent = PluginLib_ModuleUser::GetUserCurrent();
		$aTopics = parent::GetTopicsByArrayId($aTopicId);
		
		//Дополнительная фильтрация, так как топики полученные из кэша, не были проверены на доступ
		$aTopics = $oUserCurrent->isAdministrator() ? $aTopics : $this->deleteTopicsWithoutNeccessaryAccess($aTopics);
		return $aTopics;
	}
	
	public function GetTopicsByArrayIdSolid($aTopicId) {
		$oUserCurrent = PluginLib_ModuleUser::GetUserCurrent();
		$aTopics = parent::GetTopicsByArrayIdSolid($aTopicId);
		
		//Дополнительная фильтрация, так как топики полученные из кэша, не были проверены на доступ
		$aTopics = $oUserCurrent->isAdministrator() ? $aTopics : $this->deleteTopicsWithoutNeccessaryAccess($aTopics);
		return $aTopics;
	}
	
	/**
	* Удаляем из полученных топиков те, к которым текущий пользователь не должен получить доступ.
	* @param	array		ассоциатвный массив результатов запроса и параметров.
	*/
	protected function deleteTopicsWithoutNeccessaryAccess($aTopics) {
		if(!is_array($aTopics)) {
			return $aTopics;
		}
		
		$oUserCurrent = PluginLib_ModuleUser::GetUserCurrent();
		
		//Для админа никаких ограничений
		if($oUserCurrent->isAdministrator()) {
			return $aTopics;
		}
		
		//Обходим все топики в выборке и принимаем решение оставлять ли каждый из них.
		$aNewResult = array();
		foreach($aTopics as $oTopic) {
			if($this->PluginAccesstotopic_ModuleAccess_CheckAccessToTopic($oUserCurrent, $oTopic)) $aNewResult[$oTopic->getId()] = $oTopic;
		}
		return $aNewResult;
	}
}
?>