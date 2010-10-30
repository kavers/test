<?php
/*
 * Vladimir Yuriev
 * vladimir.o.yuriev@gmail.com
*/

class PluginAceadminpanel_ModuleWidgets_MapperWidgets extends Mapper {
    
    public function AddWidget(PluginAceadminpanel_ModuleWidgets_EntityWidgets $oWidget) {
        $sql = 'INSERT INTO '.Config::Get('db.table.widgets').'
			(wid_title,
			wid_name,
			wid_date_add,
                        wid_description,
			wid_category,
			wid_price,
                        wid_avatar
			)
			VALUES(?,  ?,	?,?,	?,?d,?)
		';
		if ($iId=$this->oDb->query($sql,$oWidget->getWidTitle(),$oWidget->getWidName(),$oWidget->getWidDateAdd(),$oWidget->getWidDescription(),$oWidget->getWidCategory(),$oWidget->getWidPrice(),$oWidget->getWidAvatar()))
		{
			$oWidget->setWidId($iId);
			return $iId;
		}
		return false;
    }

    public function UpdateWidget(PluginAceadminpanel_ModuleWidgets_EntityWidgets $oWidget) {
       $sql = "UPDATE ".Config::Get('db.table.widgets')."
			SET
				wid_name= ?,
				wid_title= ?,
                                wid_date_edit=?,
                                wid_description =?,
				wid_category= ?,
				wid_price = ?d,
                                wid_avatar= ?
			WHERE
				wid_id = ?d
		";
		if ($this->oDb->query($sql,$oWidget->getWidName(),$oWidget->getWidTitle(),$oWidget->getWidDateEdit(),$oWidget->getWidDescription(),$oWidget->getWidCategory(),$oWidget->getWidPrice(),$oWidget->getWidAvatar(),$oWidget->getWidId())) {
			return true;
		}
		return false;
    }

     public function GetWidgets($aFilter) {

        $sql = 'SELECT
                        *
                FROM
                        '.Config::Get('db.table.widgets').'
                WHERE
                    1=1
                    { AND wid_category = ? }
                    { AND wid_name = ? }
                ORDER BY wid_id';
        $aWidgets=array();
        if ($aRows=$this->oDb->select($sql,
                                      isset($aFilter['category'])?$aFilter['category']:DBSIMPLE_SKIP,
                                      isset($aFilter['name'])?$aFilter['name']:DBSIMPLE_SKIP
                )) {
                foreach ($aRows as $oWidget) {
                        $aWidgets[]=Engine::GetEntity('PluginAceadminpanel_ModuleWidgets_EntityWidgets',$oWidget);
                }
        }
        return $aWidgets;
    }

    public function GetFavWidgets($aFilter) {
        $sFilter=$this->paramFilter($aFilter);
        $sql = 'SELECT
                        *
                FROM
                        '.Config::Get('db.table.widfav').' as tf
                        LEFT JOIN (
                                SELECT
                                        *
                                FROM '.Config::Get('db.table.widgets').'
                        ) AS tpl ON tpl.wid_id = tf.wid_id
                WHERE
                    1=1
                    '.$sFilter.'
                ORDER BY tf.wid_priority asc';
        $aWidgets=array();
        if ($aRows=$this->oDb->select($sql)) {
                foreach ($aRows as $oWidget) {
                        $aWidgets[]=Engine::GetEntity('PluginAceadminpanel_ModuleWidgets_EntityWidgets',$oWidget);
                }
        }
        return $aWidgets;
    }

    public function paramFilter($aFilter) {
        $sWhere='';
		if (isset($aFilter['type'])) {
			$sWhere.=" AND tf.wid_type =  '".$aFilter['type']."'";
		}
		
		if (isset($aFilter['user_id'])) {
			$sWhere.=" AND tf.target_id =  '".(int)$aFilter['user_id']."'";
		}

                if (isset($aFilter['wid_id'])) {
			$sWhere.=" AND tf.wid_id =  '".(int)$aFilter['wid_id']."'";
		}


		return $sWhere;
    }

