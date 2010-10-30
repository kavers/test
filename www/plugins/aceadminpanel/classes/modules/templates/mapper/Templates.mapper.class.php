<?php
/*
 * Vladimir Yuriev
 * vladimir.o.yuriev@gmail.com
*/

class PluginAceadminpanel_ModuleTemplates_MapperTemplates extends Mapper {
    
    public function AddTemplate(PluginAceadminpanel_ModuleTemplates_EntityTemplates $oTemplate) {
        $sql = 'INSERT INTO '.Config::Get('db.table.templates').'
			(tpl_title,
			tpl_name,
			tpl_date_add,
                        tpl_description,
			tpl_category,
			tpl_price,
                        tpl_avatar
			)
			VALUES(?,  ?,	?,?,	?,?d,?)
		';
		if ($iId=$this->oDb->query($sql,$oTemplate->getTplTitle(),$oTemplate->getTplName(),$oTemplate->getTplDateAdd(),$oTemplate->getTplDescription(),$oTemplate->getTplCategory(),$oTemplate->getTplPrice(),$oTemplate->getTplAvatar()))
		{
			$oTemplate->setTplId($iId);
			return $iId;
		}
		return false;
    }

    public function UpdateTemplate(PluginAceadminpanel_ModuleTemplates_EntityTemplates $oTemplate) {
       $sql = "UPDATE ".Config::Get('db.table.templates')."
			SET
				tpl_name= ?,
				tpl_title= ?,
                                tpl_date_edit=?,
                                tpl_description =?,
				tpl_category= ?,
				tpl_price = ?d,
                                tpl_avatar= ?
			WHERE
				tpl_id = ?d
		";
		if ($this->oDb->query($sql,$oTemplate->getTplName(),$oTemplate->getTplTitle(),$oTemplate->getTplDateEdit(),$oTemplate->getTplDescription(),$oTemplate->getTplCategory(),$oTemplate->getTplPrice(),$oTemplate->getTplAvatar(),$oTemplate->getTplId())) {
			return true;
		}
		return false;
    }

     public function GetTemplates($aFilter) {

        $sql = 'SELECT
                        *
                FROM
                        '.Config::Get('db.table.templates').'
                WHERE
                    1=1
                    { AND tpl_category = ? }
                ORDER BY tpl_id';
        $aTemplates=array();
        if ($aRows=$this->oDb->select($sql,isset($aFilter['category'])?$aFilter['category']:DBSIMPLE_SKIP)) {
                foreach ($aRows as $oTemplate) {
                        $aTemplates[]=Engine::GetEntity('PluginAceadminpanel_ModuleTemplates_EntityTemplates',$oTemplate);
                }
        }
        return $aTemplates;
    }

    public function GetFavTemplates($aFilter) {
        $sFilter=$this->paramFilter($aFilter);
        $sql = 'SELECT
                        *
                FROM
                        '.Config::Get('db.table.tplfav').' as tf
                        LEFT JOIN (
                                SELECT
                                        *
                                FROM '.Config::Get('db.table.templates').'
                        ) AS tpl ON tpl.tpl_id = tf.tpl_id
                WHERE
                    1=1
                    '.$sFilter.'
                ORDER BY tf.tpl_id';
        $aTemplates=array();
        if ($aRows=$this->oDb->select($sql)) {
                foreach ($aRows as $oTemplate) {
                        $aTemplates[]=Engine::GetEntity('PluginAceadminpanel_ModuleTemplates_EntityTemplates',$oTemplate);
                }
        }
        return $aTemplates;
    }

    public function paramFilter($aFilter) {
        $sWhere='';
		if (isset($aFilter['type'])) {
			$sWhere.=" AND tf.tpl_type =  '".$aFilter['type']."'";
		}
		
		if (isset($aFilter['user_id'])) {
			$sWhere.=" AND tf.target_id =  '".(int)$aFilter['user_id']."'";
		}


		return $sWhere;
    }

