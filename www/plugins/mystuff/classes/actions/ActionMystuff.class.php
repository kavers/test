<?php

/*********************************************************
*
* @author Kulikov Alexey <ak@essentialmind.com>
* @copyright essentialmind gmbh
* @since 2010-07-01
*
*********************************************************/
class PluginMystuff_ActionMystuff extends ActionPlugin {


	/***
	*  Le constructor
	***/
	public function Init() {
		$this->SetDefaultEvent('index'); 
		$this->Viewer_AddHtmlTitle($this->Lang_Get('my_stuff'));
	}

	
	/***
	*  Per Default call the event EventIndex
	***/
	protected function RegisterEvent() {
		$this->AddEvent('index','EventIndex');
		$this->AddEvent('new','EventNew');
	}


	/***
	*  Default Event
	***/
	protected function EventIndex($newOnly=false) {
		//visible to logged in users only or anonim if he use path through "my" action.
		if (!$this->User_IsAuthorization() && $this->GetParam(1, '') != 'friends') {
			return $this->EventNotFound();
		}

		if($this->GetParam(1, '') != 'friends') {
			//It's our stuff (uri /mine)
			$oUserOwner = $this->User_GetUserCurrent();
		} else {
			//uri /my/username/friends
			$sUserLogin = $this->GetParam(0, '');
			$oUserOwner = $this->User_GetUserByLogin($sUserLogin);
		}
		
		if(!$oUserOwner) {
			return $this->EventNotFound();
		}
		
		$this->SetTemplateAction('index');
		
		//load data from db
		$aResult = $this->PluginMystuff_ModuleMystuff_GetTopicsByFriend(false, $newOnly, $oUserOwner);
		$aTopics = $aResult['collection'];
		
		//pass it to smarty
		$this->Viewer_Assign('aTopics',$aTopics);
		$this->Viewer_Assign('menu', 'mystuff');
		$this->Viewer_Assign('oUserProfile', $oUserOwner);
		$this->Viewer_Assign('oUserOwner', $oUserOwner);
	}
	
	
	/***
	*  Tiny filter to acttually filter the list we have
	***/
	protected function EventNew() {
		$this->Viewer_Assign('sMenu','new');
		return $this->EventIndex(true);
	}

	
	/***
	*  Shutdown Function
	***/
	public function EventShutdown() {
		dump('MyStuff Event Completed');
	}
}

?>