    public function GetWidById($sId) {
        $sql = 'SELECT
                        *
                FROM
                        '.Config::Get('db.table.widgets').'
                WHERE
                    wid_id = ?d
                ';
        if ($aRow=$this->oDb->selectRow($sql,$sId)) {
                return new PluginAceadminpanel_ModuleWidgets_EntityWidgets($aRow);
        }
        return false;
    }

     public function SetWidget($sType,$iTarget,$iWid) {
        $sql = 'INSERT INTO '.Config::Get('db.table.widusers').'
			(wid_type,
			target_id,
			wid_id,
                        wid_date_add
			)
			VALUES(?,  ?d,	?d,?)
		';
		if ($this->oDb->query($sql,$sType,$iTarget,$iWid,date("Y-m-d H:i:s")))
		{
			return true;
		}
		return false;
     }

     public function DeleteWidget($sType,$iTarget,$iWid) {
		$sql = "DELETE FROM ".Config::Get('db.table.widusers')."
			WHERE
				wid_type = ?
                                AND
                                target_id = ?d
                                AND
                                wid_id = ?d
		";
		if ($this->oDb->query($sql,$sType,$iTarget,$iWid)) {
			return true;
		}
		return false;
    }

    public function AdminDeleteWidget($iWid) {

                $sql1 = "DELETE FROM ".Config::Get('db.table.widgets')."
			WHERE
                        wid_id = ?d
		";
                $sql2 = "DELETE FROM ".Config::Get('db.table.widusers')."
			WHERE
                        wid_id = ?d
		";
                $sql3 = "DELETE FROM ".Config::Get('db.table.widfav')."
			WHERE
                        wid_id = ?d
		";
		if ($this->oDb->query($sql1,$iWid) && $this->oDb->query($sql2,$iWid) && $this->oDb->query($sql3,$iWid)) {
			return true;
		}
		return false;
    }

      public function SetFavWidget($sType,$iTarget,$iWid,$iPriority) {
        $sql = 'INSERT INTO '.Config::Get('db.table.widfav').'
			(wid_type,
			target_id,
			wid_id,
                        wid_priority
			)
			VALUES(?,  ?d,	?d,?d)
		';
		if ($this->oDb->query($sql,$sType,$iTarget,$iWid,$iPriority))
		{
			return true;
		}
		return false;
     }

     public function DeleteFavWidget($sType,$iTarget,$iWid) {
		$sql = "DELETE FROM ".Config::Get('db.table.widfav')."
			WHERE
				wid_type = ?
                                AND
                                target_id = ?d
                                AND
                                wid_id = ?d
		";
		if ($this->oDb->query($sql,$sType,$iTarget,$iWid)) {
                        $this->DeleteWidget($sType,$iTarget,$iWid);
			return true;
		}
		return false;
	}

     public function GetWidActiveByTargetTypeAndTargetId($sId,$sType,$widId) {
        $sql = 'SELECT
                        *
                FROM
                        '.Config::Get('db.table.widusers').'
                WHERE
                    target_id = ?d
                    AND
                    wid_type = ?
                    AND
                    wid_id = ?
                ';
        if ($aRow=$this->oDb->selectRow($sql,$sId,$sType,$widId)) {
                return new PluginAceadminpanel_ModuleWidgets_EntityWidgets($aRow);
        }
        return false;
    }

    public function GetWidgetsActive($sId,$sType) {
        $sql = 'SELECT
                        *
                FROM
                        '.Config::Get('db.table.widusers').' as tf
                        LEFT JOIN (
                                SELECT
                                        *
                                FROM '.Config::Get('db.table.widgets').'
                        ) AS tpl ON tpl.wid_id = tf.wid_id
                WHERE
                    tf.target_id = ?d
                    AND
                    tf.wid_type = ?
                ORDER by tpl.wid_description asc
                ';
        $aWidgets=array();
        if ($aRows=$this->oDb->select($sql,$sId,$sType)) {
                foreach ($aRows as $oWidget) {
                        $aWidgets[]=Engine::GetEntity('PluginAceadminpanel_ModuleWidgets_EntityWidgets',$oWidget);
                }
        }
        return $aWidgets;
    }


