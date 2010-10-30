<?php
/*
 * Vladimir Yuriev
 * vladimir.o.yuriev@gmail.com
*/

class PluginAceadminpanel_ModuleWidgets extends Module {

    public function Init() {
        $this->oMapper = HelperPlugin::GetMapper();
    }

    public function AddWidget(PluginAceadminpanel_ModuleWidgets_EntityWidgets $oWidget) {
        if ($this->oMapper->AddWidget($oWidget)) {
            return true;
        }
        return false;
    }

    public function UpdateWidget(PluginAceadminpanel_ModuleWidgets_EntityWidgets $oWidget) {
        if ($this->oMapper->UpdateWidget($oWidget)) {
           return true;
        }
        return false;
    }

    public function GetWidgets($aFilter) {
        return $this->oMapper->GetWidgets($aFilter);
    }

    public function GetFavWidgets($aFilter) {
        return $this->oMapper->GetFavWidgets($aFilter);
    }

    public function GetFavByIdAndUserId($sType,$iTarget,$iWid) {
        return $this->oMapper->GetFavByIdAndUserId($sType,$iTarget,$iWid);
    }

    public function GetWidById($sId) {
        return $this->oMapper->GetWidById($sId);
    }

    public function SetWidget($sType,$iTarget,$iWid) {
        if ($this->oMapper->SetWidget($sType,$iTarget,$iWid)) {
           $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('wid_set',"wid_users_count_{$iWid}"));
           return true;
        }
        return false;
    }

    public function DeleteWidget($sType,$iTarget,$iWid) {
        if ($this->oMapper->DeleteWidget($sType,$iTarget,$iWid)) {
           $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('wid_set',"wid_users_count_{$iWid}"));
           return true;
        }
        return false;
    }

    public function AdminDeleteWidget($iWid) {
        if ($this->oMapper->AdminDeleteWidget($iWid)) {
           $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('wid_set',"wid_users_count_{$iWid}"));
           return true;
        }
        return false;
    }

    public function SetFavWidget($sType,$iTarget,$iWid,$iPriority) {
        if ($this->oMapper->SetFavWidget($sType,$iTarget,$iWid,$iPriority)) {
           return true;
        }
        return false;
    }

    public function DeleteFavWidget($sType,$iTarget,$iWid) {
        if ($this->oMapper->DeleteFavWidget($sType,$iTarget,$iWid)) {
           return true;
        }
        return false;
    }

    public function GetWidActiveByTargetTypeAndTargetId($sId,$sType,$widId) {
        return $this->oMapper->GetWidActiveByTargetTypeAndTargetId($sId,$sType,$widId);
    }
    public function GetWidgetsActive($sId,$sType) {
        return $this->oMapper->GetWidgetsActive($sId,$sType);
    }


    public function GetCountUsers($iWidId) {
        //if (false === ($data = $this->Cache_Get("wid_users_count_{$iWidId}"))) {
            $iCount=0;
            $iCount = $this->oMapper->GetCountUsers($iWidId);
        //    $this->Cache_Set($iCount, "wid_users_count_{$iWidId}", array("wid_set"), 60*60*24*1);
            return $iCount;
        //}
        //return '0';
    }

    public function GetNextWidBySort($iSort,$aFilter,$sWay='up') {
            return $this->oMapper->GetNextWidBySort($iSort,$aFilter,$sWay);
    }

    public function UpdateFavWid(PluginAceadminpanel_ModuleWidgets_EntityWidgets $oWidget,$aFilter) {
        if ($this->oMapper->UpdateFavWid($oWidget,$aFilter)) {
           return true;
        }
        return false;
    }

    public function GetMaxSortByPid($aFilter) {
        return $this->oMapper->GetMaxSortByPid($aFilter);
    }

    public function GetWidsByProducerName($pId) {
        return $this->oMapper->GetWidsByProducerName($pId);
    }

    /*public function GetWidgetsProducers() {
        return $this->oMapper->GetWidgetsProducers();
    }

    public function AddWidgetProducer($sName) {
        return $this->oMapper->AddWidgetProducer($sName);
    }

    public function GetWidgetProducerByName($sName) {
        return $this->oMapper->GetWidgetProducerByName($sName);
    }*/
}