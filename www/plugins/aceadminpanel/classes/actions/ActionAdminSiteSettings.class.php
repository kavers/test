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
* @File Name: ActionAdminSiteSettings.class.php
* @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*----------------------------------------------------------------------------
*/

class PluginAceadminpanel_AdminSiteSettings extends AceAdmin {
    protected $aFields = array();

    public function Init() {
        $this->aFields['base'] =
                array(
                'adm_set_section_general' => array(
                        'type'=>'section',
                ),
                'adm_set_view_name' => array(
                        'type'=>'input',
                        'config'=>'view.name',
                        'class'=>'w100p'
                ),
                'adm_set_view_description' => array(
                        'type'=>'input',
                        'config'=>'view.description',
                        'class'=>'w100p'
                ),
                'adm_set_view_keywords' => array(
                        'type'=>'input',
                        'config'=>'view.keywords',
                        'class'=>'w100p'
                ),

                'adm_set_general_close' => array(
                        'type'=>'checkbox',
                        'config'=>'general.close',
                ),
                'adm_set_general_reg_invite' => array(
                        'type'=>'checkbox',
                        'config'=>'general.reg.invite',
                ),
                'adm_set_general_reg_activation' => array(
                        'type'=>'checkbox',
                        'config'=>'general.reg.activation',
                ),

                'adm_set_section_edit' => array(
                        'type'=>'section',
                ),
                'adm_set_view_tinymce' => array(
                        'type'=>'checkbox',
                        'config'=>'view.tinymce',
                ),
                'adm_set_view_noindex' => array(
                        'type'=>'checkbox',
                        'config'=>'view.noindex',
                ),
                'adm_set_view_img_resize_width' => array(
                        'type'=>'text',
                        'config'=>'view.img_resize_width',
                        'class'=>'w50 number',
                        'valtype'=>'number',
                ),
                'adm_set_view_img_max_width' => array(
                        'type'=>'text',
                        'config'=>'view.img_max_width',
                        'class'=>'w50 number',
                        'valtype'=>'number',
                ),
                'adm_set_view_img_max_height' => array(
                        'type'=>'text',
                        'config'=>'view.img_max_height',
                        'class'=>'w50 number',
                        'valtype'=>'number',
                ),
        );

        $this->aFields['sys'] =
                array(
                'adm_set_section_sys_cookie' => array(
                        'type'=>'section',
                ),
                'adm_set_sys_cookie_host' => array(
                        'type'=>'input',
                        'config'=>'sys.cookie.host',
                        'class'=>'w100p',
                        'valtype'=>'string',
                        'empty'=>null,
                ),
                'adm_set_sys_cookie_path' => array(
                        'type'=>'input',
                        'config'=>'sys.cookie.path',
                        'class'=>'w100p',
                        'valtype'=>'string',
                ),
                'adm_set_sys_session_standart' => array(
                        'type'=>'checkbox',
                        'config'=>'sys.session.standart',
                ),
                'adm_set_sys_session_name' => array(
                        'type'=>'input',
                        'config'=>'sys.session.name',
                        'class'=>'w100p',
                        'valtype'=>'string',
                ),
                'adm_set_sys_session_timeout' => array(
                        'type'=>'text',
                        'config'=>'sys.session.timeout',
                        'class'=>'w50 number',
                        'valtype'=>'string',
                        'empty'=>null,
                ),
                'adm_set_sys_session_host' => array(
                        'type'=>'input',
                        'config'=>'sys.session.host',
                        'class'=>'w100p',
                        'valtype'=>'string',
                ),
                'adm_set_sys_session_path' => array(
                        'type'=>'input',
                        'config'=>'sys.session.path',
                        'class'=>'w100p',
                        'valtype'=>'string',
                ),

                'adm_set_section_sys_mail' => array(
                        'type'=>'section',
                ),
                'adm_set_sys_mail_from_email' => array(
                        'type'=>'input',
                        'config'=>'sys.mail.from_email',
                        'class'=>'w100p',
                        'valtype'=>'string',
                ),
                'adm_set_sys_mail_from_name' => array(
                        'type'=>'input',
                        'config'=>'sys.mail.from_name',
                        'class'=>'w100p',
                        'valtype'=>'string',
                ),
                'adm_set_sys_mail_charset' => array(
                        'type'=>'input',
                        'config'=>'sys.mail.charset',
                        'class'=>'w100p',
                        'valtype'=>'string',
                ),
                'adm_set_sys_mail_type' => array(
                        'type'=>'select',
                        'options'=>array('mail', 'sendmail', 'smtp'),
                        'config'=>'sys.mail.type',
                        'class'=>'w100p',
                        'valtype'=>'string',
                ),
                'adm_set_sys_mail_smtp_host' => array(
                        'type'=>'input',
                        'config'=>'sys.mail.smtp.host',
                        'class'=>'w100p',
                        'valtype'=>'string',
                ),
                'adm_set_sys_mail_smtp_port' => array(
                        'type'=>'text',
                        'config'=>'sys.mail.smtp.port',
                        'class'=>'w50 number',
                        'valtype'=>'number',
                ),
                'adm_set_sys_mail_smtp_user' => array(
                        'type'=>'input',
                        'config'=>'sys.mail.smtp.user',
                        'class'=>'w100p',
                        'valtype'=>'string',
                ),
                'adm_set_sys_mail_smtp_password' => array(
                        'type'=>'password',
                        'config'=>'sys.mail.smtp.password',
                        'class'=>'w100p',
                        'valtype'=>'password',
                ),
                'adm_set_sys_mail_smtp_auth' => array(
                        'type'=>'checkbox',
                        'config'=>'sys.mail.smtp.auth',
                ),
                'adm_set_sys_mail_include_comment' => array(
                        'type'=>'checkbox',
                        'config'=>'sys.mail.include_comment',
                ),
                'adm_set_sys_mail_include_talk' => array(
                        'type'=>'checkbox',
                        'config'=>'sys.mail.include_talk',
                ),
        );

        foreach ($this->aFields as $sMode => $aModeSet) {
            foreach ($aModeSet as $sName => $aField) {
                if (!isset($aField['valtype']) || !in_array($aField['valtype'], array('boolean', 'integer', 'float', 'string'))) {
                    if (isset($aField['valtype']) && $aField['valtype']=='number') {
                        $this->aFields[$sMode][$sName]['valtype']='integer';
                    }
                    else {
                        $this->aFields[$sMode][$sName]['valtype'] = ($aField['type']=='checkbox')?'boolean':'string';
                    }
                }
            }
        }
    }

