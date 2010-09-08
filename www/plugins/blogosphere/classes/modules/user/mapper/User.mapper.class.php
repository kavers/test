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


class PluginBlogosphere_ModuleUser_MapperUser extends PluginBlogosphere_Inherit_ModuleUser_MapperUser {
	/**
	 * Получить список популярных пользователей (с наибольшим числом подписчиков)
	 *
	 * @param	int		Количество полььзователей
	 * @return	array
	 */
	public function GetPopularUsersBySubscribers($limit = 5) {
		$sql = 'SELECT
					u.user_id,
					count(f.user_to) + count(f2.user_from) as friends
				FROM 
					'.Config::Get('db.table.user').' u
					LEFT JOIN '.Config::Get('db.table.friend').' f ON u.user_id = f.user_to AND f.status_from = '. ModuleUser::USER_FRIEND_ACCEPT .' OR f.status_from = '. ModuleUser::USER_FRIEND_OFFER .'
					LEFT JOIN '.Config::Get('db.table.friend').' f2 ON u.user_id = f2.user_from AND f2.status_to = '.ModuleUser::USER_FRIEND_ACCEPT.'
				GROUP BY (u.user_id)
				ORDER BY friends DESC
				LIMIT 0, ?d;';
		$aUsers=array();
		if ($aRows=$this->oDb->select(
				$sql,
				$limit
			)
		) {
			foreach ($aRows as $aUser) {
				$aUsers[] = $aUser['user_id'];
			}
		}
		return $aUsers;
	}
}

?>