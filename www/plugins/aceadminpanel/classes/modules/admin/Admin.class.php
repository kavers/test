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
* @File Name: Admin.class.php
* @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*----------------------------------------------------------------------------
*/

/**
 * Модуль для работы с админпанелью.
 *
 */
class PluginAceadminpanel_ModuleAdmin extends Module {
    protected $oMapper;
    protected $sVersionDB='';

    public function Init() {
        $this->oMapper = HelperPlugin::GetMapper();
        //$this->oMapper = Engine::GetMapper(__CLASS__);
        $this->sVersionDB=$this->oMapper->GetAdminValue('version');
    }

    public function GetVersionDB() {
        return  $this->sVersionDB;
    }

    public function GetVersion($bFull=false) {
        $sVersion = HelperPlugin::GetConfig('version');
        if (floatVal($this->sVersionDB) != floatVal($sVersion)) {
            return $sVersion.'/'.$this->sVersionDB;
        }
        else {
            return $sVersion;
        }
    }

    public function IsNeedUpgrade() {
        return  (floatVal($this->sVersionDB) < floatVal(HelperPlugin::GetConfig('version')));
    }

    public function GetValue($xKey, $xDefault=null) {
        return ($this->oMapper->GetAdminValue($xKey, $xDefault));
    }

    public function SetValue($sKey, $xValue) {
        return ($this->oMapper->SetAdminValue($sKey, $xValue));
    }

    public function GetValueArrayByPrefix($sPrefix) {
        return ($this->oMapper->GetAdminValue('prefix:'.$sPrefix));
    }

    public function SetValueArray($aValueSet) {
        $result = true;
        foreach ($aValueSet as $aValue) {
            $result = $result && $this->SetValue($aValue['key'], $aValue['val']);
        }
        return $result;
    }

    public function DelValue($xKey) {
        return ($this->oMapper->DelAdminValue($xKey));
    }

    public function DelValueArrayByPrefix($sPrefix) {
        return ($this->oMapper->DelAdminValue('prefix:'.$sPrefix));
    }

    public function Upgrade() {
        $aUpgrades[]=array('version'=>'0.1', 'file'=>'upgrade/AdminUpgrade000x010.php');
        $aUpgrades[]=array('version'=>'0.2', 'file'=>'upgrade/AdminUpgrade010x020.php');
        $aUpgrades[]=array('version'=>'0.3', 'file'=>'upgrade/AdminUpgrade020x030.php');
        $aUpgrades[]=array('version'=>'1.0', 'file'=>'upgrade/AdminUpgrade030x100.php');
        $aUpgrades[]=array('version'=>'1.1', 'file'=>'upgrade/AdminUpgrade100x110.php');
        $aUpgrades[]=array('version'=>'1.2', 'file'=>'upgrade/AdminUpgrade110x120.php');

        $result=true;
        $db=$this->Database_GetConnect();
        foreach($aUpgrades as $aData) {
            if ($result && $this->sVersionDB<$aData['version']) $result=include $aData['file'];
        }
        if ($result) $this->Init();
        return $result;
    }

    /**
     * Получить список пользователей с использованием фильтра
     *	Фильтр:
     *		login	=> точный логин
     *		like	=> начальные буквы логина
     *		admin	=> администраторы
     */
    public function GetUserList(&$iCount, $iCurrPage, $iPerPage, $aFilter=Array(), $aSort=Array()) {
        $filter=serialize($aFilter);
        $sort=serialize($aSort);
        $sCacheKey='adm_user_list_'.$filter.'_'.$sort.'_'.$iCurrPage.'_'.$iPerPage;
        if (false === ($data = $this->Cache_Get($sCacheKey))) {
            $data = array('collection'=>$this->oMapper->GetUserList($iCount, $iCurrPage, $iPerPage, $aFilter, $aSort),'count'=>$iCount);
            if ($data['count']) {
                $aUserId = array();
                foreach ($data['collection'] as $oUser) {
                    $aUserId[]=$oUser->getId();
                }
                $aSessions=$this->User_GetSessionsByArrayId($aUserId);
                foreach ($data['collection'] as $oUser) {
                    if (isset($aSessions[$oUser->getId()])) {
                        $oUser->setSession($aSessions[$oUser->getId()]);
                    } else {
                        $oUser->setSession(null); // или $oUser->setSession(new UserEntity_Session());
                    }
                }
            }
            $this->Cache_Set($data, $sCacheKey, array("user_update", "user_new"), 60*15);
        }
        return $data;
    }

    public function GetUserId($sUserLogin) {
        return $this->oMapper->GetUserId($sUserLogin);
    }

    public function GetUserCurrent() {
        $oUser=$this->User_GetUserCurrent();
        if ($oUser && ($oUserAdmin=$this->GetUserById($oUser->getId()))) {
            return $oUserAdmin;
        } else {
            return $oUser;
        }
    }

