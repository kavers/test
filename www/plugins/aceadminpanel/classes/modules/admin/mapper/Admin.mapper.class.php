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
* @File Name: Admin.mapper.class.php
* @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*----------------------------------------------------------------------------
*/

class PluginAceadminpanel_ModuleAdmin_MapperAdmin extends Mapper {

    public function GetAdminValue($xKey, $xDefault=null) {
        $sValue = null;
        if (is_array($xKey)) {
            $sql = "SELECT adminset_val FROM ".Config::Get('db.table.adminset')." WHERE adminset_key IN (?a) ";
            $aRows = @$this->oDb->select($sql, $xKey);
            if ($aRows && is_array($aRows)) $sValue = $aRows[0]['adminset_val'];
        } elseif (strpos($xKey, 'prefix:')===0) {
            $sql = "SELECT adminset_key AS `key`, adminset_val AS `val` FROM ".Config::Get('db.table.adminset')." WHERE adminset_key LIKE ? ";
            $aRows = @$this->oDb->select($sql, substr($xKey, 7).'%');
            if ($aRows) $sValue = $aRows;
        } else {
            $sql = "SELECT adminset_val FROM ".Config::Get('db.table.adminset')." WHERE adminset_key = ? ";
            $sValue=@$this->oDb->selectCell($sql, $xKey);
        }
        if ($sValue===null && $xDefault!==null) $sValue=$xDefault;
        return $sValue;
    }

    public function SetAdminValue($sKey, $sValue) {
        $sql = "SELECT adminset_val FROM ".Config::Get('db.table.adminset')." WHERE adminset_key =? ";
        $row=$this->oDb->selectRow($sql, $sKey);
        if ($row) {
            $sOldValue=$row['adminset_val'];
            if ($sValue!=$sOldValue) {
                $bOk=$this->oDb->query("UPDATE ".Config::Get('db.table.adminset')." SET adminset_val=? WHERE adminset_key=? ", $sValue, $sKey);
            } else {
                $bOk=true;
            }
        } else {
            $sOldValue=null;
            $bOk=$this->oDb->query("INSERT INTO ".Config::Get('db.table.adminset')." (adminset_key, adminset_val) VALUES(?, ?)", $sKey, $sValue);
        }
        return Array('result'=>$bOk, 'old_value'=>$sOldValue);
    }

    public function DelAdminValue($xKey) {
        if (is_array($xKey)) {
            $sql = "DELETE FROM ".Config::Get('db.table.adminset')." WHERE adminset_key IN (?a) ";
            $nResult = @$this->oDb->query($sql, $xKey);
        } elseif (strpos($xKey, 'prefix:')===0) {
            $sql = "DELETE FROM ".Config::Get('db.table.adminset')." WHERE adminset_key LIKE ? ";
            $nResult = @$this->oDb->query($sql, substr($xKey, 7).'%');
        } else {
            $sql = "DELETE FROM ".Config::Get('db.table.adminset')." WHERE adminset_key=? ";
            $nResult = @$this->oDb->query($sql, $xKey);
        }
        return $nResult;
    }

    protected function BuildUserFilter($aFilter) {
        $sWhere='(1=1) ';
        if ($aFilter) {
            if (isset($aFilter['login'])) $sWhere.="AND (user_login='".$aFilter['login']."') ";
            if (isset($aFilter['like'])) $sWhere.="AND (user_login LIKE '".$aFilter['like']."%') ";
            if (isset($aFilter['admin'])) $sWhere.="AND (ua.user_id>0) ";
            if (isset($aFilter['ip'])) {
                $ip1=$ip2=$aFilter['ip'];
                if (strpos($aFilter['ip'], '*')!==false) {
                    $ip1=str_replace('*', '0', $ip1);
                    $ip2=str_replace('*', '255', $ip2);
                }
                /* form 0.3
                $sWhere.="AND (".
                        "(INET_ATON(user_ip_register) BETWEEN INET_ATON('".$ip1."') AND INET_ATON('".$ip2."')) OR ".
                        "(INET_ATON(user_ip_last) BETWEEN INET_ATON('".$ip1."') AND INET_ATON('".$ip2."')) ".
                        ")";
                 *
                */
                $sWhere.="AND (".
                        "(INET_ATON(user_ip_register) BETWEEN INET_ATON('".$ip1."') AND INET_ATON('".$ip2."')) ".
                        ")";
            }
            if (isset($aFilter['regdate'])) {
                $nY=intVal(substr($aFilter['regdate'], 0, 4));
                if ($nY) $sWhere.="AND (YEAR(user_date_register)=".$nY.") ";
                if (strlen($aFilter['regdate'])>5) {
                    $nM=intVal(substr($aFilter['regdate'], 5, 2));
                    if ($nM) $sWhere.="AND (MONTH(user_date_register)=".$nM.") ";
                }
                if (strlen($aFilter['regdate'])>8) {
                    $nD=intVal(substr($aFilter['regdate'], 8, 2));
                    if ($nD) $sWhere.="AND (DAYOFMONTH(user_date_register)=".$nD.") ";
                }
            }
        }
        return $sWhere;
    }

