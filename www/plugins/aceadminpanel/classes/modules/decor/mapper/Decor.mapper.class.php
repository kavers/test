<?php
/*
 * Vladimir Yuriev
 * vladimir.o.yuriev@gmail.com
*/

class PluginAceadminpanel_ModuleDecor_MapperDecor extends Mapper {
    
    public function AddDecor(PluginAceadminpanel_ModuleDecor_EntityDecor $oDecor) {
        $sql = 'INSERT INTO '.Config::Get('db.table.decor').'
			(dec_title,
			dec_date_add,
                        dec_description,
                        dec_position,
			dec_category,
			dec_price,
                        dec_avatar
			)
			VALUES(?,	?,?,?,	?,?d,?)
		';
		if ($iId=$this->oDb->query($sql,$oDecor->getDecTitle(),$oDecor->getDecDateAdd(),$oDecor->getDecDescription(),$oDecor->getDecPosition(),$oDecor->getDecCategory(),$oDecor->getDecPrice(),$oDecor->getDecAvatar()))
		{
			$oDecor->setDecId($iId);
			return $iId;
		}
		return false;
    }

    public function UpdateDecor(PluginAceadminpanel_ModuleDecor_EntityDecor $oDecor) {
       $sql = "UPDATE ".Config::Get('db.table.decor')."
			SET
				dec_title= ?,
                                dec_date_edit=?,
                                dec_description =?,
                                dec_position = ?,
				dec_category= ?,
				dec_price = ?d,
                                dec_avatar= ?
			WHERE
				dec_id = ?d
		";
		if ($this->oDb->query($sql,$oDecor->getDecTitle(),$oDecor->getDecDateEdit(),$oDecor->getDecDescription(),$oDecor->getDecPosition(),$oDecor->getDecCategory(),$oDecor->getDecPrice(),$oDecor->getDecAvatar(),$oDecor->getDecId())) {
			return true;
		}
		return false;
    }

     public function GetDecors($aFilter) {

        $sql = 'SELECT
                        *
                FROM
                        '.Config::Get('db.table.decor').'
                WHERE
                    1=1
                    { AND dec_category = ? }
                ORDER BY dec_id';
        $aDecor=array();
        if ($aRows=$this->oDb->select($sql,isset($aFilter['category'])?$aFilter['category']:DBSIMPLE_SKIP)) {
                foreach ($aRows as $oDecor) {
                        $aDecor[]=Engine::GetEntity('PluginAceadminpanel_ModuleDecor_EntityDecor',$oDecor);
                }
        }
        return $aDecor;
    }

    public function GetFavDecors($aFilter) {
        $sFilter=$this->paramFilter($aFilter);
        $sql = 'SELECT
                        *
                FROM
                        '.Config::Get('db.table.decfav').' as tf
                        LEFT JOIN (
                                SELECT
                                        *
                                FROM '.Config::Get('db.table.decor').'
                        ) AS tpl ON tpl.dec_id = tf.dec_id
                WHERE
                    1=1
                    '.$sFilter.'
                ORDER BY tf.dec_id';
        $aDecor=array();
        if ($aRows=$this->oDb->select($sql)) {
                foreach ($aRows as $oDecor) {
                        $aDecor[]=Engine::GetEntity('PluginAceadminpanel_ModuleDecor_EntityDecor',$oDecor);
                }
        }
        return $aDecor;
    }

    public function paramFilter($aFilter) {
        $sWhere='';
		if (isset($aFilter['type'])) {
			$sWhere.=" AND tf.dec_type =  '".$aFilter['type']."'";
		}
		
		if (isset($aFilter['user_id'])) {
			$sWhere.=" AND tf.target_id =  '".(int)$aFilter['user_id']."'";
		}


		return $sWhere;
    }

    public function GetDecById($sId) {
        $sql = 'SELECT
                        *
                FROM
                        '.Config::Get('db.table.decor').'
                WHERE
                    dec_id = ?d
                ';
        if ($aRow=$this->oDb->selectRow($sql,$sId)) {
                return new PluginAceadminpanel_ModuleDecor_EntityDecor($aRow);
        }
        return false;
    }

     public function SetDecor($sType,$iTarget,$iDec) {
        $sql = 'INSERT INTO '.Config::Get('db.table.decusers').'
			(dec_type,
			target_id,
			dec_id
			)
			VALUES(?,  ?d,	?d)
		';
		if ($this->oDb->query($sql,$sType,$iTarget,$iDec))
		{
			return true;
		}
		return false;
     }

     public function DeleteDecor($sType,$iTarget,$iDec) {
		$sql = "DELETE FROM ".Config::Get('db.table.decusers')."
			WHERE
				dec_type = ?
                                AND
                                target_id = ?d
                                AND
                                dec_id = ?d
		";
		if ($this->oDb->query($sql,$sType,$iTarget,$iDec)) {
			return true;
		}
		return false;
    }

    public function AdminDeleteDecor($iDec) {

                $sql1 = "DELETE FROM ".Config::Get('db.table.decor')."
			WHERE
                        dec_id = ?d
		";
                $sql2 = "DELETE FROM ".Config::Get('db.table.decusers')."
			WHERE
                        dec_id = ?d
		";
                $sql3 = "DELETE FROM ".Config::Get('db.table.decfav')."
			WHERE
                        dec_id = ?d
		";
		if ($this->oDb->query($sql1,$iDec) && $this->oDb->query($sql2,$iDec) && $this->oDb->query($sql3,$iDec)) {
			return true;
		}
		return false;
    }

      public function SetFavDecor($sType,$iTarget,$iDec) {
        $sql = 'INSERT INTO '.Config::Get('db.table.decfav').'
			(dec_type,
			target_id,
			dec_id
			)
			VALUES(?,  ?d,	?d)
		';
		if ($this->oDb->query($sql,$sType,$iTarget,$iDec))
		{
			return true;
		}
		return false;
     }

     public function DeleteFavDecor($sType,$iTarget,$iDec) {
		$sql = "DELETE FROM ".Config::Get('db.table.decfav')."
			WHERE
				dec_type = ?
                                AND
                                target_id = ?d
                                AND
                                dec_id = ?d
		";
		if ($this->oDb->query($sql,$sType,$iTarget,$iDec)) {
			return true;
		}
		return false;
	}

     public function GetDecActiveByTargetTypeAndTargetId($sId,$sType) {
        $sql = 'SELECT
                        *
                FROM
                        '.Config::Get('db.table.decusers').'
                WHERE
                    target_id = ?d
                    AND
                    dec_type = ?
                ';
        if ($aRow=$this->oDb->selectRow($sql,$sId,$sType)) {
                return new PluginAceadminpanel_ModuleDecor_EntityDecor($aRow);
        }
        return false;
    }

    public function GetFavByIdAndUserId($sType,$iTarget,$iDec) {
        $sql = 'SELECT
                        *
                FROM
                        '.Config::Get('db.table.decfav').'
                WHERE
                    dec_type=?
                    AND
                    target_id = ?d
                    AND
                    dec_id = ?
                ';
        if ($aRow=$this->oDb->selectRow($sql,$sType,$iTarget,$iDec)) {
                return new PluginAceadminpanel_ModuleDecor_EntityDecor($aRow);
        }
        return false;
    }

    public function GetCountUsers($iDecId) {
        $sql = "SELECT
                count(dec_id) as count
        FROM
                ".Config::Get('db.table.decusers')."
        WHERE
                dec_id = ?d;";
        if ($aRow=$this->oDb->selectRow($sql,$iDecId)) {
                return $aRow['count'];
        }
        return '0';
    }

    
}

// EOF