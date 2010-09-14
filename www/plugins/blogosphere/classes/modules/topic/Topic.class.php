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
	//Константы анонимного пользователя
	const ANONIM_USER_ID = 0;
	
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
	* Получить объект текущего пользователя, или создать объект с user_id = 0 для анонима
	* 
	* @return	object		объект-сущность пользователя
	*/
	protected function getCurrentUserObject() {
		//Проверяем является находистя ли пользователь в системе
		if(!$this->User_IsAuthorization()) {
			$oUserCurrent = Engine::GetEntity('User_User', array('user_id' => self::ANONIM_USER_ID, 'user_is_administrator' => false));
		} else {
			$oUserCurrent = $this->User_GetUserCurrent();
		}
		
		return $oUserCurrent;
	}
	
	/**
	* Проверяем анонимный ли пользователь.
	* Решил не расширять класс ModuleUser_EntityUser, дабы не провоцировать возможные
	* конфликты мз-за локального решения о сущности анонимного пользователя.
	* 
	* @param	object		объект-сущность пользователя
	* @return	boolean		true, если передан объект анонимного пользователя.
	*/
	protected function isUserAnon($oUser) {
		return $oUser->getId() == self::ANONIM_USER_ID ? true : false;
	}
	
	/**
	 * Список топиков по фильтру
	 *
	 * @param  array $aFilter
	 * @return array
	 */
	public function GetTopicsForBlogosphereByFilter($aFilter) {
		$aFilter['oUser'] = $this->getCurrentUserObject();
		$aFilterConfig = $this->getFilterConfig($aFilter['filterType']);
		
		if(isset($aFilterConfig['function'])) {
			$sOutsideGetTopicsFunctionName = $aFilterConfig['function'];
			return $this->$sOutsideGetTopicsFunctionName($aFilter);
		} elseif(isset($aFilterConfig['type'])) {
			$sPrepareStandartFilterMethodName = $this->getFilterMethodName($aFilterConfig['type']);
			$aFilter = $this->$sPrepareStandartFilterMethodName($aFilter);
		}
		
		$s=serialize($aFilter);
		if (false === ($data = $this->Cache_Get("topic_filter_{$s}"))) {
			$data = $this->oMapperTopic->GetTopicsForBlogosphereByFilter($aFilter,$this->isAccessModuleAvailable());
			$this->Cache_Set($data, "topic_filter_{$s}", array('topic_update','topic_new'), 60*60*24*3);
		}
		$data = $this->GetTopicsAdditionalData($data);
		return $data;
	}
	
	/**
	* Возвращает конфигурационный массив фильтра из конфига плагина
	* 
	* @param	string		Тип фильтра (должен быть так же определён в конфиге)
	* @return	array		Конфигурационный массив фильтра
	*/
	protected function getFilterConfig($sFilterType) {
		$aFilter = Config::Get('plugin.blogosphere.filters');
		foreach($aFilter as $aConfig) {
			if($aConfig['type'] == $sFilterType) {
				return $aConfig;
			}
		}
		
		return array();
	}
	
	/**
	* На основании типа фильтра возвращает имя метода для его обработки
	* 
	* @param	string		тип фильтра
	* @return	string		имя метода
	*/
	protected function getFilterMethodName($sFilterType) {
		return 'prepareStandartFilter' . ucfirst($sFilterType); 
	}
	
	/*
	* Методы подготовки фильтра
	*/
	
	/**
	* Тип фильтра all, т.е. никакой дополнительной фильтрации
	* 
	* @param	array		Массив с параметрами фильтра
	* @return	array		Массив с новыми параметрами фильтра
	*/
	protected function prepareStandartFilterAll($aFilter) {
		return $aFilter;
	}
	
	/**
	* Тип фильтра popularTopics, возвращаем топики, чей рейтинг выше минимального, установленного
	* в конфиге.
	* 
	* @param	array		Массив с параметрами фильтра
	* @return	array		Массив с новыми параметрами фильтра
	*/
	protected function prepareStandartFilterPopularTopics($aFilter) {
		$aFilter['more']['topic_count_comment'] = Config::Get('plugin.blogosphere.popularTopicMinComment');
		return $aFilter;
	}
	
	/**
	* Тип фильтра popularUsers, возвращаем топики популярных пользователей
	* 
	* @param	array		Массив с параметрами фильтра
	* @return	array		Массив с новыми параметрами фильтра
	*/
	protected function prepareStandartFilterPopularUsers($aFilter) {
		$aPopularUserId = $this->User_GetPopularUsersBySubscribers(Config::Get('plugin.blogosphere.popularUsersCount'));
		$aFilter['in']['user_id'] = $aPopularUserId;
		return $aFilter;
	}
	
	/**
	* Тип фильтра community, возвращаем топики из сообществ
	* 
	* @param	array		Массив с параметрами фильтра
	* @return	array		Массив с новыми параметрами фильтра
	*/
	protected function prepareStandartFilterCommunity($aFilter) {
		$aAccessibleBlogId = $this->Blog_GetAccessibleBlogsByUser($aFilter['oUser']);
		$aFilter['in']['blog_id'] = $aAccessibleBlogId;
		return $aFilter;
	}
}
?>