    protected function BuildUserSort($aSort) {
        $sSort='';
        if (isset($aSort['id'])) {
            $sSort='user_id ';
            if ($aSort['id']==1) $sSort.='ASC'; else $sSort.='DESC';
        }
        if (isset($aSort['login'])) {
            $sSort='user_login ';
            if ($aSort['login']==1) $sSort.='ASC'; else $sSort.='DESC';
        }
        if (isset($aSort['regdate'])) {
            $sSort='user_date_register ';
            if ($aSort['regdate']==1) $sSort.='ASC'; else $sSort.='DESC';
        }
        if (isset($aSort['reg_ip'])) {
            $sSort='INET_ATON(user_ip_register) ';
            if ($aSort['reg_ip']==1) $sSort.='ASC'; else $sSort.='DESC';
        }
        if (isset($aSort['activated'])) {
            $sSort='user_date_activate ';
            if ($aSort['activated']==1) $sSort.='ASC'; else $sSort.='DESC';
        }

        if (isset($aSort['last_date'])) {
            $sSort='session_date_last ';
            if ($aSort['last_date']==1) $sSort.='ASC'; else $sSort.='DESC';
        }
        if (isset($aSort['last_ip'])) {
            $sSort='INET_ATON(session_ip_last) ';
            if ($aSort['last_ip']==1) $sSort.='ASC'; else $sSort.='DESC';
        }

        if (!$sSort) $sSort='user_id ASC';
        return($sSort);
    }

    public function GetUserList(&$iCount, $iCurrPage, $iPerPage, $aFilter=Array(), $aSort=Array()) {
        $aReturn=array();

        $sFieldList =
                "u.user_id, user_login, user_date_register, user_skill,
            user_rating, user_activate, user_date_activate, user_date_comment_last,
            user_ip_register, user_profile_avatar, 
            IF(ua.user_id IS NULL,0,1) as user_is_administrator,
            ab.banline, ab.banunlim, ab.banactive,
            session_ip_create, session_ip_last, session_date_create, session_date_last 
            ";
        $sWhere=$this->BuildUserFilter($aFilter);
        $sOrder=$this->BuildUserSort($aSort);

        $sql =
                "SELECT ".$sFieldList."
            FROM
                ".Config::Get('db.table.user')." AS u
            LEFT JOIN ".Config::Get('db.table.adminban')." AS ab ON u.user_id=ab.user_id
            LEFT JOIN ".Config::Get('db.table.user_administrator')." AS ua ON u.user_id=ua.user_id
            LEFT JOIN ".Config::Get('db.table.session')." AS us ON u.user_id=us.user_id
            WHERE
                ".$sWhere."
            ORDER BY ".$sOrder."
            LIMIT ?d, ?d
            ";

        $aRows=$this->oDb->selectPage($iCount, $sql, ($iCurrPage-1)*$iPerPage, $iPerPage);

        if ($aRows) {
            foreach ($aRows as $aRow) {
                $aReturn[]=new PluginAceadminpanel_ModuleAdmin_EntityUser($aRow);
            }
        }
        return $aReturn;
    }

    public function GetCountCommentsByUserId($sUserId) {
        return 0;
        $sql="SELECT Count(*) AS cnt FROM ".Config::Get('db.table.topic_comment')." WHERE user_id=?";
        return $this->oDb->selectCell($sql, $sUserId);
    }

    public function GetCountTopicsByUserId($sUserId) {
        $sql="SELECT Count(*) AS cnt FROM ".Config::Get('db.table.topic')." WHERE user_id=?";
        return $this->oDb->selectCell($sql, $sUserId);
    }

