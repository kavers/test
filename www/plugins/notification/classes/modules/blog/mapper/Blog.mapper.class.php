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

class PluginNotification_ModuleBlog_MapperBlog extends  PluginNotification_Inherit_ModuleBlog_MapperBlog {
	public function UpdateRelationBlogUser(ModuleBlog_EntityBlogUser $oBlogUser) {
		$sql = "UPDATE ".Config::Get('db.table.blog_user')." 
			SET 
				user_role = ?d,
				user_settings_notice_new_topic_subscribe = ?d,
				user_settings_notice_new_comment_subscribe = ?d
			WHERE
				blog_id = ?d 
				AND
				user_id = ?d
		";

		if ($this->oDb->query(
				$sql,
				$oBlogUser->getUserRole(),
				$oBlogUser->getUserSettingsNoticeNewTopicSubscribe(),
				$oBlogUser->getUserSettingsNoticeNewCommentSubscribe(),
				$oBlogUser->getBlogId(),
				$oBlogUser->getUserId())
		) {
			return true;
		}
		return false;
	}
}

?>