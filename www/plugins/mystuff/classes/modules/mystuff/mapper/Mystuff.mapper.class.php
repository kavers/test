<?php

	/*********************************************************
	*
	* @author Kulikov Alexey <ak@essentialmind.com>
	* @copyright essentialmind gmbh
	* @since 2010-07-01
	*
	*********************************************************/
	class PluginMystuff_ModuleMystuff_MapperMystuff extends Mapper {
		
		/***
		*  Mark the neccessary table for update
		***/
		public function MarkTopicWithTimestamp($oTopic) {        
			$sql = "UPDATE ".Config::Get('db.table.topic')."
						SET
							topic_last_update = ?            
						WHERE
							topic_id = ?d
					";
						
			if ($this->oDb->query($sql, $oTopic->getLastUpdate(), $oTopic->getId())) {                
				return true;
			}
			return false;
		}
		
		
		
		/***
		*  Mark a Topic for use in My Stuff
		***/
		public function MarkTopicInMyStuff($oUser, $oTopic){
			$sql = "SELECT 
						topic_id 
					FROM 
						".Config::Get('plugin.mystuff.table.topic_commented')."
					WHERE
						user_id     = ?d AND
						topic_id    = ?d ";
						
			
			//basucally check if there is an entry in the db for these settings
			//and if there is none, go and make one
			if(!$this->oDb->selectCell($sql, $oUser->getId(), $oTopic->getId())) { //if the entry is NOT yet marked
			
				$sql = "INSERT INTO ".Config::Get('plugin.mystuff.table.topic_commented')." SET
						user_id     = ?d ,
						topic_id    = ?d ";
			
				//making the entry here
				if(!$this->oDb->query($sql, $oUser->getId(), $oTopic->getId())) {
					return true;
				}                
			}
			
			return false;
		}
		
		
		/***
		*  Returns a list of topic IDs that belong to "my stuff"
		*  @return Array()
		***/
		public function getTopicIDsForMyStuff($oUser, $accessModule = false, $aFilter = array()){
			$accessWhere = (!$oUser->isAdministrator() && $accessModule) ?
								' AND ' . PluginAccesstotopic_ModuleAccess::GetAccessWhereStatment($oUser->getId()) : ' ';
			$sql = 'SELECT 
						user_to 
						FROM '.Config::Get('db.table.friend').' 
						WHERE 
							user_from = ?d 
							AND (status_from = 1
							OR status_from   = 2)
					UNION 
					SELECT 
						user_from 
						FROM '.Config::Get('db.table.friend').'
						WHERE 
							user_to = ?d
							AND (status_to = 1
							OR status_to = 2)';
			
			//first, get a list of all my friends
			$friends    = $this->oDb->selectCol($sql, $oUser->getId(), $oUser->getId());
			$friends[]  = $oUser->getId(); //I am my own friend :-)
			dump('My friends are: '.print_r($friends, true));

			//now get a list of topics my friends commented on no more than 4 weeks ago
			if(isset($aFilter['more']['topic_add_date']) && isset($aFilter['less']['topic_add_date'])) {
				$dateWhere = ' AND tc.created >= "'.mysql_real_escape_string($aFilter['more']['topic_add_date']) .'" AND '.
					'tc.created <= "'.mysql_real_escape_string($aFilter['less']['topic_add_date']) . '"';
			} else {
				$dateWhere = ' AND tc.created >= DATE_SUB(NOW(), INTERVAL 4 WEEK)';
			}
			
			$sql = 'SELECT
						tc.topic_id 
					FROM 
						'. Config::Get('plugin.mystuff.table.topic_commented') .' as tc,
						'. Config::Get('db.table.topic') .' as t
					WHERE
						tc.user_id IN (?a)
						AND
						tc.topic_id = t.topic_id
						'. $dateWhere .'
						'. $accessWhere .'
					';
			if($topics = $this->oDb->selectCol($sql, $friends)){
				$topics = array_unique($topics);
				dump('My Stuff Topics are: '.print_r($topics, true));
				return $topics;
			}
			
			//fallback
			return array();
		}
	
	
	}