    public function GetUserById($nUserId) {
        $iCurrentUserId=-1;
        $sql =
                "SELECT
                u.*,
  		IF(ua.user_id IS NULL,0,1) as user_is_administrator,
                ab.banline, ab.banunlim, ab.bancomment, ab.banactive,
		INET_ATON('".func_getIp()."') as ipn
            FROM
                ".Config::Get('db.table.user')." as u
            LEFT JOIN ".Config::Get('db.table.user_administrator')." AS ua ON u.user_id=ua.user_id
            LEFT JOIN ".Config::Get('db.table.adminban')." AS ab ON u.user_id=ab.user_id
            WHERE
                u.user_id = ? ";
        if (($aRow=@$this->oDb->selectRow($sql, $nUserId))) {
            $aRow['tpoics_count']=$this->GetCountTopicsByUserId($nUserId);
            $aRow['comments_count']=$this->GetCountCommentsByUserId($nUserId);
            $sql=
                    "SELECT id FROM ".Config::Get('db.table.adminips')."
                WHERE (? BETWEEN ip1 AND ip2) AND (banactive=1) AND (banunlim>0 OR Now()<banline) ";
            if (($nId=$this->oDb->selectCell($sql, $aRow['ipn']))) {
                $aRow['ban_ip']=$nId;
            } else {
                $aRow['ban_ip']=0;
            }
            return new PluginAceadminpanel_ModuleAdmin_EntityUser($aRow);
        }
        return null;
    }


    public function GetUserId($sUserLogin) {
        $sql = "SELECT user_id FROM ".Config::Get('db.table.user')." WHERE user_login=?";
        return $this->oDb->selectCell($sql, strtolower($sUserLogin));
    }

    public function SetUserBan($nUserId, $dDate, $nUnlim, $sComment=null) {

        $sql="SELECT user_id FROM ".Config::Get('db.table.adminban')." WHERE user_id=?";
        if ($this->oDb->selectCell($sql, $nUserId)) {
            $sql="UPDATE ".Config::Get('db.table.adminban')." SET bandate=?, banline=?, banunlim=?, bancomment=? WHERE user_id=?";
            $result=$this->oDb->query($sql, date("Y-m-d H:i:s"), $dDate, $nUnlim, $sComment, $nUserId);
        } else {
            $sql="INSERT INTO ".Config::Get('db.table.adminban')."(user_id, bandate, banline, banunlim, bancomment) VALUES(?, ?, ?, ?, ?)";
            $result=$this->oDb->query($sql, $nUserId, date("Y-m-d H:i:s"), $dDate, $nUnlim, $sComment);
        }
        return $result;
    }

    public function SetBanUser($nUserId, $dDate, $nUnlim, $sComment=null) {
        $sql="SELECT user_id FROM ".Config::Get('db.table.adminban')." WHERE user_id=?";
        if ($this->oDb->selectCell($sql, $nUserId)) {
            $sql="UPDATE ".Config::Get('db.table.adminban')." SET bandate=?, banline=?, banunlim=?, bancomment=?, banactive=1 WHERE user_id=?";
            $result=$this->oDb->query($sql, date("Y-m-d H:i:s"), $dDate, $nUnlim, $sComment, $nUserId);
        } else {
            $sql="INSERT INTO ".Config::Get('db.table.adminban')."(user_id, bandate, banline, banunlim, bancomment, banactive) VALUES(?, ?, ?, ?, ?, 1)";
            $result=$this->oDb->query($sql, $nUserId, date("Y-m-d H:i:s"), $dDate, $nUnlim, $sComment);
        }
        //$sql="UPDATE ".Config::Get('db.table.user')." SET user_key=NULL WHERE user_id=?d";
        //$result=$result && $this->oDb->query($sql, $nUserId);
        return $result;
    }

    public function DelBanUser($nUserId) {
        $sql="UPDATE ".Config::Get('db.table.adminban')." SET banactive=0, banunlim=0 WHERE user_id=?";
        $result=$this->oDb->query($sql, $nUserId);
        //$sql="UPDATE ".Config::Get('db.table.user')." SET user_key=NULL WHERE user_id=?d";
        //$this->oDb->query($sql, $nUserId);
        return $result;
    }

