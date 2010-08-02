<?php
/*---------------------------------------------------------------------------
* @Module Name: MySearch
* @Module Id: ls_mysearch
* @Module URI: http://livestreet.ru/addons/74/
* @Description: Simple Search via MySQL (without Sphinx) for LiveStreet
* @Version: 1.1.34
* @Author: aVadim
* @Author URI: 
* @LiveStreet Version: 0.3.1
* @File Name: HookMysearch.class.php
* @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*----------------------------------------------------------------------------
*/
class HookMysearch extends Hook {
  
	public function RegisterHook() {
		if (!MYSEARCH_HOOK_ENABLE) return;

    $this->AddHook('init_action', 'CheckSearch', __CLASS__);
  }
  
  public function CheckSearch($aVars) {
		if (!MYSEARCH_HOOK_ENABLE) return;
		
		if (Router::GetAction()=='search'){
			if (getRequest('q') && isset($_SERVER["HTTP_REFERER"]) && (preg_match('|/search/(\w+)/|', $_SERVER["HTTP_REFERER"], $m))) {
				$sActionEvent=$m[1];
			} else {
				$sActionEvent=Router::GetActionEvent();
			}
			Router::Action('mysearch', $sActionEvent, Router::GetParams());
		}
  }

}

?>