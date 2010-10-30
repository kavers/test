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

class PluginCommunitycats_ModuleBlog_MapperBlog extends PluginCommunitycats_Inherit_ModuleBlog_MapperBlog {
	public function AddBlog(ModuleBlog_EntityBlog $oBlog) {
		if($iId = parent::AddBlog($oBlog)) {
			$oBlog->setId($iId);
			if($this->updateCommunityCat($oBlog)) {
				return $iId;
			}
		}
		
		return false;
	}
	
	public function UpdateBlog(ModuleBlog_EntityBlog $oBlog) {
		if($this->updateCommunityCat($oBlog)) {
			return parent::UpdateBlog($oBlog);
		}
		
		return false;
	}
	
	protected function updateCommunityCat(ModuleBlog_EntityBlog $oBlog) {
		$sql = 'UPDATE '.Config::Get('db.table.blog').' 
			SET 
				blog_cat = ?
			WHERE
				blog_id = ?d';
		
		if($this->oDb->query($sql,$oBlog->getCategory(),$oBlog->getId()) !== null) {
			return true;
		}
		
		return false;
	}
}
?>