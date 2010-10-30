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
 * Модуль для выборки отдельно закрепленных топиков и всех топиков, кроме закрепленных
 */
class PluginTopicfix_ModuleTofix extends Module {
	protected $oMapperTopic;
	protected $oUserCurrent=null;
	
	/**
	 * Инициализация
	 */
	public function Init() {
		$this->oMapperTopic=Engine::GetMapper(__CLASS__);
		$this->oUserCurrent=$this->User_GetUserCurrent();
	}
	
	/**
	 * Список топиков по фильтру(исключая закрепленные)
	 */
	public function GetTopicsByFilter($aFilter,$iPage=0,$iPerPage=0) {
		$s=serialize($aFilter);
		$iCount=0;
		if($iPage!=0 and $iPerPage!=0) {
			$aTopics=$this->oMapperTopic->GetTopicsByFilter($aFilter, array('topic_date_add'=>'DESC'),$iCount,array('iPage'=>$iPage,'iElementsPerPage'=>$iPerPage)); }
		else {
			$aTopics=$this->oMapperTopic->GetTopicsByFilter($aFilter, array('topic_date_add'=>'DESC'),$iCount);
		}
		
		if(false === ($data = $this->Cache_Get("topic_filter_{$s}_{$iPage}_{$iPerPage}"))) {
			$data = array(
						'collection'=>$aTopics,
						'count'=>$iCount
					);
			$this->Cache_Set($data, "topic_filter_{$s}_{$iPage}_{$iPerPage}", array('topic_update','topic_new'), 60*60*24*3);
		}
		$data['collection']=$this->ModuleTopic_GetTopicsAdditionalData($data['collection']);
		return $data;
	}
	
	/**
	 * Список топиков из блога(исключая закрепленные)
	 */
	public function GetTopicsByBlogNotFixed($oBlog,$iPage,$iPerPage) {
		$aFilter=array(
			'eq' => array(
				'topic_fixed' => 0,
				'topic_publish' => 1,
				'blog_id' => $oBlog->getId()
			)
		);
		return $this->GetTopicsByFilter($aFilter,$iPage,$iPerPage);
	}
	
	/**
	 * Список закрепленных топиков из блога
	 */
	public function GetTopicsByBlogFixed($oBlog) {
		$aFilter=array(
			'eq' => array(
				'topic_fixed' => 1,
				'topic_publish' => 1,
				'blog_id' => $oBlog->getId()
			)
		);
		return $this->GetTopicsByFilter($aFilter);
	}
	
	/**
	 * Получает список топиков по юзеру исключая закрепленные
	 */
	public function GetTopicsPersonalByUserNotFixed($sUserId,$iPublish,$iPage,$iPerPage) {
		$aFilter=array(
			'eq' => array(
				'topic_fixed' => 0,
				'topic_publish' => $iPublish,
				'user_id' => $sUserId
			),
			'in' => array(
				'blog_type' => array('open','personal')
			)
		);
		/**
		 * Если пользователь смотрит свой профиль, то добавляем в выдачу
		 * закрытые блоги в которых он состоит
		 */
		if($this->oUserCurrent && $this->oUserCurrent->getId()==$sUserId) {
			$aFilter['in']['blog_type'][]='close';
		}
		return $this->GetTopicsByFilter($aFilter,$iPage,$iPerPage);
	}
	
	/**
	 * Получает список закрепленных топиков по юзеру
	 */
	public function GetTopicsPersonalByUserFixed($sUserId,$iPublish) {
		$aFilter=array(
			'eq' => array(
				'topic_fixed' => 1,
				'topic_publish' => $iPublish,
				'user_id' => $sUserId
			),
			'in' => array(
				'blog_type' => array('open','personal')
			)
		);
		/**
		 * Если пользователь смотрит свой профиль, то добавляем в выдачу
		 * закрытые блоги в которых он состоит
		 */
		if($this->oUserCurrent && $this->oUserCurrent->getId()==$sUserId) {
			$aFilter['in']['blog_type'][]='close';
		}
		return $this->GetTopicsByFilter($aFilter);
	}
}
?>