    public function GetUserById($sUserId) {
        $sCacheKey='user_'.$sUserId;
        if (false === ($data = $this->Cache_Get($sCacheKey))) {
            $data = $this->oMapper->GetUserById($sUserId);
            $this->Cache_Set($data, $sCacheKey, array('user_update_'.$sUserId), 60*5);
        }
        return $data;
    }

    public function GetUserByLogin($sUserLogin) {
        return $this->GetUserById($this->GetUserId($sUserLogin));
    }

    public function SetUserBan($nUserId, $nDays=null, $sComment=null) {
        if (!$nDays) {
            $nUnlim=1;
            $dDate=null;
        }
        else {
            $nUnlim=0;
            $dDate=date("Y-m-d H:i:s", time()+3600*24*$nDays);
        }
        $this->Session_Drop($nUserId);
        $bOk=$this->oMapper->SetBanUser($nUserId, $dDate, $nUnlim, $sComment);
        if (($oUser=$this->GetUserById($nUserId))) {
            $this->User_Update($oUser); // для сброса кеша
        }
        return $bOk;
    }

    public function ClearUserBan($nUserId) {
        $bOk=$this->oMapper->DelBanUser($nUserId);
        if (($oUser=$this->GetUserById($nUserId))) {
            $this->User_Update($oUser); // для сброса кеша
        }
        return $bOk;
    }

    public function GetBanList(&$iCount, $iCurrPage, $iPerPage, $aFilter=Array(), $aSort=Array()) {
        $filter=serialize($aFilter);
        $sort=serialize($aSort);
        $sCacheKey='adm_banlist_'.$filter.'_'.$sort.'_'.$iCurrPage.'_'.$iPerPage;
        if (false === ($data = $this->Cache_Get($sCacheKey))) {
            $data = array('collection'=>$this->oMapper->GetBanList($iCount, $iCurrPage, $iPerPage, $aFilter, $aSort),'count'=>$iCount);
            $this->Cache_Set($data, $sCacheKey, array('adm_banlist', 'user_update'), 60*15);
        }
        return $data;
    }

    public function GetBanListIp(&$iCount, $iCurrPage, $iPerPage) {
        $sCacheKey='adm_banlist_ip_'.$iCurrPage.'_'.$iPerPage;
        if (false === ($data = $this->Cache_Get($sCacheKey))) {
            $data = array('collection'=>$this->oMapper->GetBanListIp($iCount, $iCurrPage, $iPerPage),'count'=>$iCount);
            $this->Cache_Set($data, $sCacheKey, array('adm_banlist_ip'), 60*15);
        }
        return $data;
    }

