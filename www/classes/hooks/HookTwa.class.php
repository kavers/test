<?php
/*
 * hook for Templates widgets and addons
 */
class HookTwa extends Hook {
	public function RegisterHook() {
		$this->AddHook('topic_show','TPShow',__CLASS__,99);
                $this->AddHook('blog_collective_show','TCShow',__CLASS__,96);
                $this->AddHook('topic_show','WidgetTPSet',__CLASS__,98);
                $this->AddHook('blog_collective_show','WidgetTCSet',__CLASS__,95);
                $this->AddHook('init_action','InitTpl',__CLASS__,93);
                $this->AddHook('topic_show','DecTPSet',__CLASS__,97);
                $this->AddHook('blog_collective_show','DecTCSet',__CLASS__,90);
                $this->AddHook('init_action','InitDec',__CLASS__,91);
	}

	public function TPShow($aVars) {
		$oTopic=$aVars['oTopic'];
                $oBlog=$this->Blog_GetBlogById($oTopic->getBlogId());
                if ($oBlog->getType()=='personal') {
                    if ($oTpl=$this->PluginAceadminpanel_Templates_GetTplActiveByTargetTypeAndTargetId($oBlog->getOwnerId(),'personal')) {

                   $oTplActiveCss=$this->PluginAceadminpanel_Templates_GetTplById($oTpl->getTplId());
                    $this->Viewer_Assign('oTplActiveCss',$oTplActiveCss);
                    $this->Viewer_Assign('oTplActiveAddress',Config::Get('path.static.skin').'/css/tpls/'.$oTplActiveCss->getTplName().'.css');
                    }
                }
                
	}

        public function TCShow($aVars) {
		$oBlog=$aVars['oBlog'];
                if($oTpl=$this->PluginAceadminpanel_Templates_GetTplActiveByTargetTypeAndTargetId($oBlog->getId(),'collective')){
                    $oTplActiveCss=$this->PluginAceadminpanel_Templates_GetTplById($oTpl->getTplId());
                    $this->Viewer_Assign('oTplActiveCss',$oTplActiveCss);
                    $this->Viewer_Assign('oTplActiveAddress',Config::Get('path.static.skin').'/css/tpls/'.$oTplActiveCss->getTplName().'.css');

                }
	}

        public function WidgetTPSet($aVars) {
		$oTopic=$aVars['oTopic'];
                $oBlog=$this->Blog_GetBlogById($oTopic->getBlogId());

                if ($oBlog->getType()=='personal') {
                    $sTargetId=$oBlog->getOwnerId();
                    $sTargetType='personal';
                } elseif ($oBlog->getType()=='open' || $oBlog->getType()=='close') {
                    $sTargetId=$oBlog->getId();
                    $sTargetType='collective';
                }
                
                if ($aWidgets=$this->PluginAceadminpanel_Widgets_GetWidgetsActive($sTargetId,$sTargetType)) {
                    foreach ($aWidgets as $oWidget) {
                        $this->Viewer_AddBlock('right','WidgetSet',array("oWidget"=>$oWidget),$oWidget->getWidPriority());
                    }
                }
	}

        public function WidgetTCSet($aVars) {
		$oBlog=$aVars['oBlog'];
                if ($aWidgets=$this->PluginAceadminpanel_Widgets_GetWidgetsActive($oBlog->getId(),'collective')) {
                    foreach ($aWidgets as $oWidget) {
                        $this->Viewer_AddBlock('right','WidgetSet',array("oWidget"=>$oWidget),$oWidget->getWidPriority());
                    }
                }
	}

         public function InitTpl() {
             if(getRequest('settpl') && func_check(getRequest('settpl'),'id') && $oTplActiveCss=$this->PluginAceadminpanel_Templates_GetTplById(getRequest('settpl'))) {
                $this->Viewer_Assign('oTplSetCss',$oTplActiveCss);
                $this->Viewer_Assign('oTplSetAddress',Config::Get('path.static.skin').'/css/tpls/'.$oTplActiveCss->getTplName().'.css');
             }
         }

         public function DecTPSet($aVars) {
		$oTopic=$aVars['oTopic'];
                $oBlog=$this->Blog_GetBlogById($oTopic->getBlogId());
                if ($oBlog->getType()=='personal') {
                    if ($oDec=$this->PluginAceadminpanel_Decor_GetDecActiveByTargetTypeAndTargetId($oBlog->getOwnerId(),'personal')) {

                        $oDecActive=$this->PluginAceadminpanel_Decor_GetDecById($oDec->getDecId());
                        $aDecors[$oDecActive->getDecPosition()]=$oDecActive;
                        $this->Viewer_Assign('aDecors',$aDecors);
                    }
                }

	}

        public function DecTCSet($aVars) {
		$oBlog=$aVars['oBlog'];
                if($oDec=$this->PluginAceadminpanel_Decor_GetDecActiveByTargetTypeAndTargetId($oBlog->getId(),'collective')){
                    $oDecActive=$this->PluginAceadminpanel_Decor_GetDecById($oDec->getDecId());
                    $aDecors[$oDecActive->getDecPosition()]=$oDecActive;
                    $this->Viewer_Assign('aDecors',$aDecors);
                
                }
	}

        public function InitDec() {
             if(getRequest('setDec') && func_check(getRequest('setDec'),'id') && $oDecActive=$this->PluginAceadminpanel_Decor_GetDecById(getRequest('setDec'))) {
                $aDecors[$oDecActive->getDecPosition()]=$oDecActive;
                $this->Viewer_Assign('aDecors',$aDecors);
             }
        }

}
?>