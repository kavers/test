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
* @File Name: admin_gettalk.php
* @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*----------------------------------------------------------------------------
*/

/**
 * Обрабатывает получение сообщения по его ID
 */

set_include_path(get_include_path().PATH_SEPARATOR.dirname(dirname(dirname(__FILE__))));
$sDirRoot=dirname(dirname(dirname(dirname(dirname(__FILE__)))));
require_once($sDirRoot."/config/config.ajax.php");

$bStateError=true;
$sTextResult='';
$sMsgTitle='';
$sMsg='';


$sTalkId=@$_REQUEST['talk_id'];
if ($oTalk=$oEngine->Talk_GetTalkById($sTalkId)) {
	$bStateError=false;
	$sTitle=$oTalk->GetTitle();
	$sText=$oTalk->GetText();
	if ((substr($sText, 0, 4)=='To: ') && $n=strpos($sText, 'Msg: ')) {
		$sText=trim(substr($sText, $n+5));
	}
} else {
	$sMsgTitle=$oEngine->Lang_Get('error');
	$sMsg=$oEngine->Lang_Get('system_error');
}


$GLOBALS['_RESULT'] = array(
"bStateError"     => $bStateError,
"sTitle" => $sTitle,
"sText"   => $sText,
);

// EOF