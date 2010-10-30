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

class PluginLib_ModuleUser_EntityUser extends PluginLib_Inherit_ModuleUser_EntityUser {
	public function isAnonim() {
		return $this->getId() == PluginLib_ModuleUser::ANONIM_USER_ID;
	}
}
?>