    public function GetBanList(&$iCount, $iCurrPage, $iPerPage, $aFilter=Array(), $aSort=Array()) {
        $aReturn=array();
        $sFieldList =
                "u.user_id, user_login, user_date_register, user_skill,
            user_rating, user_activate, user_date_activate, user_date_comment_last,
            user_ip_register, user_profile_avatar, 
            IF(ua.user_id IS NULL,0,1) as user_is_administrator,
            ab.banline, ab.banunlim, ab.bancomment, ab.banactive
            ";

        $sWhere=$this->BuildUserFilter($aFilter);
        $sOrder=$this->BuildUserSort($aSort);

        $sql =
                "SELECT ".$sFieldList."
            FROM
                ".Config::Get('db.table.user')." AS u
            LEFT JOIN ".Config::Get('db.table.adminban')." AS ab ON u.user_id=ab.user_id
            LEFT JOIN ".Config::Get('db.table.user_administrator')." AS ua ON u.user_id=ua.user_id
            WHERE (ab.user_id>0) AND (ab.banunlim>0 OR (Now()<ab.banline AND ab.banactive=1)) ".
                " AND ".$sWhere."
            ORDER BY ".$sOrder."
            LIMIT ?d, ?d
            ";
        $aRows=$this->oDb->selectPage($iCount, $sql, ($iCurrPage-1)*$iPerPage, $iPerPage);
        if ($aRows) {
            foreach ($aRows as $aRow) {
                $aReturn[]=new PluginAceadminpanel_ModuleAdmin_EntityUser($aRow);
            }
        }
        return $aReturn;
    }

    public function GetBanListIp(&$iCount, $iCurrPage, $iPerPage) {
        $aReturn=array();

        $sql =
                "SELECT
            ips.id,
            CASE WHEN ips.ip1<>0 THEN INET_NTOA(ips.ip1) ELSE '' END AS `ip1`,
            CASE WHEN ips.ip2<>0 THEN INET_NTOA(ips.ip2) ELSE '' END AS `ip2`,
            ips.bandate, ips.banline, ips.banunlim, ips.bancomment
 	FROM 
            ".Config::Get('db.table.adminips')." AS ips
	WHERE banactive=1
        ORDER BY ips.id
 	LIMIT ?d, ?d				
        ";
        $aRows=$this->oDb->selectPage($iCount, $sql, ($iCurrPage-1)*$iPerPage, $iPerPage);

        if ($aRows) {
            $aReturn=$aRows;
        }
        return $aReturn;
    }

    public function SetBanIp($sIp1, $sIp2, $dDate, $nUnlim, $sComment) {
        $sql=
                "INSERT INTO ".Config::Get('db.table.adminips')."(ip1, ip2, bandate, banline, banunlim, bancomment, banactive)
            VALUES(INET_ATON(?), INET_ATON(?), ?, ?, ?, ?, 1)";
        $result=$this->oDb->query($sql, $sIp1, $sIp2, date("Y-m-d H:i:s"), $dDate, $nUnlim, $sComment);

        return $result;
    }

    public function DelBanIp($nId) {
        $sql="UPDATE ".Config::Get('db.table.adminips')." SET banactive=0, banunlim=0 WHERE id=?d";
        $result=$this->oDb->query($sql, $nId);

        return $result;
    }

    public function IsBanIp($sIp) {
        $sql =
                "SELECT Count(*)
            FROM ".Config::Get('db.table.adminips')."
            WHERE (INET_ATON(?) BETWEEN ip1 AND ip2) AND (banactive=1)";
        $result = $this->oDb->selectCell($sql, $sIp);
        return $result;
    }

