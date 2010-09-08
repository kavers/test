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
		//visible to logged in users only
		if (!$this->User_GetUserCurrent()) {			
			return $this->EventNotFound();
		}
	
		$this->SetTemplateAction('index');
		
		//load data from db
		$aResult = $this->PluginMystuff_ModuleMystuff_GetTopicsByFriend(false, $newOnly);
		$aTopics = $aResult['collection'];
		
		//pass it to smarty
		$this->Viewer_Assign('aTopics',$aTopics);
		$this->Viewer_Assign('menu', 'mystuff');
		$this->Viewer_Assign('oUserProfile', $this->User_GetUserCurrent());
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
