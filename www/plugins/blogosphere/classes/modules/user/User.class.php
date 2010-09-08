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


class PluginBlogosphere_ModuleUser extends PluginBlogosphere_Inherit_ModuleUser {
	/**
	 * Получить список популярных пользователей (с наибольшим числом подписчиков)
	 *
	 * @param	int		Количество пользователей
	 * @return	array
	 */
	public function GetPopularUsersBySubscribers($limit) {
		if (false === ($data = $this->Cache_Get("popular_users_bysubscribers_($limit)"))) {
			$data = $this->oMapper->GetPopularUsersBySubscribers($limit);
			$this->Cache_Set($data, "popular_users_bysubscribers_($limit)", array(), 60*60*24*2);
		}
		return $data;
	}
}

?>