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
* @File Name: Language.class.php
* @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*----------------------------------------------------------------------------
*/

/**
 * Расширенный модуль поддержки языковых файлов
 *
 */
class PluginAceadminpanel_ModuleLanguage extends PluginAceadminpanel_Inherit_ModuleLang {
    protected $sPlugin = 'aceadminpanel';

    protected $sDefaultLang=LANG_DEFAULT;
    protected $aLangDefine = array('russian');

    /**
     * Инициализация модуля
     *
     */
    public function Init() {
        if (defined('LANG_DEFINE') && ($sLangs=str_replace(' ', '', LANG_DEFINE))) {
            $this->aLangDefine=explode(',', $sLangs);
        }

        $this->SetCurrentLang($this->Session_Get('language'));
        parent::Init();
    }

    /**
     * Проверяет язык на соответствие заданному набору языков
     */
    protected function CheckLang($sLang) {
        if (in_array($sLang, $this->aLangDefine)) return $sLang;
        else null;
    }

    protected function SetCurrentLang($sLang) {
        $sLang = $this->CheckLang($sLang);
        if ($sLang) $this->sCurrentLang = $sLang;
    }

    /**
     * Инициализирует языковой файл
     *
     */
    protected function InitLang($sLanguage=null) {
        $this->aLangMsg=array();
        if (!$sLanguage) $sLanguage=$this->sCurrentLang;
        /**
         * Если используется кеширование через memcaсhed, то сохраняем данные языкового файла в кеш
         */
        if (Config::Get('sys.cache.use') && Config::Get('sys.cache.type')=='memory') {
            if (false === ($this->aLangMsg = $this->Cache_Get("lang_".$sLanguage))) {
                $this->aLangMsg=array();
                $this->LoadLangFiles($sLanguage);
                $this->Cache_Set($this->aLangMsg, "lang_".$sLanguage, array('adm_lang'), 60*60);
            }
        }
        else {
            $this->LoadLangFiles($sLanguage);
        }
        /**
         * Загружаем в шаблон
         */
        $this->Viewer_Assign('aLang', $this->aLangMsg);
        $this->Viewer_Assign('oLang', $this);
    }

    protected function LoadLangFiles($sLanguage=null) {
        if (!$sLanguage) $sLanguage=$this->sCurrentLang;
        parent::LoadLangFiles($this->sDefaultLang);
        if ($this->sDefaultLang != $sLanguage) {
            $aMsgDefault = $this->aLangMsg;
            parent::LoadLangFiles($sLanguage);
            $this->aLangMsg = array_merge($aMsgDefault, $this->aLangMsg);
        }
    }

    /**
     * Получает текстовку по её имени
     *
     * @param unknown_type $sName
     */
    public function Get($sName, $aReplace=array()) {
        if (isset($this->aLangMsg[$sName])) {
            return parent::Get($sName, $aReplace);
        }
        return strtoupper($sName);
    }

    public function __get($sName) {
        if (isset($this->aLangMsg[$sName])) {
            return $this->aLangMsg[$sName];
        }
        return strtoupper($sName);
    }

    public function ResetLang() {
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('adm_lang'));
        $this->InitLang();
    }

    public function Dictionary($sLanguage=null) {
        if ($sLanguage==null) {
            if (isset($_REQUEST['language'])) {
                $sLanguage = $this->CheckLang($_REQUEST['language']);
            }
            elseif (isset($_REQUEST['LANG_CURRENT'])) {
                $sLanguage = $this->CheckLang($_REQUEST['LANG_CURRENT']);
            }
            if (defined('LANG_SAVE_DAYS') && LANG_SAVE_DAYS > 0) {
                @setcookie('LANG_CURRENT', $sLanguage, time()+60*60*24*intVal(LANG_SAVE_DAYS), SYS_SESSION_PATH, SYS_SESSION_HOST);
            }
        }
        if ($sLanguage && $sLanguage!==$this->sCurrentLang) $this->InitLang($sLanguage);
        return $this;
    }

    public function ParseText($sText, $aData=Array()) {
        if (!isset($aData['date'])) {
            $aData['date']=time();
        }
        elseif (!is_numeric($aData['date'])) {
            $aData['date']=strtotime($aData['date']);
        }
        if (!isset($aData['user'])) $aData['user']='';

        $sText=preg_replace('/\[@user\]/', $aData['user'], $sText);
        $sText=preg_replace('/\[@date\]/', date('Y-m-d H:i:s', $aData['date']), $sText);
        if (preg_match('/\[@date=([^\]]*)\]/', $sText, $match)) {
            $date=date($match[1], $aData['date']);
            $sText=preg_replace('/\[@date=([^\]]*)\]/', $date, $sText);
        }
        return ($sText);
    }

    public function Text($sMsgKey, $aData=Array()) {
        $sText=$this->Get($sMsgKey);
        if (strpos($sText, '[@')===false) {
            return $sText;
        }
        else {
            return $this->ParseText($sText, $aData);
        }
    }

    public function LoadFile($sFileName) {
        $aLangMessages = include($sFileName);
        $this->aLangMsg=array_merge($this->aLangMsg, $aLangMessages);
    }

}
// EOF