    public function GetUserVoteStat0($sUserId) {
        $aResult=Array(
                'cnt_topics_m'=>0, 'cnt_topics_p'=>0, 'sum_topics_m'=>0.0, 'sum_topics_p'=>0.0,
                'cnt_users_m'=>0, 'cnt_users_p'=>0, 'sum_users_m'=>0.0, 'sum_users_p'=>0.0,
                'cnt_comments_m'=>0, 'cnt_comments_p'=>0, 'sum_comments_m'=>0.0, 'sum_comments_p'=>0.0,
        );

        $sql=
                "SELECT
                Sum(CASE WHEN vote_delta<0 THEN 1 ELSE 0 END) AS cnt_topics_m,
                Sum(CASE WHEN vote_delta>0 THEN 1 ELSE 0 END) AS cnt_topics_p,
                Sum(CASE WHEN vote_delta<0 THEN vote_delta ELSE 0 END) AS sum_topics_m,
                Sum(CASE WHEN vote_delta>0 THEN vote_delta ELSE 0 END) AS sum_topics_p
            FROM ".Config::Get('db.table.topic_vote')."
            WHERE user_voter_id=?";
        $aRow=$this->oDb->selectRow($sql, $sUserId);

        if ($aRow) {
            $aResult['cnt_topics_m']=intVal($aRow['cnt_topics_m']);
            $aResult['cnt_topics_p']=intVal($aRow['cnt_topics_p']);
            $aResult['sum_topics_m']=sprintf('%0.4f', $aRow['sum_topics_m']);
            $aResult['sum_topics_p']=sprintf('%0.4f', $aRow['sum_topics_p']);
        }

        $sql=
                "SELECT
                Sum(CASE WHEN vote_delta<0 THEN 1 ELSE 0 END) AS cnt_users_m,
                Sum(CASE WHEN vote_delta>0 THEN 1 ELSE 0 END) AS cnt_users_p,
                Sum(CASE WHEN vote_delta<0 THEN vote_delta ELSE 0 END) AS sum_users_m,
                Sum(CASE WHEN vote_delta>0 THEN vote_delta ELSE 0 END) AS sum_users_p
            FROM ".Config::Get('db.table.user_vote')."
            WHERE user_voter_id=?";
        $aRow=$this->oDb->selectRow($sql, $sUserId);

        if ($aRow) {
            $aResult['cnt_users_m']=intVal($aRow['cnt_users_m']);
            $aResult['cnt_users_p']=intVal($aRow['cnt_users_p']);
            $aResult['sum_users_m']=sprintf('%0.4f', $aRow['sum_users_m'], 4);
            $aResult['sum_users_p']=sprintf('%0.4f', $aRow['sum_users_p']);
        }

        $sql=
                "SELECT
                Sum(CASE WHEN vote_delta<0 THEN 1 ELSE 0 END) AS cnt_comments_m,
                Sum(CASE WHEN vote_delta>0 THEN 1 ELSE 0 END) AS cnt_comments_p,
                Sum(CASE WHEN vote_delta<0 THEN vote_delta ELSE 0 END) AS sum_comments_m,
                Sum(CASE WHEN vote_delta>0 THEN vote_delta ELSE 0 END) AS sum_comments_p
            FROM ".Config::Get('db.table.topic_comment_vote')."
            WHERE user_voter_id=?";
        $aRow=$this->oDb->selectRow($sql, $sUserId);

        if ($aRow) {
            $aResult['cnt_comments_m']=intVal($aRow['cnt_comments_m']);
            $aResult['cnt_comments_p']=intVal($aRow['cnt_comments_p']);
            $aResult['sum_comments_m']=sprintf('%0.4f', $aRow['sum_comments_m']);
            $aResult['sum_comments_p']=sprintf('%0.4f', $aRow['sum_comments_p']);
        }

        return $aResult;
    }

    public function GetUserVoteStat($sUserId) {
        $aResult=Array(
                'cnt_topics_m'=>0, 'cnt_topics_p'=>0, 'sum_topics_m'=>0.0, 'sum_topics_p'=>0.0,
                'cnt_users_m'=>0, 'cnt_users_p'=>0, 'sum_users_m'=>0.0, 'sum_users_p'=>0.0,
                'cnt_comments_m'=>0, 'cnt_comments_p'=>0, 'sum_comments_m'=>0.0, 'sum_comments_p'=>0.0,
        );

        $sql=
                "SELECT
                Sum(CASE WHEN vote_value<0 AND target_type='topic' THEN 1 ELSE 0 END) AS cnt_topics_m,
                Sum(CASE WHEN vote_value>0 AND target_type='topic'  THEN 1 ELSE 0 END) AS cnt_topics_p,
                Sum(CASE WHEN vote_value<0 AND target_type='topic'  THEN vote_value ELSE 0 END) AS sum_topics_m,
                Sum(CASE WHEN vote_value>0 AND target_type='topic'  THEN vote_value ELSE 0 END) AS sum_topics_p,
                Sum(CASE WHEN vote_value<0 AND target_type='user' THEN 1 ELSE 0 END) AS cnt_users_m,
                Sum(CASE WHEN vote_value>0 AND target_type='user' THEN 1 ELSE 0 END) AS cnt_users_p,
                Sum(CASE WHEN vote_value<0 AND target_type='user' THEN vote_value ELSE 0 END) AS sum_users_m,
                Sum(CASE WHEN vote_value>0 AND target_type='user' THEN vote_value ELSE 0 END) AS sum_users_p,
                Sum(CASE WHEN vote_value<0 AND target_type='comment' THEN 1 ELSE 0 END) AS cnt_comments_m,
                Sum(CASE WHEN vote_value>0 AND target_type='comment' THEN 1 ELSE 0 END) AS cnt_comments_p,
                Sum(CASE WHEN vote_value<0 AND target_type='comment' THEN vote_value ELSE 0 END) AS sum_comments_m,
                Sum(CASE WHEN vote_value>0 AND target_type='comment' THEN vote_value ELSE 0 END) AS sum_comments_p
            FROM ".Config::Get('db.table.vote')."
            WHERE user_voter_id=?";
        $aRow=$this->oDb->selectRow($sql, $sUserId);

        if ($aRow) {
            $aResult['cnt_topics_m']=intVal($aRow['cnt_topics_m']);
            $aResult['cnt_topics_p']=intVal($aRow['cnt_topics_p']);
            $aResult['sum_topics_m']=sprintf('%0.4f', $aRow['sum_topics_m']);
            $aResult['sum_topics_p']=sprintf('%0.4f', $aRow['sum_topics_p']);
            $aResult['cnt_users_m']=intVal($aRow['cnt_users_m']);
            $aResult['cnt_users_p']=intVal($aRow['cnt_users_p']);
            $aResult['sum_users_m']=sprintf('%0.4f', $aRow['sum_users_m'], 4);
            $aResult['sum_users_p']=sprintf('%0.4f', $aRow['sum_users_p']);
            $aResult['cnt_comments_m']=intVal($aRow['cnt_comments_m']);
            $aResult['cnt_comments_p']=intVal($aRow['cnt_comments_p']);
            $aResult['sum_comments_m']=sprintf('%0.4f', $aRow['sum_comments_m']);
            $aResult['sum_comments_p']=sprintf('%0.4f', $aRow['sum_comments_p']);
        }

        return $aResult;
    }

