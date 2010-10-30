<?php
/*
 * Vladimir Yuriev
 * vladimir.o.yuriev@gmail.com
*/

class PluginAceadminpanel_ModuleWidgets_EntityWidgets extends Entity
{
    
    public function getAvatarPath($iSize=48) {
    	return Config::Get('path.uploads.root').'/widgets/avatar_'.$this->getTplId().'_'.$iSize.'x'.$iSize.'.gif';
    }

    public function getWidPrice() {
        return number_format(round($this->_aData['wid_price'],2), 2, '.', '');
    }

    public function getWidFav($sType,$sTargetId=0) {
        return $this->PluginAceAdminPanel_Widgets_GetFavByIdAndUserId($sType,$sTargetId,$this->getWidId());
    }

    public function getWidActive($sType,$sTargetId=0) {
        return $this->PluginAceAdminPanel_Widgets_GetWidActiveByTargetTypeAndTargetId($sTargetId,$sType,$this->getWidId());
    }

    public function getCountUsers() {
        return $this->PluginAceAdminPanel_Widgets_GetCountUsers($this->getWidId());
    }
}

// EOF