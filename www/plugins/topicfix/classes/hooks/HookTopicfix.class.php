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
* –егистраци€ хуков
*/
class PluginTopicfix_HookTopicfix extends Hook {
	public function RegisterHook() {
		//добавл€ем дополнительные пол€ к объекту oTopic
		$this->AddHook('topic_add_before','AddValuesToObject');
		$this->AddHook('topic_edit_before','AddValuesToObject');
		//’ук дл€ добавлени€ данных в форму редактировани€ топика
		$this->AddHook('topic_edit_show', 'AddFixedStatusToShowForm');
		
		//’ук дл€ вставки HTML кода
		$this->AddHook('template_html_pluginTopicfix_form', 'AddTopicfixForm');
		$this->AddHook('template_html_pluginTopicfix_show', 'AddTopicfixShow');
	}
	
	public function AddValuesToObject($data) {
		$data['oTopic']->setFixedStatus(0);
		if($this->checkUserRights($data['oBlog'])) {
			$data['oTopic']->setFixedStatus(getRequest('topic_fixed') ? 1 : 0);
		}
	}
	
	public function AddFixedStatusToShowForm($data) {
		if(!isset($_REQUEST['submit_topic_publish']) && !isset($_REQUEST['submit_topic_save'])) {
			$_REQUEST['topic_fixed'] = $data['oTopic']->getFixedStatus();
		}
	}
	
	//добавл€ем поле к созданию/редактированию топика
	public function AddTopicfixForm() {
		//текущего пользовател€
		$oUserCurrent=PluginLib_ModuleUser::GetUserCurrent();
		//получаем отношени€ ёзер-Ѕлог. » статус пользовател€ в каждом из этих блогов.
		$aUserBlog=$this->ModuleBlog_GetBlogUsersByUserId($oUserCurrent->getId());
		$aBlogRole=array();
		foreach($aUserBlog as $iBlogId=>$oBlogUser) {
			$aBlogRole[$iBlogId]=$oBlogUser->getUserRole();
		}
		//получаем список всех блогов, в которых пользователь €вл€етс€ владельцем
		$aBlogOwner=$this->ModuleBlog_GetBlogsByOwnerId($oUserCurrent->getId(),true);
		//получаем »ƒ персонального блога
		$oPersonalBlog=$this->ModuleBlog_GetPersonalBlogByUserId($oUserCurrent->getId());
		$aBlogOwner[]=$oPersonalBlog->getBlogId();
		$aBlogsRights=$this->prepairBlogRightsArray($aBlogOwner, $aBlogRole);
		//загружаем переменные в шаблон
		$this->Viewer_Assign('aBlogsRights',$aBlogsRights);
		$this->Viewer_Assign('oUserCurrent',$oUserCurrent);
		return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__).'topicfix_form.tpl');
	}
	
	//выводим закрепленные топики
	public function AddTopicfixShow() {
		return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__).'topicfix_show.tpl');
	}
	
	//получаем ассоциативный массив BlogId=>ѕрава_на_закрепление_топика
	private function prepairBlogRightsArray($aBlogOwner, $aBlogRole) {
		foreach($aBlogRole as $iBlogId=>$iRole) {
			//если пользователь администратор блога то разрешаем закрепление топика
			if($iRole==4) {
				$aBlogsRights[$iBlogId]=1;
			} else {
				$aBlogsRights[$iBlogId]=0;
			}
		}
		//дл€ каждого блога владельцем которого €вл€етс€ пользователь - разрешаем
		foreach($aBlogOwner as $iBlogId=>$iPermission) {
			$aBlogsRights[$iBlogId]=1;
		}
		return $aBlogsRights;
	}
	
	//провер€ем права на закрепление топика
	private function checkUserRights($oBlog) {
		//получаем текущего юзера
		$oUserCurrent=PluginLib_ModuleUser::GetUserCurrent();
		//владелец блога может закрепл€ть топики
		if(($oBlog->getOwnerId()) == ($oUserCurrent->getId())) return true;
		//администратор может закрепл€ть топики
		if($oUserCurrent->isAdministrator()) return true;
		//администратор блога может закрепл€ть топики
		$aUsersObjs=$this->ModuleBlog_GetBlogUsersByBlogId($oBlog->getId());
		if(isset($aUsersObjs[$oUserCurrent->getId()])) {
			$oUserBlog=$aUsersObjs[$oUserCurrent->getId()];
			if($oUserBlog->getIsAdministrator()) return true;
		}
		//в противном случае пользователь не может закрепл€ть топики
		return false;
	}
}
?>