    public function SetBanIp($sIp1, $sIp2, $nDays=null, $sComment=null) {
        if (!$nDays) {
            $nUnlim=1;
            $dDate=null;
        }
        else {
            $nUnlim=0;
            $dDate=date("Y-m-d H:i:s", time()+3600*24*$nDays);
        }

        //чистим зависимые кеши
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('adm_banlist_ip'));
        return $this->oMapper->SetBanIp($sIp1, $sIp2, $dDate, $nUnlim, $sComment);
    }

    public function ClearBanIp($nId) {
        //чистим зависимые кеши
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('adm_banlist_ip'));
        return $this->oMapper->DelBanIp($nId);
    }

    public function IsBanIp($sIp) {
        $sCacheKey='adm_is_ban_ip_'.$sIp;
        if (false === ($data = $this->Cache_Get($sCacheKey))) {
            $data = $this->oMapper->IsBanIp($sIp);
            $this->Cache_Set($data, $sCacheKey, array('adm_banlist_ip'), 60*15);
        }
        return $data;
    }

    public function GetUserVoteStat($sUserId) {
        return $this->oMapper->GetUserVoteStat($sUserId);
    }

    public function GetVotesByUserId($sUserId, $iPerPage) {
        $data = $this->oMapper->GetVotesByUserId($sUserId, $iPerPage);
        return $data;
    }

    public function AddAdministrator($nUserId) {
        $bOk=$this->oMapper->AddAdministrator($nUserId);
        if ($bOk) {
            $oUser=$this->GetUserById($nUserId);
            if ($oUser) $this->User_Update($oUser);
        }
        return $bOk;
    }

    public function DelAdministrator($nUserId) {
        $bOk=$this->oMapper->DelAdministrator($nUserId);
        if ($bOk) {
            $oUser=$this->GetUserById($nUserId);
            if ($oUser) $this->User_Update($oUser);
        }
        return $bOk;
    }

    public function DelUser($nUserId) {
        $bOk=$this->oMapper->DelUser($nUserId);
        if ($bOk) {
            // Удаляем блоги
            $aBlogsId = $this->Blog_GetBlogsByOwnerId($nUserId, true);
            if ($aBlogsId) {
                foreach ($aBlogsId as $sBlogId) $this->DelBlog($sBlogId, false);
            }
            // Удаляем переписку
            $iPerPage = 10000;
            do {
                $aTalks = $this->Talk_GetTalksByFilter(array('user_id'=>$nUserId), 1, $iPerPage);
                if ($aTalks['count']) $this->Talk_DeleteTalkUserByArray($aTalks['collection'], $nUserId);
            } while ($aTalks['count'] > $iPerPage);
            
            // Слишком много взаимосвязей, поэтому просто сбрасываем кеш
            $this->Cache_Clean();
        }
        return $bOk;
    }

    /**
     * Получить все блоги всех типов
     */
    public function GetBlogList($iCount, $iCurrPage, $iPerPage, $aParam=array()) {
        $sCacheKey = 'adm_blog_list_'.$iCurrPage.'_'.$iPerPage.'_'.serialize($aParam);
        if (false === ($data = $this->Cache_Get($sCacheKey))) {
            $data = array('collection'=>$this->oMapper->GetBlogList($iCount, $iCurrPage, $iPerPage, $aParam), 'count'=>$iCount);
            if ($data['collection'])
                foreach ($data['collection'] as $sBlogId=>$aBlog)
                    if ($aBlog['blog_type']=='personal') {
                        $data['collection'][$sBlogId]['blog_url_full'] = Router::GetPath('my').$aBlog['user_login'].'/';
                    } else {
                        $data['collection'][$sBlogId]['blog_url_full'] = Router::GetPath('blog').$aBlog['blog_url'].'/';
                    }
            $this->Cache_Set($data, $sCacheKey, array('blog_update', 'blog_new'), 60*15);
        }
        return $data;
    }

    /**
     * Получить типы блогов
     */
    public function GetBlogTypes() {
        $sCacheKey = 'adm_blog_types';
        if (false === ($data = $this->Cache_Get($sCacheKey))) {
            $data = $this->oMapper->GetBlogTypes();
            $this->Cache_Set($data, $sCacheKey, array('blog_update', 'blog_new'), 60*15);
        }
        return $data;
    }

    /**
     * Все (и персональные, и нет) блоги юзера
     */
    public function GetBlogsByUserId($sUserId) {
        $iCount = 0;
        $aParam = array('user_id'=>$sUserId);
        $data = $this->GetBlogList($iCount, 1, 1000, $aParam);
        return $data['collection'];
    }

    public function DelBlog($sBlogId, $bClearCache=true) {
        $aTopicsId = $this->Topic_GetTopicsByBlogId($sBlogId);
        $bOk = $this->Blog_DeleteBlog($sBlogId);
        if ($bOk) {
            if ($aTopicsId) {
                foreach ($aTopicsId as $sTopicId) $this->DelTopic($sTopicId, false);
            }
            // Слишком много взаимосвязей, поэтому просто сбрасываем кеш
            $this->Cache_Clean();
        }
        return $bOk;
    }

    public function DelTopic($sTopicId, $bClearCache=true) {
        $bOk = $this->Topic_DeleteTopic($sTopicId);
        if ($bOk) {
            $this->Comment_DeleteCommentByTargetId($sTopicId, 'topic');
            $this->Comment_DeleteCommentOnlineByTargetId($sTopicId, 'topic');
            $this->Topic_DeleteTopicTagsByTopicId($sTopicId);
            $this->Topic_DeleteTopicReadByArrayId($sTopicId);

            // Слишком много взаимосвязей, поэтому просто сбрасываем кеш
            if ($bClearCache) $this->Cache_Clean();
        }
    }

    public function AddUserVote($oUserVote) {
        return $this->oMapper->AddUserVote($oUserVote);
    }

    /**
     * Получить все инвайты
     */
    public function GetInvites($iCount, $iCurrPage, $iPerPage, $aParam=array()) {
        // Инвайты не кешируются, поэтому работаем напрямую с БД
        $sCacheKey = 'adm_invite_list_'.$iCurrPage.'_'.$iPerPage.'_'.serialize($aParam);
        $data = array('collection'=>$this->oMapper->GetInvites($iCount, $iCurrPage, $iPerPage, $aParam), 'count'=>$iCount);
        return $data;
    }

    /**
     * Проверка качества пароля
     */
    public function IsPasswordQuality($oUser) {
        if (md5($oUser->getLogin()) == $oUser->getPassword()) {
            return 0;
        } else {
            return 1;
        }
    }

    public function GetSiteStat() {
        $sCacheKey = 'adm_site_stat';
        if (false === ($data = $this->Cache_Get($sCacheKey))) {
            $data = $this->oMapper->GetSiteStat();
            $this->Cache_Set($data, $sCacheKey, array('user_new', 'blog_new', 'topic_new', 'comment_new'), 60*15);
        }
        return $data;
    }

    public function GetCustomConfigFile() {
        return admFilePath(Config::Get('sys.cache.dir').CUSTOM_CFG);
    }
}

// EOF