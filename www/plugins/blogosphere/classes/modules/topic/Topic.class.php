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


class PluginBlogosphere_ModuleTopic extends PluginBlogosphere_Inherit_ModuleTopic {
	
	protected $accessModuleAvailable = null;
	
	/**
	* Проверяем установку плагина AccessToTopic для проверки прав доступа
	* 
	* @return	boolean
	*/
	protected function isAccessModuleAvailable() {
		if($this->accessModuleAvailable === null) {
			$aActivePlugin = $this->Plugin_GetActivePlugins();
			$this->accessModuleAvailable = in_array('accesstotopic', $aActivePlugin);
		}
		return $this->accessModuleAvailable;
	}
	
	/**
	 * Список топиков по фильтру
	 *
	 * @param  array $aFilter
	 * @return array
	 */
	public function GetTopicsForBlogosphereByFilter($aFilter) {
		
		//получаем текущего пользователя
		if (!$this->User_IsAuthorization()) {
			$aFilter['user_id']=0;
			$aFilter['user_admin']=0;
		} else {
			$this->oUserCurrent=$this->User_GetUserCurrent();
			$aFilter['user_id']=$this->oUserCurrent->getId();
			if($this->oUserCurrent->isAdministrator()) $aFilter['user_admin']=$this->oUserCurrent->isAdministrator();
				else $aFilter['user_admin']=0;
		}
		
		$s=serialize($aFilter);
		if (false === ($data = $this->Cache_Get("topic_filter_{$s}"))) {
			$data = array(
						'collection'=>$this->oMapperTopic->GetAllTopicsFilteredByDate($aFilter,$this->isAccessModuleAvailable()),
						'count'=>$this->GetCountTopicsByFilter($aFilter)
					);
			$this->Cache_Set($data, "topic_filter_{$s}", array('topic_update','topic_new'), 60*60*24*3);
		}
		$data['collection']=$this->GetTopicsAdditionalData($data['collection']);
		return $data;
	}
}
?>