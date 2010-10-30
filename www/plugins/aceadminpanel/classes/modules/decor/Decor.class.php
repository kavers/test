<?php
/*
 * Vladimir Yuriev
 * vladimir.o.yuriev@gmail.com
*/

class PluginAceadminpanel_ModuleDecor extends Module {

    public function Init() {
        $this->oMapper = HelperPlugin::GetMapper();
    }

    public function AddDecor(PluginAceadminpanel_ModuleDecor_EntityDecor $oDecor) {
        if ($this->oMapper->AddDecor($oDecor)) {
            return true;
        }
        return false;
    }

    public function UpdateDecor(PluginAceadminpanel_ModuleDecor_EntityDecor $oDecor) {
        if ($this->oMapper->UpdateDecor($oDecor)) {
           return true;
        }
        return false;
    }

    public function GetDecors($aFilter) {
        return $this->oMapper->GetDecors($aFilter);
    }

    public function GetFavDecors($aFilter) {
        return $this->oMapper->GetFavDecors($aFilter);
    }

    public function GetFavByIdAndUserId($sType,$iTarget,$iDec) {
        return $this->oMapper->GetFavByIdAndUserId($sType,$iTarget,$iDec);
    }

    public function GetDecById($sId) {
        return $this->oMapper->GetDecById($sId);
    }

    public function SetDecor($sType,$iTarget,$iDec) {
        if ($this->oMapper->SetDecor($sType,$iTarget,$iDec)) {
           $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('dec_set',"dec_users_count_{$iDec}"));
           return true;
        }
        return false;
    }

    public function DeleteDecor($sType,$iTarget,$iDec) {
        if ($this->oMapper->DeleteDecor($sType,$iTarget,$iDec)) {
           $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('dec_set',"dec_users_count_{$iDec}"));
           return true;
        }
        return false;
    }

    public function AdminDeleteDecor($iDec) {
        if ($this->oMapper->AdminDeleteDecor($iDec)) {
           $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('dec_set',"dec_users_count_{$iDec}"));
           return true;
        }
        return false;
    }

    public function SetFavDecor($sType,$iTarget,$iDec) {
        if ($this->oMapper->SetFavDecor($sType,$iTarget,$iDec)) {
           return true;
        }
        return false;
    }

    public function DeleteFavDecor($sType,$iTarget,$iDec) {
        if ($this->oMapper->DeleteFavDecor($sType,$iTarget,$iDec)) {
           return true;
        }
        return false;
    }

    public function GetDecActiveByTargetTypeAndTargetId($sId,$sType) {
        return $this->oMapper->GetDecActiveByTargetTypeAndTargetId($sId,$sType);
    }


    public function GetCountUsers($iDecId) {
        //if (false === ($data = $this->Cache_Get("dec_users_count_{$iDecId}"))) {
            return $this->oMapper->GetCountUsers($iDecId);
        //    $this->Cache_Set($iCount, "dec_users_count_{$iDecId}", array("dec_set"), 60*60*24*1);
         //   return $iCount;
        //}
        //return '0';
    }

}