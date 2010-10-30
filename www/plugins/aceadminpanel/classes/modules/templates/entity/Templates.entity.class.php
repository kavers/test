<?php
/*
 * Vladimir Yuriev
 * vladimir.o.yuriev@gmail.com
*/

class PluginAceadminpanel_ModuleTemplates_EntityTemplates extends Entity
{
    
    public function getAvatarPath($iSize=48) {
    	return Config::Get('path.uploads.root').'/tpls/avatar_'.$this->getTplId().'_'.$iSize.'x'.$iSize.'.gif';
    }

    public function getTplPrice() {
        return number_format(round($this->_aData['tpl_price'],2), 2, '.', '');
    }

    public function getTplFav($sType,$sTargetId=0) {
        return $this->PluginAceAdminPanel_Templates_GetFavByIdAndUserId($sType,$sTargetId,$this->getTplId());
    }

    public function getCountUsers() {
        return $this->PluginAceAdminPanel_Templates_GetCountUsers($this->getTplId());
    }
}

// EOF