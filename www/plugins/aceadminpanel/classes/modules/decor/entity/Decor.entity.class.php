<?php
/*
 * Vladimir Yuriev
 * vladimir.o.yuriev@gmail.com
*/

class PluginAceadminpanel_ModuleDecor_EntityDecor extends Entity
{
    
    public function getAvatarPath($iSize=48) {
    	return Config::Get('path.uploads.root').'/decor/avatar_'.$this->getTplId().'_'.$iSize.'x'.$iSize.'.gif';
    }

    public function getDecPrice() {
        return number_format(round($this->_aData['dec_price'],2), 2, '.', '');
    }

    public function getDecFav($sType,$sTargetId=0) {
        return $this->PluginAceAdminPanel_Decor_GetFavByIdAndUserId($sType,$sTargetId,$this->getDecId());
    }

    public function getCountUsers() {
        return $this->PluginAceAdminPanel_Decor_GetCountUsers($this->getDecId());
    }
}

// EOF