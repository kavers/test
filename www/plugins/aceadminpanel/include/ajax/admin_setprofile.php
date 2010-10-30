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
* @File Name: admin_setprofile.php
* @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*----------------------------------------------------------------------------
*/

/**
 * Обрабатывает изменение профиля юзера
 */

set_include_path(get_include_path().PATH_SEPARATOR.dirname(dirname(dirname(__FILE__))));
$sDirRoot=dirname(dirname(dirname(dirname(dirname(__FILE__)))));
require_once($sDirRoot."/config/config.ajax.php");

$bStateError=true;
$sTextResult='';
$sMsgTitle='';
$sMsg='';


$sUserId=@$_REQUEST['user_id'];
if (($oUser=$oEngine->User_GetUserById($sUserId))) {
    if (isset($_REQUEST['profile_about'])) $oUser->setProfileAbout(getRequest('profile_about'));
    if (isset($_REQUEST['profile_site'])) $oUser->setProfileSite(getRequest('profile_site'));
    if (isset($_REQUEST['profile_site_name'])) $oUser->setProfileSiteName(getRequest('profile_site_name'));
    if (isset($_REQUEST['profile_email'])) $oUser->setMail(getRequest('profile_email'));
    if (isset($_REQUEST['profile_user_cat'])) {
        $sCatName = $_REQUEST['profile_user_cat'];
        $sCatName = strtoupper(substr(trim($sCatName), 0, 20));
        if($sFullCatName = $oEngine->PluginUsercats_ModuleCategory_GetFullCategoryName($sCatName)) {
            $oUser->setCategory($sFullCatName);
        }
    }
    if (isset($_REQUEST['profile_blog_cat'])) {
        if($oBlog = $oEngine->Blog_GetPersonalBlogByUserId($oUser->getId())) {
            $sCatName = $_REQUEST['profile_blog_cat'];
            $sCatName = strtoupper(substr(trim($sCatName), 0, 20));
            if($sFullCatName = $oEngine->PluginCommunitycats_ModuleCategory_GetFullCategoryName($sCatName)) {
                $oBlog->setCategory($sFullCatName);
            }
            
            $oEngine->Blog_UpdateBlog($oBlog);
        }
    }
    
    if ($oEngine->User_Update($oUser)!==false) {
        $bStateError=false;
        $sTitle='aceAdminPanel';
        $sText=$oEngine->Lang_Get('adm_saved_ok');
    } else {
        $sTitle='aceAdminPanel';
        $sText=$oEngine->Lang_Get('adm_saved_err');
    }
} else {
    $sTitle=$oEngine->Lang_Get('error');
    $sText=$oEngine->Lang_Get('system_error');
}


$GLOBALS['_RESULT'] = array(
        "bStateError"     => $bStateError,
        "sTitle" => $sTitle,
        "sText"   => $sText,
);

// EOF