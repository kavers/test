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
* @File Name: adm_oldclassloader.php
* @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*----------------------------------------------------------------------------
*/

/**
 * Поддержка старого именования классов
 * Подгружаем класс, только если эта поддержка включена
 */
if (defined('OLD_CLASS_LOADER') && OLD_CLASS_LOADER) {
    class HelperOldClassLoader extends Object {
        static protected $oInstance=null;

        static public function getInstance() {
            if (self::$oInstance===null) {
                self::$oInstance = new self;
            }
            return self::$oInstance;
        }

        static public function Init() {
            return self::getInstance();
        }

        public function Autoloader($sClassName) {
            if (HelperPluginLoader::getInstance()->AutoloadIgnoreClass($sClassName)) return;

            $this->DeclareClass($sClassName);
        }

        protected function DeclareClass($sClassName) {
            $aClassElements = $this->ClassNameExplode($sClassName);

            $sParentClass = '';
            $sFileClass = '';

            if (isset($aClassElements['Module'])) {
                $sClassDelegate = Engine::getInstance()->Plugin_GetDelegate('module', $sClassName);
                if ($sClassDelegate != $sClassName) {
                    $sParentClass = $sClassDelegate;
                }
            }

            if (!$sParentClass) {
                $sClassNameItem = $sClassName;
                if (isset($aClassElements['Plugin'])) {
                    // класс внутри плагина
                    $sParentClass = 'Plugin'.ucfirst(strtolower($aClassElements['Plugin'])).'_';
                    $n = strpos($sClassName, '_');
                    if ($n) $sClassNameItem = substr($sClassName, $n+1);
                    // если такой плагин не активирван, то уходим
                    if (!in_array(strtolower($aClassElements['Plugin']), Engine::getInstance()->Plugin_GetActivePlugins()))
                        return;

                }

                if (preg_match('/^Ls([\w]+)/', $sClassNameItem, $aMatches)) {
                    $sParentClass .= 'Module'.$aMatches[1];
                }
                elseif (preg_match('/^(\w+)Entity_([\w]+)/', $sClassNameItem, $aMatches)) {
                    $sParentClass .= 'Module'.$aMatches[1].'_Entity'.$aMatches[2];
                }
                elseif (preg_match('/^Mapper_([\w]+)/', $sClassNameItem, $aMatches)) {
                    $sParentClass .= 'Module'.$aMatches[1].'_Mapper'.$aMatches[1];
                }
                elseif (isset($aClassElements['Inherits'])) {
                    if (isset($aClassElements['Module'])) {
                        $sParentClass .= 'Inherit_Module'.$aClassElements['Module'];
                    }
                }
                elseif (isset($aClassElements['Action'])) {
                    $sParentClass .= 'Action'.$aClassElements['Action'];
                }
                elseif (isset($aClassElements['Block'])) {
                    $sParentClass .= 'Block'.$aClassElements['Block'];
                }
                elseif (isset($aClassElements['Hook'])) {
                    $sParentClass .= 'Hook'.$aClassElements['Hook'];
                }
                elseif (isset($aClassElements['Module'])) {
                    $sParentClass .= $aClassElements['Module'];
                }
                elseif (isset($aClassElements['Mapper'])) {
                    $sParentClass .= '_Mapper'.$aClassElements['Mapper'];
                }
            }

            if (!$sFileClass) $sFileClass = $this->ClassToPath($sClassName);
            if ($sFileClass && file_exists($sFileClass)) {
                include_once $sFileClass;
            }
            if ((!class_exists($sClassName, false)) && $sParentClass && ($sParentClass !=$sClassName)) {
                $sEvalCode = 'class '.$sClassName.' extends '.$sParentClass.' {}';
                eval($sEvalCode);
            }
        }

        protected function ClassNameExplode($sClassName) {


            if (preg_match('/^Plugin(\w+)_(\w+)Entity_(\w+)/', $sClassName, $match)) {
                $aClassElements['Plugin'] = $match[1];
                $aClassElements['Module'] = $match[2];
                $aClassElements['Entity'] = $match[3];
            } else {
                $aClassElements = HelperPlugin::ClassNameExplode($sClassName);
            }
            return $aClassElements;
        }

        protected function ClassToPath($xClass) {
            if (is_array($xClass)) $aClassElements = $xClass;
            else $aClassElements = $this->ClassNameExplode($xClass);

            $sFilePath = Config::Get('path.root.server');
            if (isset($aClassElements['Plugin'])) {
                // класс внутри плагина
                if ((is_string($xClass) && (false!==strpos($aClassElements['Plugin'], '_'))) ||
                        (sizeof($aClassElements)>1)) {
                    $sFilePath .= '/plugins/'.strtolower($aClassElements['Plugin']);
                }
                // класс самого плагина
                else {
                    $sFilePath .= '/plugins/Plugin'.ucfirst(strtolower($aClassElements['Plugin'])).'.class.php';
                }
            }

            if (isset($aClassElements['Action'])) {
                $sFilePath .= '/classes/actions/Action'.$aClassElements['Action'].'.class.php';
            }
            elseif (isset($aClassElements['Block'])) {
                $sFilePath .= '/classes/blocks/Block'.$aClassElements['Block'].'.class.php';
            }
            elseif (isset($aClassElements['Hook'])) {
                $sFilePath .= '/classes/hooks/Hook'.$aClassElements['Hook'].'.class.php';
            }
            elseif (isset($aClassElements['Module'])) {
                $sFilePath .= '/classes/modules/'.strtolower($aClassElements['Module']);
                if (isset($aClassElements['Mapper'])) {
                    $sFilePath .= '/mapper/'.$aClassElements['Mapper'].'.mapper.class.php';
                }
                elseif (isset($aClassElements['Entity'])) {
                    $sFilePath .= '/entity/'.$aClassElements['Entity'].'.entity.class.php';
                }
                else {
                    $sFilePath .= '/'.ucfirst($aClassElements['Module']).'.class.php';
                }
            }
            elseif (isset($aClassElements['Mapper'])) {
                if (!isset($aClassElements['Plugin'])) {
                    $sFilePath .= '/classes/modules/'.strtolower($aClassElements['Mapper']).'/mapper/'.ucfirst(strtolower($aClassElements['Mapper'])).'.mapper.class.php';
                }
            }
            if (DIRECTORY_SEPARATOR!='/') $sFilePath = str_replace(DIRECTORY_SEPARATOR, '/', $sFilePath);
            return $sFilePath;
        }
    } // class HelperOldClassLoader

    HelperPlugin::AutoLoadRegister(array(HelperOldClassLoader::Init(), 'AutoLoader'));

}
// EOF