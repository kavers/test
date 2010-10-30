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

class PluginAvatars_ActionSettings extends PluginAvatars_Inherit_ActionSettings {
	protected function RegisterEvent() {
		$this->AddEvent('avatars','EventAvatars');
		parent::RegisterEvent();
	}
	
	protected function EventAvatars() {
	}
}
?>