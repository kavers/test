<?php

if (!class_exists('Plugin')) {
	die('Hacking attemp!');
}

class PluginTopicadditionalfields_HookTopicadditionalfields extends Hook {
	
	public function RegisterHook() {
		//добавляем дополнительные поля к объекту oTopic
		$this->AddHook('topic_add_before','AddValuesToObject');
		$this->AddHook('topic_edit_before','AddValuesToObject');
		$this->AddHook('topic_edit_show', 'AddAdditionalfieldsToShowForm');
		//добавляем хук к шаблону
		$this->AddHook('template_html_pluginTopicadditionalfields_form','AddInputTpl');
		$this->AddHook('template_html_pluginTopicadditionalfields_show','AddShowTpl');
	}
	
	public function AddInputTpl(){
		return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__).'TopicAdditionalFields.tpl');
	}
	
	public function AddShowTpl(){
		return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__).'ShowAdditionalFields.tpl');
	}
	
	public function AddAdditionalfieldsToShowForm($data) {
		if(getRequest('now_listening', null) === null) {
			$_REQUEST['now_listening'] = $data['oTopic']->getNowListening();
		}
		if(getRequest('current_place', null) === null) {
			$_REQUEST['current_place'] = $data['oTopic']->getCurrentPlace();
		}
		if(getRequest('mood', null) === null) {
			$_REQUEST['mood'] = $data['oTopic']->getMood();
		}
	}
	
	public function AddValuesToObject($data) {
		if(getRequest('now_listening', null) !== null || !$data['oTopic']->getNowListening()) {
			$data['oTopic']->setNowListening(getRequest('now_listening', ''));
		}
		
		if(getRequest('current_place', null) !== null || !$data['oTopic']->getCurrentPlace()) {
			$data['oTopic']->setCurrentPlace(getRequest('current_place', ''));
		}
		
		if(getRequest('mood', null) !== null) {
			$data['oTopic']->setMood(getRequest('mood', ''));
		}
	}
}
?>