    public function GetTplById($sId) {
        $sql = 'SELECT
                        *
                FROM
                        '.Config::Get('db.table.templates').'
                WHERE
                    tpl_id = ?d
                ';
        if ($aRow=$this->oDb->selectRow($sql,$sId)) {
                return new PluginAceadminpanel_ModuleTemplates_EntityTemplates($aRow);
        }
        return false;
    }

     public function SetTemplate($sType,$iTarget,$iTpl) {
        $sql = 'INSERT INTO '.Config::Get('db.table.tplusers').'
			(tpl_type,
			target_id,
			tpl_id
			)
			VALUES(?,  ?d,	?d)
		';
		if ($this->oDb->query($sql,$sType,$iTarget,$iTpl))
		{
			return true;
		}
		return false;
     }

     public function DeleteTemplate($sType,$iTarget,$iTpl) {
		$sql = "DELETE FROM ".Config::Get('db.table.tplusers')."
			WHERE
				tpl_type = ?
                                AND
                                target_id = ?d
                                AND
                                tpl_id = ?d
		";
		if ($this->oDb->query($sql,$sType,$iTarget,$iTpl)) {
			return true;
		}
		return false;
    }

    public function AdminDeleteTemplate($iTpl) {

                $sql1 = "DELETE FROM ".Config::Get('db.table.templates')."
			WHERE
                        tpl_id = ?d
		";
                $sql2 = "DELETE FROM ".Config::Get('db.table.tplusers')."
			WHERE
                        tpl_id = ?d
		";
                $sql3 = "DELETE FROM ".Config::Get('db.table.tplfav')."
			WHERE
                        tpl_id = ?d
		";
		if ($this->oDb->query($sql1,$iTpl) && $this->oDb->query($sql2,$iTpl) && $this->oDb->query($sql3,$iTpl)) {
			return true;
		}
		return false;
    }

      public function SetFavTemplate($sType,$iTarget,$iTpl) {
        $sql = 'INSERT INTO '.Config::Get('db.table.tplfav').'
			(tpl_type,
			target_id,
			tpl_id
			)
			VALUES(?,  ?d,	?d)
		';
		if ($this->oDb->query($sql,$sType,$iTarget,$iTpl))
		{
			return true;
		}
		return false;
     }

     public function DeleteFavTemplate($sType,$iTarget,$iTpl) {
		$sql = "DELETE FROM ".Config::Get('db.table.tplfav')."
			WHERE
				tpl_type = ?
                                AND
                                target_id = ?d
                                AND
                                tpl_id = ?d
		";
		if ($this->oDb->query($sql,$sType,$iTarget,$iTpl)) {
			return true;
		}
		return false;
	}

     public function GetTplActiveByTargetTypeAndTargetId($sId,$sType) {
        $sql = 'SELECT
                        *
                FROM
                        '.Config::Get('db.table.tplusers').'
                WHERE
                    target_id = ?d
                    AND
                    tpl_type = ?
                ';
        if ($aRow=$this->oDb->selectRow($sql,$sId,$sType)) {
                return new PluginAceadminpanel_ModuleTemplates_EntityTemplates($aRow);
        }
        return false;
    }

    public function GetFavByIdAndUserId($sType,$iTarget,$iTpl) {
        $sql = 'SELECT
                        *
                FROM
                        '.Config::Get('db.table.tplfav').'
                WHERE
                    tpl_type=?
                    AND
                    target_id = ?d
                    AND
                    tpl_id = ?
                ';
        if ($aRow=$this->oDb->selectRow($sql,$sType,$iTarget,$iTpl)) {
                return new PluginAceadminpanel_ModuleTemplates_EntityTemplates($aRow);
        }
        return false;
    }

    public function GetCountUsers($iTplId) {
        $sql = "SELECT
                count(tpl_id) as count
        FROM
                ".Config::Get('db.table.tplusers')."
        WHERE
                tpl_id = ?d;";
        if ($aRow=$this->oDb->selectRow($sql,$iTplId)) {
                return $aRow['count'];
        }
        return '0';
    }

    
}

// EOF