<?php

if (!class_exists('Plugin')) {
	die('Hacking attemp!');
}

class PluginCommunitycats_HookBlogAction extends Hook {
	
	public function RegisterHook() {
		//добавляем категорию к объекту oBlog
		$this->AddHook('blog_add_before','AddValuesToObject');
		$this->AddHook('blog_edit_before','AddValuesToObject');
	}
	
	public function AddValuesToObject($data) {
		if(($sCatName = getRequest('blog_cat', null)) !== null) {
			$sCatName = strtoupper(substr(trim($sCatName), 0, 20));
			if($sFullCatName = $this->PluginCommunitycats_ModuleCategory_GetFullCategoryName($sCatName)) {
				$data['oBlog']->setCategory($sFullCatName);
			}
		}
	}
}
?>