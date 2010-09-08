<?php
/*********************************************************
*
* @author Kulikov Alexey <ak@essentialmind.com>
* @copyright essentialmind gmbh
* @since 2010-07-01
*
*********************************************************/
class PluginMystuff_ModuleMystuff extends Module {

	//database access layer 
	protected $oMapper;
    protected $accessModuleAvailable;

	
	/***
	* Constructor
	***/
	public function Init() {
		$this->oMapper = Engine::GetMapper(__CLASS__); //get me my DB link baby
		$this->oUserCurrent = $this->User_GetUserCurrent(); //do I need this?
	}

	
	/***
	* Pass the update to the database 
	***/
	public function MarkTopicWithTimestamp($oTopic) {  
		return $this->oMapper->MarkTopicWithTimestamp($oTopic);
	}
	
	
	/***
	*  Add a topic to the list of topics I have commented on (if not there already)
	***/
	public function MarkTopicInMyStuff($oTopic) {
		return $this->oMapper->MarkTopicInMyStuff($this->oUserCurrent, $oTopic);
	}
	
	
	/***
	*  Get a list of topics that my frieds commented on
	*  and order them in the order of the latest comment added
	***/
	public function GetTopicsByFriend($countOnly=false, $newOnly=false) {
		//these are the topics relevant for MyStuff list
		$myStuffTopics = $this->oMapper->getTopicIDsForMyStuff($this->oUserCurrent, $this->isAccessModuleAvailable());
		
		//I need to filter this list to show only topics that have something NEW in them
		$reply = $this->PluginMystuff_ModuleTopic_GetOnlyUnreadTopicsFromList($myStuffTopics);
		$this->Viewer_Assign('myStuffNewComments', $reply['newComments']);
		$this->Viewer_Assign('myStuffNewTopics', $reply['newTopics']);
		if($newOnly){
			$myStuffTopics = $reply['topics'];
		}
		
		
		
		//build the filter (cache works based on the filter as key)
		$aFilter=array(
			'blog_type'     => array(
									'personal',
									//'open',
									//'subcat'
								),
			'topic_publish' => 1,
			'topic_publish_index' => 1,
			'topic_id'      => $myStuffTopics,
			'order'         => 't.topic_last_update desc'
		);

		/*if($this->oUserCurrent) {
			$aOpenBlogs = $this->Blog_GetAccessibleBlogsByUser($this->oUserCurrent);
			if(count($aOpenBlogs)) $aFilter['blog_type']['close'] = $aOpenBlogs;
		}*/

		if($countOnly){
			return $this->PluginMystuff_ModuleTopic_GetCountTopicsByFilter($aFilter);
		}
		
		return $this->PluginMystuff_ModuleTopic_GetTopicsByFilter($aFilter);
	}
	
	
	/***
	*  Calculate the number of unread topics by some user in MyStuff
	***/
	public function GetNumberOfUnreadTopics() {
		//$topicsWrittenByFriends = $this->GetTopicsByFriend(true);
		//return $topicsWrittenByFriends;
	}
    
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
    
    /***
	*  Get a list of topics that my frieds commented on
	*  and order them in the order of the latest comment added
	***/
	public function GetTopicsForBlogosphere($aFilter) {
		//these are the topics relevant for MyStuff list
		$myStuffTopics = $this->oMapper->getTopicIDsForMyStuff($this->oUserCurrent, $this->isAccessModuleAvailable(), $aFilter);
		
		//build the filter (cache works based on the filter as key)
		$aFilter=array(
			'blog_type'     => array(
									'personal',
									//'open',
									//'subcat'
								),
			'topic_publish' => 1,
			'topic_publish_index' => 1,
			'topic_id'      => $myStuffTopics,
			'order'         => 't.topic_last_update desc'
		);
        $aResult = $this->PluginMystuff_ModuleTopic_GetTopicsByFilter($aFilter);
        return $aResult['collection'];
    }

}

?>