    public function Event() {
        $sMode = $this->GetParam(1);
        if (in_array($sMode, array('base', 'sys'))) {
            $this->sMenuNavItemSelect = $sMode;
        } else {
            $this->sMenuNavItemSelect = $sMode = 'base';
        }
        if (isPost('submit_data_save')) {
            $this->SaveConfig($sMode);
        }
        $this->Viewer_Assign('aFields', $this->aFields[$this->sMenuNavItemSelect]);
    }

    public function SaveConfig($sMode) {
        $this->Security_ValidateSendForm();
        $aConfigSet = array();
        foreach ($this->aFields[$sMode] as $sName=>$aField) {
            if ($aField['type'] != 'section') {
                $aConfigField['key'] = 'config.all.'.$aField['config'];
                if (!isset($_POST[$sName]) || !$_POST[$sName]) {
                    if (isset($aField['empty'])) $aConfigField['val'] = $aField['empty'];
                    else {
                        if ($aField['valtype']=='boolean') $val=false;
                        else $val='';
                    }
                } else {
                    $val = $_POST[$sName];
                    settype($val, $aField['valtype']);
                }
                $aConfigField['val']=serialize($val);
                $aConfigSet[] = $aConfigField;
            }
        }
        $sDataFile = $this->PluginAceadminpanel_Admin_GetCustomConfigFile();
        if ($this->PluginAceAdminPanel_Admin_SetValueArray($aConfigSet)) {
            $aConfigSet = $this->PluginAceAdminPanel_Admin_GetValueArrayByPrefix('config.all.');
            file_put_contents($sDataFile, serialize($aConfigSet));
            $this->oAdminAction->Message('notice', $this->Lang_Get('adm_saved_ok'), null, true);
        } else {
            $this->oAdminAction->Message('error', $this->Lang_Get('adm_saved_err'), null, true);
        }
        admHeaderLocation(Router::GetPath('admin').'site/settings/'.$this->sMenuNavItemSelect);
    }
}

// EOF