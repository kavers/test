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
* @File Name: HookAdmin.class.php
* @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*----------------------------------------------------------------------------
*/

class PluginAceadminpanel_HookAdmin extends Hook {
    protected $sPlugin = 'aceadminpanel';
    protected $oUser=null;

    public function RegisterHook() {
        $this->AddHook('init_action', 'InitAction', __CLASS__);
    }

    private function GetUser() {
        if (is_null($this->oUser)) {
            if (($sUserId=$this->Session_Get('user_id'))) {
                $this->oUser=$this->PluginAceadminpanel_Admin_GetUserById($sUserId);
            } elseif (isset($_REQUEST['submit_login']) && isset($_REQUEST['login'])) {
                $this->oUser=$this->PluginAceadminpanel_Admin_GetUserByLogin($_REQUEST['login']);
            }
        }
        return $this->oUser;
    }

    private function UserBanned($oUser) {
        if ($oUser) {
            if ($oUser->IsBannedUnlim()) {
                $sText=$this->Lang_Get('adm_banned2_text');
            }
            else {
                $sText=$this->Lang_Get('adm_banned1_text', array('date'=>$oUser->GetBanLine()));
            }
            $this->Message_AddErrorSingle($sText, $this->Lang_Get('adm_denied_title'));
            $oUser->setKey(uniqid(time(), true));
            $this->User_Update($oUser);

            $this->User_Logout();
        }
        $this->Session_DropSession();
        Router::Action('error');
    }

    // Зарезервировано
    private function SiteClosed() {
        return false;
    }

    public function InitAction($aVars) {
        $oLang=$this->Lang_Dictionary();
        $this->Viewer_Assign('oLang', $oLang);
        
        $oUser=$this->GetUser();
        if ($oUser && $oUser->IsAdministrator() && Config::Get('plugin.'.$this->sPlugin.'.'.'icon_menu')) {
            $sScript = Plugin::GetTemplateWebPath($this->sPlugin).'js/'.'icon_menu.js';
            $this->Viewer_AppendScript($sScript);
        }

        if (Router::GetAction()=='admin' || Router::GetAction()=='error') return;

        if (!$oUser) {
            if (Router::GetAction()=='registration') {
                $aIp = admGetAllUserIp();
                foreach ($aIp as $sIp) {
                    if ($this->PluginAceadminpanel_Admin_IsBanIp($sIp)) {
                        $this->Message_AddErrorSingle($this->Lang_Get('adm_banned2_text'), $this->Lang_Get('adm_denied_title'));
                        return $this->UserBanned(null);
                    }
                }
            }
            return;
        }

        if (defined('ADMIN_SITE_CLOSED') && ADMIN_SITE_CLOSED && !$oUser->IsAdministrator()) {
            $this->SiteClosed();
        }
        if ($oUser->IsBannedByLogin() || ($oUser->IsBannedByIp() && !$oUser->IsAdministrator())) {
            return $this->UserBanned($oUser);
        }
    }

}

// EOF