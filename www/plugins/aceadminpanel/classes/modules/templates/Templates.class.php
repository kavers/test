<?php
/*
 * Vladimir Yuriev
 * vladimir.o.yuriev@gmail.com
*/

class PluginAceadminpanel_ModuleTemplates extends Module {

    public function Init() {
        $this->oMapper = HelperPlugin::GetMapper();
    }

    public function AddTemplate(PluginAceadminpanel_ModuleTemplates_EntityTemplates $oTemplate) {
        if ($this->oMapper->AddTemplate($oTemplate)) {
            return true;
        }
        return false;
    }

    public function UpdateTemplate(PluginAceadminpanel_ModuleTemplates_EntityTemplates $oTemplate) {
        if ($this->oMapper->UpdateTemplate($oTemplate)) {
           return true;
        }
        return false;
    }

    public function GetTemplates($aFilter) {
        return $this->oMapper->GetTemplates($aFilter);
    }

    public function GetFavTemplates($aFilter) {
        return $this->oMapper->GetFavTemplates($aFilter);
    }

    public function GetFavByIdAndUserId($sType,$iTarget,$iTpl) {
        return $this->oMapper->GetFavByIdAndUserId($sType,$iTarget,$iTpl);
    }

    public function GetTplById($sId) {
        return $this->oMapper->GetTplById($sId);
    }

    public function SetTemplate($sType,$iTarget,$iTpl) {
        if ($this->oMapper->SetTemplate($sType,$iTarget,$iTpl)) {
           $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('tpl_set',"tpl_users_count_{$iTpl}"));
           return true;
        }
        return false;
    }

    public function DeleteTemplate($sType,$iTarget,$iTpl) {
        if ($this->oMapper->DeleteTemplate($sType,$iTarget,$iTpl)) {
           $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('tpl_set',"tpl_users_count_{$iTpl}"));
           return true;
        }
        return false;
    }

    public function AdminDeleteTemplate($iTpl) {
        if ($this->oMapper->AdminDeleteTemplate($iTpl)) {
           $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('tpl_set',"tpl_users_count_{$iTpl}"));
           return true;
        }
        return false;
    }

    public function SetFavTemplate($sType,$iTarget,$iTpl) {
        if ($this->oMapper->SetFavTemplate($sType,$iTarget,$iTpl)) {
           return true;
        }
        return false;
    }

    public function DeleteFavTemplate($sType,$iTarget,$iTpl) {
        if ($this->oMapper->DeleteFavTemplate($sType,$iTarget,$iTpl)) {
           return true;
        }
        return false;
    }

    public function GetTplActiveByTargetTypeAndTargetId($sId,$sType) {
        return $this->oMapper->GetTplActiveByTargetTypeAndTargetId($sId,$sType);
    }


    public function GetCountUsers($iTplId) {
        if (false === ($data = $this->Cache_Get("tpl_users_count_{$iTplId}"))) {
            $iCount = $this->oMapper->GetCountUsers($iTplId);
            $this->Cache_Set($iCount, "tpl_users_count_{$iTplId}", array("tpl_set"), 60*60*24*1);
            return $iCount;
        }
        return '0';
    }

}