    public function GetVotesByUserId($sUserId, $iPerPage) {
        $sql="SELECT target_id, target_type, vote_value, topic_title AS title, user_login, vote_date
		FROM ".Config::Get('db.table.vote')." AS v
			LEFT JOIN ".Config::Get('db.table.topic')." AS t ON t.topic_id=v.target_id
			LEFT JOIN ".Config::Get('db.table.user')." AS u ON u.user_id=t.user_id
		WHERE target_type='topic' AND user_voter_id=? ORDER BY vote_date DESC LIMIT ?d";
        $aResult['topics']=$this->oDb->select($sql, $sUserId, $iPerPage);

        $sql="SELECT vote_value, blog_title AS title, user_login, vote_date
		FROM ".Config::Get('db.table.vote')." AS v
			LEFT JOIN ".Config::Get('db.table.blog')." AS b ON b.blog_id=v.target_id
			LEFT JOIN ".Config::Get('db.table.user')." AS u ON u.user_id=b.user_owner_id
		WHERE target_type='blog' AND user_voter_id=? ORDER BY vote_date DESC LIMIT ?d";
        $aResult['blogs']=$this->oDb->select($sql, $sUserId, $iPerPage);

        $sql="SELECT vote_value, IF(Length(comment_text)>200,Concat(Left(comment_text, 197), '...'), comment_text) AS title,
                user_login, vote_date
		FROM ".Config::Get('db.table.vote')." AS v
			LEFT JOIN ".Config::Get('db.table.comment')." AS c ON c.comment_id=v.target_id AND c.target_type='topic'
			LEFT JOIN ".Config::Get('db.table.user')." AS u ON u.user_id=c.user_id
		WHERE v.target_type='comment' AND  user_voter_id=? ORDER BY vote_date DESC LIMIT ?d";
        $aResult['comments']=$this->oDb->select($sql, $sUserId, $iPerPage);

        $sql="SELECT vote_value, user_profile_name AS title, user_login, vote_date
		FROM ".Config::Get('db.table.vote')." AS v
			LEFT JOIN ".Config::Get('db.table.user')." AS u ON u.user_id=v.target_id
		WHERE target_type='user' AND  user_voter_id=? ORDER BY vote_date DESC LIMIT ?d";
        $aResult['users']=$this->oDb->select($sql, $sUserId, $iPerPage);
        return $aResult;
    }