    public function GetFavByIdAndUserId($sType,$iTarget,$iWid) {
        $sql = 'SELECT
                        *
                FROM
                        '.Config::Get('db.table.widfav').'
                WHERE
                    wid_type=?
                    AND
                    target_id = ?d
                    AND
                    wid_id = ?
                ';
        if ($aRow=$this->oDb->selectRow($sql,$sType,$iTarget,$iWid)) {
                return new PluginAceadminpanel_ModuleWidgets_EntityWidgets($aRow);
        }
        return false;
    }

    public function GetCountUsers($iWidId) {
        $sql = "SELECT
                count(wid_id) as count
        FROM
                ".Config::Get('db.table.widusers')."
        WHERE
                wid_id = ?d;";
        if ($aRow=$this->oDb->selectRow($sql,$iWidId)) {
                return $aRow['count'];
        }
        return '0';
    }


    public function GetNextWidBySort($iSort,$aFilter,$sWay) {
          $sFilter=$this->paramFilter($aFilter);
		if ($sWay=='up') {
			$sWay='>';
			$sOrder='asc';
		} else {
			$sWay='<';
			$sOrder='desc';
		}
		$sql = "SELECT
                        *
                        FROM ".Config::Get('db.table.widfav')." as tf
                        WHERE 
                        1=1
                        ".$sFilter."
                        AND tf.wid_priority {$sWay} ?
                        order by tf.wid_priority {$sOrder} limit 0,1";
		if ($aRow=$this->oDb->selectRow($sql,$iSort)) {
			return new PluginAceadminpanel_ModuleWidgets_EntityWidgets($aRow);
		}
		return null;
	}

         public function UpdateFavWid(PluginAceadminpanel_ModuleWidgets_EntityWidgets $oWidget,$aFilter){
               $sql = "UPDATE ".Config::Get('db.table.widfav')."
                                SET
                                        wid_priority = ?d
                                WHERE
                                        wid_id = ?d
                                        AND
                                        wid_type = ?
                                        AND
                                        target_id =?d
                        ";
                        if ($this->oDb->query($sql,$oWidget->getWidPriority(),$oWidget->getWidId(),$aFilter['type'],$aFilter['user_id'])) {
                                return true;
                        }
                        return false;
        }

        public function GetMaxSortByPid($aFilter) {
		$sFilter=$this->paramFilter($aFilter);
                $sql = "SELECT max(wid_priority) as max_sort
                        FROM ".Config::Get('db.table.widfav')." as tf
                        WHERE
                        1=1
                        ".$sFilter."
                        ";
		if ($aRow=$this->oDb->selectRow($sql)) {
			return $aRow['max_sort'];
		}
		return 0;
	}


    public function GetWidsByProducerName($pId) {
        $sql = 'SELECT
                        *
                FROM
                        '.Config::Get('db.table.widgets').'
                WHERE
                    wid_name=?
                ORDER BY wid_id asc';
        $aWidgets=array();
        if ($aRows=$this->oDb->select($sql,$pId)) {
                foreach ($aRows as $oWidget) {
                        $aWidgets[]=Engine::GetEntity('PluginAceadminpanel_ModuleWidgets_EntityWidgets',$oWidget);
                }
        }
        return $aWidgets;
    }

   /* public function GetWidgetsProducers() {
        $sql = 'SELECT
                        *
                FROM
                        '.Config::Get('db.table.widproducers').'
                ';
        if ($aRows=$this->oDb->select($sql)) {
                return $aRows;
        }
        return array();
    }

    public function AddWidgetProducer($sName) {
        $hash=func_encrypt(date("Y-m-d H:i:s"));
        $sql = 'INSERT INTO '.Config::Get('db.table.widproducers').'
			(pro_name,
			pro_hash
			)
			VALUES(?,  ?)
		';
                if ($iId=$this->oDb->query($sql,$sName,$hash))
		{
			return $hash;
		}
		return $hash;
    }

    public function GetWidgetProducerByName($sName) {
        $sql = 'SELECT
                        *
                FROM
                        '.Config::Get('db.table.widproducers').'
                WHERE
                    pro_name=?';
        if ($aRow=$this->oDb->selectRow($sql,$sName)) {
                return $aRow['pro_hash'];
        }
        return false;
    }*/
    
}

// EOF