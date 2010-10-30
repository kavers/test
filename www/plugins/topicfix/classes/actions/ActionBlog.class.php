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

class PluginTopicfix_ActionBlog extends PluginTopic_inherit_ActionBlog {
	protected function EventShowBlog() {
		$sBlogUrl=$this->sCurrentEvent;
		$sShowType=in_array($this->GetParamEventMatch(0,0),array('bad','new')) ? $this->GetParamEventMatch(0,0) : 'good';
		/**
		 * Проверяем есть ли блог с таким УРЛ
		 */
		if (!($oBlog=$this->Blog_GetBlogByUrl($sBlogUrl))) {
			return parent::EventNotFound();
		}
		/**
		 * Определяем права на отображение закрытого блога
		 */
		if($oBlog->getType()=='close' 
			and (!$this->oUserCurrent 
				or !in_array(
						$oBlog->getId(),
						$this->Blog_GetAccessibleBlogsByUser($this->oUserCurrent)
					)
				)
			) {
			$bCloseBlog=true;
		} else {
			$bCloseBlog=false;
		}
		
		/**
		 * Меню
		 */
		$this->sMenuSubItemSelect=$sShowType;
		$this->sMenuSubBlogUrl=$oBlog->getUrlFull();
		/**
		 * Передан ли номер страницы
		 */
		$iPage= $this->GetParamEventMatch(($sShowType=='good')?0:1,2) ? $this->GetParamEventMatch(($sShowType=='good')?0:1,2) : 1;
		
		if (!$bCloseBlog) {
			
			/**
		 	* Получаем список топиков (исключая закрепленные)
		 	*/
			$aResult=$this->PluginTopicfix_ModuleTofix_GetTopicsByBlogNotFixed($oBlog,$iPage,Config::Get('module.topic.per_page'),$sShowType);
			$aTopics=$aResult['collection'];
			/**
		 	* Получаем список закрепленных топиков
		 	*/
			$aResultFixed=$this->PluginTopicfix_ModuleTofix_GetTopicsByBlogFixed($oBlog);
			$aTopicsFixed=$aResultFixed['collection'];
			
			/**
		 	* Формируем постраничность
		 	*/
			$aPaging=($sShowType=='good')
			? $this->Viewer_MakePaging($aResult['count'],$iPage,Config::Get('module.topic.per_page'),4,rtrim($oBlog->getUrlFull(),'/'))
			: $this->Viewer_MakePaging($aResult['count'],$iPage,Config::Get('module.topic.per_page'),4,$oBlog->getUrlFull().$sShowType);
			/**
		 	* Получаем число новых топиков в текущем блоге
		 	*/
			$this->iCountTopicsBlogNew=$this->Topic_GetCountTopicsByBlogNew($oBlog);
			
			$this->Viewer_Assign('aPaging',$aPaging);
			$this->Viewer_Assign('aTopics',$aTopics);
			$this->Viewer_Assign('aTopicsFixed',$aTopicsFixed);
		}
		/**
		 * Выставляем SEO данные
		 */
		$sTextSeo=preg_replace("/<.*>/Ui",' ',$oBlog->getDescription());
		$this->Viewer_SetHtmlDescription(func_text_words($sTextSeo,20));
		/**
		 * Получаем список юзеров блога
		 */
		$aBlogUsers=$this->Blog_GetBlogUsersByBlogId($oBlog->getId(),ModuleBlog::BLOG_USER_ROLE_USER);
		$aBlogModerators=$this->Blog_GetBlogUsersByBlogId($oBlog->getId(),ModuleBlog::BLOG_USER_ROLE_MODERATOR);
		$aBlogAdministrators=$this->Blog_GetBlogUsersByBlogId($oBlog->getId(),ModuleBlog::BLOG_USER_ROLE_ADMINISTRATOR);
		
		/**
		 * Для админов проекта получаем список блогов и передаем их во вьювер
		 */
		if($this->oUserCurrent and $this->oUserCurrent->isAdministrator()) {
			$aBlogs = $this->Blog_GetBlogs();
			unset($aBlogs[$oBlog->getId()]);
			
			$this->Viewer_Assign('aBlogs',$aBlogs);
		}
		
		/**
		 * Вызов хуков
		 */
		$this->Hook_Run('blog_collective_show',array('oBlog'=>$oBlog,'sShowType'=>$sShowType));
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('aBlogUsers',$aBlogUsers);
		$this->Viewer_Assign('aBlogModerators',$aBlogModerators);
		$this->Viewer_Assign('aBlogAdministrators',$aBlogAdministrators);
		$this->Viewer_Assign('iCountBlogUsers',count($aBlogUsers));
		$this->Viewer_Assign('iCountBlogModerators',count($aBlogModerators));
		$this->Viewer_Assign('iCountBlogAdministrators',count($aBlogAdministrators)+1);
		$this->Viewer_Assign('oBlog',$oBlog);
		$this->Viewer_Assign('bCloseBlog',$bCloseBlog);
		$this->Viewer_AddHtmlTitle($oBlog->getTitle());
		$this->Viewer_SetHtmlRssAlternate(Router::GetPath('rss').'blog/'.$oBlog->getUrl().'/',$oBlog->getTitle());
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('blog');
	}
}
?>