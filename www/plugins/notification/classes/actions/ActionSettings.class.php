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

class PluginNotification_ActionSettings extends PluginNotification_Inherit_ActionSettings {
	protected function EventTuning() {
		$this->SetTemplateAction('tuning');
		$aSubscribeUserBlog = $this->Blog_GetBlogUsersByUserId($this->oUserCurrent->getId());
		$this->Viewer_Assign('aSubscribeUserBlog', $aSubscribeUserBlog);
		if(isPost('submit_settings_tuning')) {
			$this->oUserCurrent->setSettingsNoticeNewTopicCommented( getRequest('settings_notice_new_topic_commented') ? 1 : 0 );
			$this->oUserCurrent->setSettingsNoticeFriendNews( getRequest('settings_notice_friend_news') ? 1 : 0 );
			$this->oUserCurrent->setSettingsNoticeRequest( getRequest('settings_notice_request') ? 1 : 0 );
			$this->oUserCurrent->setSettingsNoticeNewGift( getRequest('settings_notice_new_gift') ? 1 : 0 );
			$this->oUserCurrent->setSettingsNoticeNewUserBlogsSubscribe( getRequest('user_settings_notice_new_user_blogs_subscribe') ? 1 : 0 );
			
			$sFrequency = getRequest('user_settings_notice_frequency');
			
			$this->oUserCurrent->setSettingsNoticeFrequency( $this->Notify_GetFrequencyNumber($sFrequency) );
			
			if(Config::Get('plugin.notification.oneSettingForBlogsComments')) {
				$this->oUserCurrent->setSettingsNoticeNewCommentBlogsSubscribe( getRequest('user_settings_notice_new_comment_blogs_subscribe') ? 1 : 0 );
			}
			
			if(!$this->updateBlogsSubscribe()) {
					$this->Message_AddErrorSingle($this->Lang_Get('system_error'));
			}
		}
		parent::EventTuning();
	}
	
	/**
	* Обновляем состояние подписки на новые блоги и комментарии к ним
	*/
	protected function updateBlogsSubscribe() {
		$aBlogTopicSubscribe = getRequest('settings_notice_new_topic_subscribe');
		
		$aBlogUser = $this->Blog_GetBlogUsersByUserId($this->oUserCurrent->getId());
		
		$aAssocBlogUser = array();
		foreach($aBlogUser as $oBlogUser) {
			$oBlogUser->setUserSettingsNoticeNewTopicSubscribe('0');
			$aAssocBlogUser[$oBlogUser->getBlogId()] = $oBlogUser;
		}
		if(is_array($aBlogTopicSubscribe)) {
			foreach($aBlogTopicSubscribe as $sBlogId => $bBlogTopicSubscribe) {
				if(isset($aAssocBlogUser[$sBlogId])) {
					$aAssocBlogUser[$sBlogId]->setUserSettingsNoticeNewTopicSubscribe($bBlogTopicSubscribe ? '1' : '0');
				}
			}
		}
		
		if(!Config::Get('plugin.notification.oneSettingForBlogsComments')) {
			$aAssocBlogUser = $this->blogsCommentNoticeCheck($aAssocBlogUser);
		}
		
		foreach($aAssocBlogUser as $oBlogUser) {
			$this->Blog_UpdateRelationBlogUser($oBlogUser);
		}
		return true;
	}
	
	protected function blogsCommentNoticeCheck($aAssocBlogUser) {
		foreach($aAssocBlogUser as $oBlogUser) {
			$oBlogUser->setUserSettingsNoticeNewCommentSubscribe('0');
		}
		
		$aBlogCommentSubscribe = getRequest('settings_notice_new_comment_subscribe');
		if(is_array($aBlogCommentSubscribe)) {
			foreach($aBlogCommentSubscribe as $sBlogId => $bBlogCommentSubscribe) {
				if(isset($aAssocBlogUser[$sBlogId])) {
					$aAssocBlogUser[$sBlogId]->setUserSettingsNoticeNewCommentSubscribe($bBlogCommentSubscribe ? '1' : '0');
				}
			}
		}
		
		return $aAssocBlogUser;
	}
}
?>