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
 * Обработка УРЛа вида /my/
 *
 */
class PluginTopicfix_ActionMy extends PluginTopic_inherit_ActionMy {
	protected function EventTopics() {
		/**
		 * Получаем логин из УРЛа
		 */
		$sUserLogin=$this->sCurrentEvent;
		/**
		 * Проверяем есть ли такой юзер
		 */	
		if (!($this->oUserProfile=$this->User_GetUserByLogin($sUserLogin))) {
			return parent::EventNotFound();
		}
		/**
		 * Передан ли номер страницы
		 */
		if ($this->GetParamEventMatch(0,0)=='blog') {
			$iPage=$this->GetParamEventMatch(1,2) ? $this->GetParamEventMatch(1,2) : 1;	
		} else {
			$iPage=$this->GetParamEventMatch(0,2) ? $this->GetParamEventMatch(0,2) : 1;	
		}
		/**
		 * Получаем список топиков (исключая зафиксированные)
		 */
		$aResultNotFixed=$this->PluginTopicfix_ModuleTofix_GetTopicsPersonalByUserNotFixed($this->oUserProfile->getId(),1,$iPage,Config::Get('module.topic.per_page'));
		$aTopics=$aResultNotFixed['collection'];
		/**
		 * Получаем список зафиксированных топиков
		 */
		$aResultFixed=$this->PluginTopicfix_ModuleTofix_GetTopicsPersonalByUserFixed($this->oUserProfile->getId(),1);
		$aTopicsFixed=$aResultFixed['collection'];
		/**
		 * Формируем постраничность
		 */
		$aPaging=$this->Viewer_MakePaging($aResultNotFixed['count'],$iPage,Config::Get('module.topic.per_page'),4,Router::GetPath('my').$this->oUserProfile->getLogin());
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('aPaging',$aPaging);
		$this->Viewer_Assign('aTopics',$aTopics);
		$this->Viewer_Assign('aTopicsFixed',$aTopicsFixed);
		$this->Viewer_Assign('oUserOwner',$this->oUserProfile);
		
		$this->Viewer_AddHtmlTitle($this->Lang_Get('user_menu_publication').' '.$this->oUserProfile->getLogin());
		$this->Viewer_AddHtmlTitle($this->Lang_Get('user_menu_publication_blog'));
		$this->Viewer_SetHtmlRssAlternate(Router::GetPath('rss').'personal_blog/'.$this->oUserProfile->getLogin().'/',$this->oUserProfile->getLogin());
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('blog');
	}
}
?>