    public function AddAdministrator($nUserId) {
        $sql="SELECT user_id FROM ".Config::Get('db.table.user_administrator')." WHERE user_id=?";
        if (!$this->oDb->selectCell($sql, $nUserId)) {
            $nCnt=$this->oDb->selectCell("SELECT Count(*) FROM ".Config::Get('db.table.user_administrator'));
            $this->oDb->query("INSERT INTO ".Config::Get('db.table.user_administrator')." (user_id) VALUES(?)", $nUserId);
            $bOk=($nCnt!=$this->oDb->selectCell("SELECT Count(*) FROM ".Config::Get('db.table.user_administrator')));
        }
        return $bOk;
    }

    public function DelAdministrator($nUserId) {
        $sql="DELETE FROM ".Config::Get('db.table.user_administrator')." WHERE user_id=?";
        $bOk=$this->oDb->query($sql, $nUserId);
        return $bOk;
    }

    public function DelUser($nUserId) {
        $bOk=true;
        // Удаление комментов

        // находим комменты удаляемого юзера и для каждого:
        // нижележащее дерево комментов подтягиваем к родителю удаляемого
        $sql=
                "SELECT comment_id AS ARRAY_KEY, comment_pid
                FROM ".Config::Get('db.table.comment')."
                WHERE user_id=?d";

        while ($aComments=$this->oDb->selectCol($sql, $nUserId)) {
            if (is_array($aComments) && sizeof($aComments)) {
                foreach($aComments AS $sId=>$sPid) {
                    $this->oDb->transaction();
                    $sql="UPDATE ".Config::Get('db.table.comment')." SET comment_pid=?d WHERE comment_pid=?d";
                    @$this->oDb->query($sql, $sPid, $sId);
                    $sql="DELETE FROM ".Config::Get('db.table.comment')." WHERE comment_id=?d";
                    @$this->oDb->query($sql, $sId);
                    $this->oDb->commit();
                }
            } else {
                break;
            }
        }

        // удаление остального "хозяйства"
        $this->oDb->transaction();
        $sql="DELETE FROM ".Config::Get('db.table.topic')." WHERE user_id=?d";
        @$this->oDb->query($sql, $nUserId);

        $sql="DELETE FROM ".Config::Get('db.table.blog')." WHERE user_owner_id=?d";
        @$this->oDb->query($sql, $nUserId);

        $sql="DELETE FROM ".Config::Get('db.table.vote')." WHERE user_voter_id=?d";
        @$this->oDb->query($sql, $nUserId);

        //$sql="DELETE FROM ".Config::Get('db.table.topic_comment_vote')." WHERE user_voter_id=?d";
        //@$this->oDb->query($sql, $nUserId);

        //$sql="DELETE FROM ".Config::Get('db.table.user_vote')." WHERE user_voter_id=?d";
        //@$this->oDb->query($sql, $nUserId);

        //$sql="DELETE FROM ".Config::Get('db.table.blog_vote')." WHERE user_voter_id=?d";
        //@$this->oDb->query($sql, $nUserId);

        $sql="DELETE FROM ".Config::Get('db.table.blog_user')." WHERE user_id=?d";
        @$this->oDb->query($sql, $nUserId);

        $sql="DELETE FROM ".Config::Get('db.table.city_user')." WHERE user_id=?d";
        @$this->oDb->query($sql, $nUserId);

        $sql="DELETE FROM ".Config::Get('db.table.country_user')." WHERE user_id=?d";
        @$this->oDb->query($sql, $nUserId);

        $sql="DELETE FROM ".Config::Get('db.table.user')." WHERE user_id=?d";
        @$this->oDb->query($sql, $nUserId);

        $sql="DELETE FROM ".Config::Get('db.table.adminban')." WHERE user_id=?d";
        @$this->oDb->query($sql, $nUserId);

        $this->oDb->commit();

        $bOk=$this->oDb->selectCell("SELECT user_id FROM ".Config::Get('db.table.user')." WHERE user_id=?d", $nUserId);
        return !$bOk;
    }

    /**
     * Получить типы блогов
     */
    public function GetBlogTypes() {
        $sql = "SELECT DISTINCT Count( blog_id ) AS blog_cnt , blog_type
		FROM ".Config::Get('db.table.blog')." AS b
		GROUP BY blog_type
		ORDER BY blog_type";
        $aRows = $this->oDb->select($sql);
        return $aRows;
    }

