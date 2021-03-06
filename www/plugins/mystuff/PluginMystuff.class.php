<?php
/*********************************************************
*
* @author Kulikov Alexey <ak@essentialmind.com>
* @copyright essentialmind gmbh
* @since 2010-07-01
*
*********************************************************/
if (!class_exists('Plugin')) {
	die('Hacking attemp!');
}

class PluginMystuff extends Plugin {
	protected $aInherits=array(
		'action' => array('ActionMy'=>'_ActionMy'),
	);
	
	/***
	*  Activating the Plugin
	***/
	public function Activate() {#
		$this->Cache_Clean();
		//this will add a last_modified field to every topic
		$this->ExportSQL(dirname(__FILE__).'/dump.sql');
		return true;
	}

	
	/***
	*  Nothing happens here, really
	***/
	public function Init() {
		$this->Viewer_AddMenu('mystuff',Plugin::GetTemplatePath(__CLASS__).'menu.mystuff.tpl');
	}
	
	
	/***
	* Remove the additional column from the database, this is neccesary
	***/
	public function Deactivate() {
		$this->Cache_Clean();
		$this->ExportSQL(dirname(__FILE__).'/remove.sql');
		return true;
	}
}

?>
