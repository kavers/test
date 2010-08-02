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
* @File Name: admin_vote.php
* @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*----------------------------------------------------------------------------
*/

/**
 * Голосование администратора за пользователя
 * 1) Доступно только админам
 * 2) Отключена проверка на повторное голосование (не работает!)
 * 3) Отключена проверка на право голосования (ACL)
 * 4) Отключена проверка на силу голосования
 */

set_include_path(get_include_path().PATH_SEPARATOR.dirname(dirname(dirname(__FILE__))));
$sDirRoot=dirname(dirname(dirname(dirname(dirname(__FILE__)))));
require_once($sDirRoot."/config/config.ajax.php");

$iValue=floatVal(getRequest('value',null,'post'));
$bStateError=true;
$sMsg='';
$sMsgTitle='';
$iRating=0;
$iSkill=0;
$iCountVote=0;
if ($oEngine->User_IsAuthorization()) {
    // $oUser - за кого голосуем
    if (($oUser=$oEngine->User_GetUserById(getRequest('idUser',null,'post')))) {
        // $oUserCurrent - кто голосует
        $oUserCurrent=$oEngine->User_GetUserCurrent();
        if ($oUserCurrent->isAdministrator()) {
            if (!($oUserVote=$oEngine->Vote_GetVote($oUser->getId(),'user',$oUserCurrent->getId()))) {
                $oUserVote=Engine::GetEntity('Vote');
                $oUserVote->setTargetId($oUser->getId());
                $oUserVote->setTargetType('user');
                $oUserVote->setVoterId($oUserCurrent->getId());
                $oUserVote->setDirection($iValue);
                $oUserVote->setDate(date("Y-m-d H:i:s"));
                $iVal=(float)$oEngine->Rating_VoteUser($oUserCurrent,$oUser,$iValue);
                $oUserVote->setValue($iVal);
                $oUser->setCountVote($oUser->getCountVote()+1);
                if ($oEngine->Vote_AddVote($oUserVote) and $oEngine->User_Update($oUser)) {
                    $bStateError=false;
                    $sMsgTitle=$oEngine->Lang_Get('attention');
                    $sMsg=$oEngine->Lang_Get('user_vote_ok');
                    $iRating=$oUser->getRating();
                    $iSkill=$oUser->getSkill();
                    $iCountVote=$oUser->getCountVote();
                } else {
                    $sMsgTitle=$oEngine->Lang_Get('error');
                    $sMsg=$oEngine->Lang_Get('system_error');
                }
            } else {
                if (Config::Get('plugin.aceadminpanel.admin_many_votes')) {
                    // * Повторное голосование админа
                    $iNewValue = $oUserVote->getValue()+$iValue;
                    $oUserVote->setDirection($iNewValue);
                    $oUserVote->setDate(date("Y-m-d H:i:s"));
                    $iVal=(float)$oEngine->Rating_VoteUser($oUserCurrent,$oUser,$iValue);
                    $oUserVote->setValue($oUserVote->getValue()+$iVal);
                    $oUser->setCountVote($oUser->getCountVote()+1);
                    if ($oEngine->Vote_UpdateVote($oUserVote) and $oEngine->User_Update($oUser)) {
                        $bStateError=false;
                        $sMsgTitle=$oEngine->Lang_Get('attention');
                        $sMsg=$oEngine->Lang_Get('user_vote_ok');
                        $iRating=$oUser->getRating();
                        $iSkill=$oUser->getSkill();
                        $iCountVote=$oUser->getCountVote();
                    } else {
                        $sMsgTitle=$oEngine->Lang_Get('error');
                        $sMsg=$oEngine->Lang_Get('system_error');
                    }
                } else {
                    $sMsgTitle=$oEngine->Lang_Get('attention');
                    $sMsg=$oEngine->Lang_Get('user_vote_error_already');
                }
            }
        } else {
            $sMsgTitle=$oEngine->Lang_Get('attention');
            $sMsg=$oEngine->Lang_Get('adm_action_for_admin_only');
        }
    } else {
        $sMsgTitle=$oEngine->Lang_Get('error');
        $sMsg=$oEngine->Lang_Get('system_error');
    }
} else {
    $sMsgTitle=$oEngine->Lang_Get('error');
    $sMsg=$oEngine->Lang_Get('need_authorization');
}


$GLOBALS['_RESULT'] = array(
        "bStateError" => $bStateError,
        "iRating" => $iRating,
        "iSkill" => $iSkill,
        "iCountVote" => $iCountVote,
        "sMsgTitle" => $sMsgTitle,
        "sMsg" => $sMsg,
);

// EOF