    /**
     * Получить все блоги всех типов
     */
    public function GetBlogList(&$iCount, $iCurrPage, $iPerPage, $aParams=array()) {
        $sWhere = '1=1';
        if (isset($aParams['type']) && $aParams['type'])
            $sWhere.=" AND (blog_type=".$this->oDb->escape($aParams['type']).")";

        if (isset($aParams['user_id']))
            $sWhere.=" AND (user_owner_id=".intVal($aParams['user_id']).")";

        $sql =
                "SELECT b.blog_id AS ARRAY_KEY, b.blog_id, blog_title, blog_url, blog_rating, blog_count_vote,
                blog_count_user, blog_date_add, blog_date_edit, blog_type,
                user_owner_id, u.user_login
            FROM ".Config::Get('db.table.blog')." AS b
                LEFT JOIN ".Config::Get('db.table.user')." AS u ON u.user_id=b.user_owner_id
            WHERE ".$sWhere."
            ORDER BY blog_id
            LIMIT ?d, ?d";
        if (($aBlogs=$this->oDb->selectPage($iCount, $sql, ($iCurrPage-1)*$iPerPage, $iPerPage))) {
            return $aBlogs;
        }
        return array();
    }

    public function GetBlogsByUserId($sUserId) {
        $sql =
                "SELECT b.blog_id AS ARRAY_KEY, b.blog_id, blog_title, blog_url, blog_rating, blog_count_vote,
                blog_count_user, blog_date_add, blog_date_edit, blog_type
            FROM ".Config::Get('db.table.blog')." AS b
            WHERE user_owner_id = ?d";
        if (($aBlogs=$this->oDb->select($sql, $sUserId))) {
            return $aBlogs;
        }
        return array();
    }

    public function AddUserVote($oUserVote) {
        $sql =
                "INSERT INTO ".Config::Get('db.table.user_vote')."
		(user_id, user_voter_id, vote_delta)
            VALUES(?d,  ?d,	?f) ON DUPLICATE KEY UPDATE vote_delta = vote_delta + ?f
            ";
        if ($this->oDb->query($sql,$oUserVote->getUserId(), $oUserVote->getVoterId(), $oUserVote->getDelta(), $oUserVote->getDelta())===0) {
            return true;
        }
        return false;
    }

    public function GetInvites(&$iCount, $iCurrPage, $iPerPage, $aParam=array()) {
        $sSort = 'invite_id';
        $sOrder = 'DESC';
        if (isset($aParam['sort'])) {
            if ($aParam['sort'] == 'code') {
                $sSort = 'i.invite_code';
            }
            elseif ($aParam['sort'] == 'user_from') {
                $sSort = 'u1.user_login';
            }
            elseif ($aParam['sort'] == 'date_add') {
                $sSort = 'i.invite_date_add';
            }
            elseif ($aParam['sort'] == 'user_to') {
                $sSort = 'u2.user_login';
            }
            elseif ($aParam['sort'] == 'date_used') {
                $sSort = 'i.invite_used';
            }
            else {
                $sSort = 'invite_id';
            }
        }
        if (isset($aParam['order'])) {
            if ($aParam['order'] == 1) {
                $sOrder = 'DESC';
            }
            else {
                $sOrder = 'ASC';
            }
        }
        $sql =
                "SELECT invite_id, invite_code, user_from_id, user_to_id,
                invite_date_add, invite_date_used, invite_used,
                u1.user_login AS from_login,
                u2.user_login AS to_login
              FROM ".Config::Get('db.table.invite')." AS i
                LEFT JOIN ".Config::Get('db.table.user')." AS u1 ON i.user_from_id=u1.user_id
                LEFT JOIN ".Config::Get('db.table.user')." AS u2 ON i.user_to_id=u2.user_id
            ORDER BY ".$sSort." ".$sOrder."
            LIMIT ?d, ?d";
        if (($aRows=$this->oDb->selectPage($iCount, $sql, ($iCurrPage-1)*$iPerPage, $iPerPage))) {
            return $aRows;
        }
        return array();
    }

    public function GetSiteStat() {
        $aResult = array();
        $sql = "SELECT Count(*) FROM ".Config::Get('db.table.user');
        $aResult['users'] = $this->oDb->selectCell($sql);
        $sql = "SELECT Count(*) FROM ".Config::Get('db.table.blog');
        $aResult['blogs'] = $this->oDb->selectCell($sql);
        $sql = "SELECT Count(*) FROM ".Config::Get('db.table.topic');
        $aResult['topics'] = $this->oDb->selectCell($sql);
        $sql = "SELECT Count(*) FROM ".Config::Get('db.table.comment')." WHERE target_type='topic'";
        $aResult['comments'] = $this->oDb->selectCell($sql);
        return $aResult;
    }

}

// EOF