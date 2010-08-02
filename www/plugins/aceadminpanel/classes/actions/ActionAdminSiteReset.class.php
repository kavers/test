<?php
/*---------------------------------------------------------------------------
* @Plugin Name: aceAdminPanel
* @Plugin Id: aceadminpanel
* @Plugin URI: 
* @Description: Advanced Administrator's Panel for LiveStreet/ACE
* @Version: 1.4-dev.109
* @Author: Vadim Shemarov (aka aVadim)
* @Author URI: 
* @LiveStreet Version: 0.4.1
* @File Name: ActionAdminSiteReset.class.php
* @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*----------------------------------------------------------------------------
*/

class PluginAceadminpanel_AdminSiteReset extends AceAdmin {

    public function Init() {
    }

    public function Event() {
        if (isPost('adm_reset_submit')) {
            $this->EventSiteResetSubmit();
            $this->Viewer_Assign('submit_cache_save', 1);
        } else {
            if($sPlugin=getRequest('plugin',null,'get') and $sAction=getRequest('action',null,'get')) {
                if ($sAction=='deactivate') {
                    return $this->SubmitManagePlugin($sPlugin,$sAction);
                } else {
                    return $this->EventSitePluginsActivate($sPlugin);
                }
            }
        }
    }

    protected function EventSiteResetSubmit() {
        if (isPost('adm_cache_clear_data')) $this->Cache_Clean();
        if (isPost('adm_cache_clear_headfiles')) admClearHeadfilesCache();
        if (isPost('adm_cache_clear_smarty')) admClearSmartyCache();
        if (isPost('adm_reset_config_data')) $this->ResetCustomConfig();
        $this->oAdminAction->Message('notice', $this->Lang_Get('adm_action_ok'), null, true);
        admHeaderLocation(Router::GetPath('admin').'site/reset/');
    }

    protected function ResetCustomConfig() {
        $this->PluginAceAdminPanel_Admin_DelValueArrayByPrefix('config.all.');
        $sFileName = $this->PluginAceAdminPanel_Admin_GetCustomConfigFile();
        unlink($sFileName);
    }
}

// EOF