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
* @File Name: PluginAceadminpanel.class.php
* @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*----------------------------------------------------------------------------
*/

class PluginAceadminpanel extends Plugin {
    private $sPlugin = 'aceadminpanel';

    /* */
    public $aDelegates = array(
            'module' => array(
                            'Admin'=>'PluginAceadminpanel_ModuleAdmin',
                            'Page'=>'PluginPage_ModulePage'),
            'entity' => array(
                            'PageEntity_Page'=>'PluginPage_PageEntity_Page')
    );
     /* */

    public $aInherits=array(
            'action' => array('ActionAdmin'),
            'module' => array(
                            'Lang'=>'_ModuleLanguage',
                            'Vote'=>'_ModuleVote'),
            'entity'=>array('ModuleUser_EntityUser'=>'_ModuleAdmin_EntityUser'),
    );

    /**
     * Активация плагина
     */
    public function Activate() {
        // Создание таблиц в базе данных при их отсутствии.
        $result = true;
        $data = $this->ExportSQL(dirname(__FILE__).'/sql.sql');
        if (!$data['result']) {
            foreach ($data['errors'] as $err) {
                if ($err>'') $result = false;
            }
        }
        //$this->PluginAceadminpanel_Admin_DelValue('version');
        //$this->PluginAceadminpanel_Admin_SetValue('version', '1.4');

        if (!$result) $this->Message_AddErrorSingle('Cannot update database for this plugin', $this->Lang_Get('error'),true);
        if ($result) {
            $this->ClearCache();
        }
        return $result;
    }

    /**
     * Инициализация плагина
     */
    public function Init() {
        HelperPlugin::InitPlugin($this);

        $sDataFile = $this->PluginAceadminpanel_Admin_GetCustomConfigFile();
        if (!file_exists($sDataFile)) {
            $aConfigSet = $this->PluginAceadminpanel_Admin_GetValueArrayByPrefix('config.all.');
            @file_put_contents($sDataFile, serialize($aConfigSet));
        }

        // поддержка именования старых классов
        if (defined('OLD_CLASS_LOADER') && OLD_CLASS_LOADER)
            HelperPlugin::AutoLoadRegister(array($this, 'AutoLoaderOldClass'));

    }

    public function Deactivate() {
        $this->ClearCache();
        return true;
    }

    protected function ClearCache() {
        if (!admClearDir(Config::Get('path.smarty.compiled'))) {
            $this->Message_AddErrorSingle(
                    'Unable to remove content of dir <b>'.admFilePath(Config::Get('path.smarty.compiled')).'</b>. It is recommended to do it manually',
                    $this->Lang_Get('attention'), true);
        }
        if (!admClearDir(Config::Get('path.smarty.cache'))) {
            $this->Message_AddErrorSingle(
                    'Unable to remove content of dir <b>'.admFilePath(Config::Get('path.smarty.cache')).'</b>. It is recommended to do it manually',
                    $this->Lang_Get('attention'), true);
        }
        $result = admClearAllCache();
        return $result;
    }

    public function __call($sName, $aArgs=array()) {
        return parent::__call($sName, $aArgs);
    }

}

if (!function_exists('admClearSmartyCache')) {
    include_once 'include/adm_function.php';
}

// EOF