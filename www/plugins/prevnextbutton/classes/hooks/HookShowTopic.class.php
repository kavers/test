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
* Регистрация хуков
*/
class PluginPrevnextbutton_HookShowTopic extends Hook {

	public function RegisterHook() {
		//Хук для переоределения id предыдущего и следующего топиков 
		$this->AddHook('topic_show', 'ChangePrevNextTopicsIds');
	}
	
	/**
	* Функция переопределяет id предыдущего и следующего топиков, гарантируя, что
	* они будут из того же блога, что и текущий.
	* 
	* @param	array	array(oTopic)
	*/
	public function ChangePrevNextTopicsIds($data) {
		if(!isset($data['oTopic'])) {
			return;
		}
		
		$oTopicCurrrent = $data['oTopic'];
		
		$aTopicsIdFromCurrentBlog = $this->Topic_GetTopicsByBlogId($oTopicCurrrent->getBlogId());
		
		$iPrevId = $iNextId = false;
		if($aTopicsIdFromCurrentBlog) {
			$iCurrKey = array_search($oTopicCurrrent->getId(), $aTopicsIdFromCurrentBlog);
			//По-умолчанию топики сортируются по дате добавления по убыванию, поэтому следующее допустимо.
			$iNextId = $iCurrKey > 0 ? $aTopicsIdFromCurrentBlog[$iCurrKey - 1] : false;
			$iPrevId = $iCurrKey < count($aTopicsIdFromCurrentBlog) - 1 ? $aTopicsIdFromCurrentBlog[$iCurrKey + 1] : false;
		}
		
		//Переопределям переменные шаблонизатора
		$this->Viewer_Assign('prev', $iPrevId);
		$this->Viewer_Assign('next', $iNextId);
	}
}
?>