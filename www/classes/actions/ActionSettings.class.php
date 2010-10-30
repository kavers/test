<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright © 2008 Mzhelskiy Maxim
*
*--------------------------------------------------------
*
*   Official site: www.livestreet.ru
*   Contact e-mail: rus.engine@gmail.com
*
*   GNU General Public License, version 2:
*   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
---------------------------------------------------------
*/

/**
 * Обрабатывает настройки профила юзера
 *
 */
class ActionSettings extends Action {
	/**
	 * Какое меню активно
	 *
	 * @var unknown_type
	 */
	protected $sMenuItemSelect='settings';
	/**
	 * Какое подменю активно
	 *
	 * @var unknown_type
	 */
	protected $sMenuSubItemSelect='profile';
	/**
	 * Текущий юзер
	 *
	 * @var unknown_type
	 */
	protected $oUserCurrent=null;
	
	/**
	 * Инициализация 
	 *
	 * @return unknown
	 */
	public function Init() {
		/**
		 * Проверяем авторизован ли юзер
		 */
		if (!$this->User_IsAuthorization()) {
			$this->Message_AddErrorSingle($this->Lang_Get('not_access'),$this->Lang_Get('error'));
			return Router::Action('error'); 
		}
		/**
		 * Получаем текущего юзера
		 */
		$this->oUserCurrent=$this->User_GetUserCurrent();
		$this->SetDefaultEvent('profile');	
		$this->Viewer_AddHtmlTitle($this->Lang_Get('settings_menu'));
	}
	
	protected function RegisterEvent() {		
		$this->AddEvent('profile','EventProfile');		
		$this->AddEvent('invite','EventInvite');	
		$this->AddEvent('tuning','EventTuning');

                $this->AddEvent('template','EventTemplate');
                $this->AddEvent('widgets','EventWidgets');
                $this->AddEvent('decor','EventDecor');
	}
		
	
	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */

	protected function EventTemplate() {
		$this->sMenuItemSelect='settings';
		$this->sMenuSubItemSelect='template';

                $this->Viewer_AddHtmlTitle($this->Lang_Get('adm_choose_template'));

                $oTplActive=$this->PluginAceadminpanel_Templates_GetTplActiveByTargetTypeAndTargetId($this->oUserCurrent->getId(),'personal');



                if (getRequest('choose') && $oTpl=$this->PluginAceAdminPanel_Templates_GetTplById(getRequest('choose'))) {

                    if ($oTplActive) {
                        $this->PluginAceadminpanel_Templates_DeleteTemplate('personal',$this->oUserCurrent->getId(),$oTplActive->getTplId());
                    }
                    $this->PluginAceadminpanel_Templates_SetTemplate('personal',$this->oUserCurrent->getId(),getRequest('choose'));
                    $this->Message_AddNoticeSingle('шаблон выбран');
                }




                if (getRequest('delete') && $oTpl=$this->PluginAceAdminPanel_Templates_GetTplById(getRequest('delete')) && $oTplActive && $oTplActive->getTplId()==getRequest('delete')) {

                    $this->PluginAceadminpanel_Templates_DeleteTemplate('personal',$this->oUserCurrent->getId(),getRequest('delete'));
                    $this->Message_AddNoticeSingle('шаблон удален');
                }

                if (getRequest('setfav') && $oTpl=$this->PluginAceAdminPanel_Templates_GetTplById(getRequest('setfav')) && !$this->PluginAceAdminPanel_Templates_GetFavByIdAndUserId('personal',$this->oUserCurrent->getId(),getRequest('setfav'))) {

                    $this->PluginAceadminpanel_Templates_SetFavTemplate('personal',$this->oUserCurrent->getId(),getRequest('setfav'));
                    $this->Message_AddNoticeSingle('шаблон добавлен в избранное');
                }




                if (getRequest('delfav') && $oTpl=$this->PluginAceAdminPanel_Templates_GetTplById(getRequest('delfav')) && $this->PluginAceAdminPanel_Templates_GetFavByIdAndUserId('personal',$this->oUserCurrent->getId(),getRequest('delfav'))) {

                    $this->PluginAceadminpanel_Templates_DeleteFavTemplate('personal',$this->oUserCurrent->getId(),getRequest('delfav'));
                    $this->Message_AddNoticeSingle('шаблон удален из избранного');
                }


               $aFilter=array();
                if($this->GetParam(0)=='all') {
                    if (getRequest('category') && in_array(getRequest('category'),Config::Get('plugin.aceadminpanel.tplcats'))) {
                        $aFilter['category']=getRequest('category');
                        $this->Viewer_Assign('aLangCategory', $this->Lang_Get('tpl_category_'.getRequest('category')));
                    }
                    $aTemplates=$this->PluginAceadminpanel_Templates_GetTemplates($aFilter);
                    $this->SetTemplateAction('templatesall');

                    $this->Viewer_AddBlock('right','block.tplcats.tpl',array(),153);
                } else {
                    $aFilter['type']='personal';
                    $aFilter['user_id']=$this->oUserCurrent->getId();
                    $aTemplates=$this->PluginAceadminpanel_Templates_GetFavTemplates($aFilter);
                }

                $this->Viewer_Assign('aTemplates', $aTemplates);


                $oTplActiveNew=$this->PluginAceadminpanel_Templates_GetTplActiveByTargetTypeAndTargetId($this->oUserCurrent->getId(),'personal');
                $this->Viewer_Assign('oTplActive', $oTplActiveNew);

                $this->Viewer_AddBlock('right','block.settings.tpl',array(),157);


        }


        protected function EventWidgets() {
		$this->sMenuItemSelect='settings';
		$this->sMenuSubItemSelect='widgets';

                $this->Viewer_AddHtmlTitle($this->Lang_Get('adm_choose_widget'));

                //$oWidActive=$this->PluginAceadminpanel_Widgets_GetWidActiveByTargetTypeAndTargetId($this->oUserCurrent->getId(),'personal');


                if (getRequest('choose') && $oWid=$this->PluginAceAdminPanel_Widgets_GetWidById(getRequest('choose')) && !$this->PluginAceadminpanel_Widgets_GetWidActiveByTargetTypeAndTargetId($this->oUserCurrent->getId(),'personal',getRequest('choose'))) {

                    /*if ($oWidActive) {
                        $this->PluginAceadminpanel_Widgets_DeleteWidget('personal',$this->oUserCurrent->getId(),$oWidActive->getWidId());
                    }*/
                    $this->PluginAceadminpanel_Widgets_SetWidget('personal',$this->oUserCurrent->getId(),getRequest('choose'));
                    $this->Message_AddNoticeSingle('виджет выбран');
                }




                if (getRequest('delete') && $oWid=$this->PluginAceAdminPanel_Widgets_GetWidById(getRequest('delete')) /*&& $oWidActive && $oWidActive->getWidId()==getRequest('delete')*/) {

                    $this->PluginAceadminpanel_Widgets_DeleteWidget('personal',$this->oUserCurrent->getId(),getRequest('delete'));
                    $this->Message_AddNoticeSingle('виджет удален');
                }

                if (getRequest('setfav') && $oWid=$this->PluginAceAdminPanel_Widgets_GetWidById(getRequest('setfav')) && !$this->PluginAceAdminPanel_Widgets_GetFavByIdAndUserId('personal',$this->oUserCurrent->getId(),getRequest('setfav'))) {
                    $aFilter['type']='personal';
                    $aFilter['user_id']=$this->oUserCurrent->getId();
                    $iPriority=$this->PluginAceadminpanel_Widgets_GetMaxSortByPid($aFilter);
                    $iPriority++;
                    $this->PluginAceadminpanel_Widgets_SetFavWidget('personal',$this->oUserCurrent->getId(),getRequest('setfav'),$iPriority);
                    $this->Message_AddNoticeSingle('виджет добавлен в избранное');
                }




                if (getRequest('delfav') && $oWid=$this->PluginAceAdminPanel_Widgets_GetWidById(getRequest('delfav')) && $this->PluginAceAdminPanel_Widgets_GetFavByIdAndUserId('personal',$this->oUserCurrent->getId(),getRequest('delfav'))) {

                    $this->PluginAceadminpanel_Widgets_DeleteFavWidget('personal',$this->oUserCurrent->getId(),getRequest('delfav'));
                    $this->Message_AddNoticeSingle('виджет удален из избранного');
                }

                 /**
		 * Обработка изменения сортировки страницы
		 */
		if (getRequest('sort') and $oWidget=$this->PluginAceadminpanel_Widgets_GetFavWidgets(array('type'=>'personal','user_id'=>$this->oUserCurrent->getId(),'wid_id'=>getRequest('sort')))) {
			//$this->Security_ValidateSendForm();
			$oWidget=$oWidget[0];
                        $sWay=getRequest('sorttype')=='down' ? 'down' : 'up';
			$iSortOld=$oWidget->getWidPriority();

                        $aFilter['type']='personal';
                        $aFilter['user_id']=$this->oUserCurrent->getId();

			if ($oWidPrev=$this->PluginAceadminpanel_Widgets_GetNextWidBySort($iSortOld,$aFilter,$sWay)) {
				$iSortNew=$oWidPrev->getWidPriority();
				$oWidPrev->setWidPriority($iSortOld);
				$this->PluginAceadminpanel_Widgets_UpdateFavWid($oWidPrev,$aFilter);
			} else {
				$iSortNew=$iSortOld;
			}
			/**
			 * Меняем значения сортировки местами
			 */
			$oWidget->setWidPriority($iSortNew);
			$this->PluginAceadminpanel_Widgets_UpdateFavWid($oWidget,$aFilter);
		}


               $aFilter=array();
                if($this->GetParam(0)=='all') {
                    if (getRequest('category') && in_array(getRequest('category'),Config::Get('plugin.aceadminpanel.tplcats'))) {
                        $aFilter['category']=getRequest('category');
                        $this->Viewer_Assign('aLangCategory', $this->Lang_Get('tpl_category_'.getRequest('category')));
                    }
                    $aWidgets=$this->PluginAceadminpanel_Widgets_GetWidgets($aFilter);
                    $this->SetTemplateAction('widgetsall');

                    $this->Viewer_AddBlock('right','block.widcats.tpl',array(),159);
                } else {
                    $aFilter['type']='personal';
                    $aFilter['user_id']=$this->oUserCurrent->getId();
                    $aWidgets=$this->PluginAceadminpanel_Widgets_GetFavWidgets($aFilter);
                }



                $this->Viewer_Assign('aWidgets', $aWidgets);
/*

                $oWidActiveNew=$this->PluginAceadminpanel_Widgets_GetWidActiveByTargetTypeAndTargetId($this->oUserCurrent->getId(),'personal');
                $this->Viewer_Assign('oWidActive', $oWidActiveNew);*/

                $this->Viewer_AddBlock('right','block.settings.tpl',array(),157);


        }

        protected function EventDecor() {
		$this->sMenuItemSelect='settings';
		$this->sMenuSubItemSelect='decor';

                $this->Viewer_AddHtmlTitle($this->Lang_Get('adm_choose_decor'));

                $oDecActive=$this->PluginAceadminpanel_Decor_GetDecActiveByTargetTypeAndTargetId($this->oUserCurrent->getId(),'personal');



                if (getRequest('choose') && $oDec=$this->PluginAceAdminPanel_Decor_GetDecById(getRequest('choose'))) {

                    if ($oDecActive) {
                        $this->PluginAceadminpanel_Decor_DeleteDecor('personal',$this->oUserCurrent->getId(),$oDecActive->getDecId());
                    }
                    $this->PluginAceadminpanel_Decor_SetDecor('personal',$this->oUserCurrent->getId(),getRequest('choose'));
                    $this->Message_AddNoticeSingle('шаблон выбран');
                }




                if (getRequest('delete') && $oDec=$this->PluginAceAdminPanel_Decor_GetDecById(getRequest('delete')) && $oDecActive && $oDecActive->getDecId()==getRequest('delete')) {

                    $this->PluginAceadminpanel_Decor_DeleteDecor('personal',$this->oUserCurrent->getId(),getRequest('delete'));
                    $this->Message_AddNoticeSingle('шаблон удален');
                }

                if (getRequest('setfav') && $oDec=$this->PluginAceAdminPanel_Decor_GetDecById(getRequest('setfav')) && !$this->PluginAceAdminPanel_Decor_GetFavByIdAndUserId('personal',$this->oUserCurrent->getId(),getRequest('setfav'))) {

                    $this->PluginAceadminpanel_Decor_SetFavDecor('personal',$this->oUserCurrent->getId(),getRequest('setfav'));
                    $this->Message_AddNoticeSingle('шаблон добавлен в избранное');
                }




                if (getRequest('delfav') && $oDec=$this->PluginAceAdminPanel_Decor_GetDecById(getRequest('delfav')) && $this->PluginAceAdminPanel_Decor_GetFavByIdAndUserId('personal',$this->oUserCurrent->getId(),getRequest('delfav'))) {

                    $this->PluginAceadminpanel_Decor_DeleteFavDecor('personal',$this->oUserCurrent->getId(),getRequest('delfav'));
                    $this->Message_AddNoticeSingle('шаблон удален из избранного');
                }


               $aFilter=array();
                if($this->GetParam(0)=='all') {
                    if (getRequest('category') && in_array(getRequest('category'),Config::Get('plugin.aceadminpanel.deccats'))) {
                        $aFilter['category']=getRequest('category');
                        $this->Viewer_Assign('aLangCategory', $this->Lang_Get('dec_category_'.getRequest('category')));
                    }
                    $aDecors=$this->PluginAceadminpanel_Decor_GetDecors($aFilter);
                    $this->SetTemplateAction('decorall');

                    $this->Viewer_AddBlock('right','block.deccats.tpl',array(),159);
                } else {
                    $aFilter['type']='personal';
                    $aFilter['user_id']=$this->oUserCurrent->getId();
                    $aDecors=$this->PluginAceadminpanel_Decor_GetFavDecors($aFilter);
                }

                $this->Viewer_Assign('aDecors', $aDecors);


                $oDecActiveNew=$this->PluginAceadminpanel_Decor_GetDecActiveByTargetTypeAndTargetId($this->oUserCurrent->getId(),'personal');
                $this->Viewer_Assign('oDecActive', $oDecActiveNew);

                $this->Viewer_AddBlock('right','block.settings.tpl',array(),157);

        }
	
	protected function EventTuning() {
		$this->sMenuItemSelect='settings';
		$this->sMenuSubItemSelect='tuning';
		
		$this->Viewer_AddHtmlTitle($this->Lang_Get('settings_menu_tuning'));
		
		if (isPost('submit_settings_tuning')) {
			$this->Security_ValidateSendForm();			
			
			$this->oUserCurrent->setSettingsNoticeNewTopic( getRequest('settings_notice_new_topic') ? 1 : 0 );
			$this->oUserCurrent->setSettingsNoticeNewComment( getRequest('settings_notice_new_comment') ? 1 : 0 );
			$this->oUserCurrent->setSettingsNoticeNewTalk( getRequest('settings_notice_new_talk') ? 1 : 0 );
			$this->oUserCurrent->setSettingsNoticeReplyComment( getRequest('settings_notice_reply_comment') ? 1 : 0 );
			$this->oUserCurrent->setSettingsNoticeNewFriend( getRequest('settings_notice_new_friend') ? 1 : 0 );
			$this->oUserCurrent->setProfileDate(date("Y-m-d H:i:s"));
			if ($this->User_Update($this->oUserCurrent)) {
				$this->Message_AddNoticeSingle($this->Lang_Get('settings_tuning_submit_ok'));
			} else {
				$this->Message_AddErrorSingle($this->Lang_Get('system_error'));
			}
		}
	}
	
	/**
	 * Показ и обработка формы приглаешний
	 *
	 * @return unknown
	 */
	protected function EventInvite() {		
		if (!Config::Get('general.reg.invite')) {
			return parent::EventNotFound();
		}
		
		$this->sMenuItemSelect='invite';
		$this->sMenuSubItemSelect='';		
		$this->Viewer_AddHtmlTitle($this->Lang_Get('settings_menu_invite'));		
		
		if (isPost('submit_invite')) {
			$this->Security_ValidateSendForm();			
			
			$bError=false;
			if (!$this->ACL_CanSendInvite($this->oUserCurrent) and !$this->oUserCurrent->isAdministrator()) {
				$this->Message_AddError($this->Lang_Get('settings_invite_available_no'),$this->Lang_Get('error'));		
				$bError=true;		
			}
			if (!func_check(getRequest('invite_mail'),'mail')) {
				$this->Message_AddError($this->Lang_Get('settings_invite_mail_error'),$this->Lang_Get('error'));		
				$bError=true;		
			}
			if (!$bError) {
				$oInvite=$this->User_GenerateInvite($this->oUserCurrent);
				$this->Notify_SendInvite($this->oUserCurrent,getRequest('invite_mail'),$oInvite);
				$this->Message_AddNoticeSingle($this->Lang_Get('settings_invite_submit_ok'));
			}
		}
		
		$this->Viewer_Assign('iCountInviteAvailable',$this->User_GetCountInviteAvailable($this->oUserCurrent));
		$this->Viewer_Assign('iCountInviteUsed',$this->User_GetCountInviteUsed($this->oUserCurrent->getId()));		
	}
	
	/**
	 * Выводит форму для редактирования профиля и обрабатывает её
	 *
	 */
	protected function EventProfile() {
		$this->Viewer_AddHtmlTitle($this->Lang_Get('settings_menu_profile'));
		/**
		 * Если нажали кнопку "Сохранить"
		 */
		if (isPost('submit_profile_edit')) {
			$this->Security_ValidateSendForm();
						
			$bError=false;			
			/**
		 	* Заполняем профиль из полей формы
		 	*/
			/**
			 * Проверяем имя
			 */
			if (func_check(getRequest('profile_name'),'text',2,20)) {
				$this->oUserCurrent->setProfileName(getRequest('profile_name'));
			} else {
				$this->oUserCurrent->setProfileName(null);
			}
			/**
			 * Проверка мыла
			 */
			if (func_check(getRequest('mail'),'mail')) {
				if ($oUserMail=$this->User_GetUserByMail(getRequest('mail')) and $oUserMail->getId()!=$this->oUserCurrent->getId()) {
					$this->Message_AddError($this->Lang_Get('settings_profile_mail_error_used'),$this->Lang_Get('error'));
					$bError=true;
				} else {
					$this->oUserCurrent->setMail(getRequest('mail'));
				}				
			} else {
				$this->Message_AddError($this->Lang_Get('settings_profile_mail_error'),$this->Lang_Get('error'));
				$bError=true;
			}
			/**
			 * Проверяем пол
			 */
			if (in_array(getRequest('profile_sex'),array('man','woman','other'))) {
				$this->oUserCurrent->setProfileSex(getRequest('profile_sex'));
			} else {
				$this->oUserCurrent->setProfileSex('other');
			}
			/**
			 * Проверяем дату рождения
			 */
			if (func_check(getRequest('profile_birthday_day'),'id',1,2) and func_check(getRequest('profile_birthday_month'),'id',1,2) and func_check(getRequest('profile_birthday_year'),'id',4,4)) {
				$this->oUserCurrent->setProfileBirthday(date("Y-m-d H:i:s",mktime(0,0,0,getRequest('profile_birthday_month'),getRequest('profile_birthday_day'),getRequest('profile_birthday_year'))));
			} else {
				$this->oUserCurrent->setProfileBirthday(null);
			}
			/**
			 * Проверяем страну
			 */
			if (func_check(getRequest('profile_country'),'text',1,30)) {
				$this->oUserCurrent->setProfileCountry(getRequest('profile_country'));
			} else {
				$this->oUserCurrent->setProfileCountry(null);
			}
			/**
			 * Проверяем регион
			 * пока отключим регион, т.к. не понятно нужен ли он вообще =)
			 */
			/*
			if (func_check(getRequest('profile_region'),'text',1,30)) {
				$this->oUserCurrent->setProfileRegion(getRequest('profile_region'));
			} else {
				$this->oUserCurrent->setProfileRegion(null);
			}
			*/
			/**
			 * Проверяем город
			 */
			if (func_check(getRequest('profile_city'),'text',1,30)) {
				$this->oUserCurrent->setProfileCity(getRequest('profile_city'));
			} else {
				$this->oUserCurrent->setProfileCity(null);
			}
			/**
			 * Проверяем ICQ
			 */
			if (func_check(getRequest('profile_icq'),'id',4,15)) {
				$this->oUserCurrent->setProfileIcq(getRequest('profile_icq'));
			} else {
				$this->oUserCurrent->setProfileIcq(null);
			}
			/**
			 * Проверяем сайт
			 */
			if (func_check(getRequest('profile_site'),'text',3,200)) {
				$this->oUserCurrent->setProfileSite(getRequest('profile_site'));
			} else {
				$this->oUserCurrent->setProfileSite(null);
			} 
			/**
			 * Проверяем название сайта
			 */
			if (func_check(getRequest('profile_site_name'),'text',3,50)) {
				$this->oUserCurrent->setProfileSiteName(getRequest('profile_site_name'));
			} else {
				$this->oUserCurrent->setProfileSiteName(null);
			} 
			/**
			 * Проверяем информацию о себе
			 */
			if (func_check(getRequest('profile_about'),'text',1,3000)) {
				$this->oUserCurrent->setProfileAbout(getRequest('profile_about'));
			} else {
				$this->oUserCurrent->setProfileAbout(null);
			} 		
			/**
			 * Проверка на смену пароля
			 */			
			if (getRequest('password','')!='') {
				if (func_check(getRequest('password'),'password',5)) {
					if (getRequest('password')==getRequest('password_confirm')) {
						if (func_encrypt(getRequest('password_now'))==$this->oUserCurrent->getPassword()) {
							$this->oUserCurrent->setPassword(func_encrypt(getRequest('password')));
						} else {
							$bError=true;
							$this->Message_AddError($this->Lang_Get('settings_profile_password_current_error'),$this->Lang_Get('error'));
						}
					} else {
						$bError=true;
						$this->Message_AddError($this->Lang_Get('settings_profile_password_confirm_error'),$this->Lang_Get('error'));
					}
				} else {
					$bError=true;
					$this->Message_AddError($this->Lang_Get('settings_profile_password_new_error'),$this->Lang_Get('error'));
				}
			}		
			/**
			 * Загрузка аватара, делаем ресайзы
			 */		
			if (isset($_FILES['avatar']) and is_uploaded_file($_FILES['avatar']['tmp_name'])) {
				/**
				 * Получаем список текущих аватаров
				 */
				$sPathOld = $this->oUserCurrent->getProfileAvatar();
				$aUserAvatars = array();
				if($sPathOld) {
					foreach (array_merge(Config::Get('module.user.avatar_size'),array(100)) as $iSize) {
						$aUserAvatars[$iSize] = $this->oUserCurrent->getProfileAvatarPath($iSize);
					}
				}
				
				if($sPath=$this->User_UploadAvatar($_FILES['avatar'],$this->oUserCurrent)) {
					$this->oUserCurrent->setProfileAvatar($sPath);
					/**
					 * Удаляем старые, если путь не совпадает с текущими аватарками
					 */
					if($sPathOld and $sPath!=$sPathOld and count($aUserAvatars)) {
						foreach ($aUserAvatars as $iSize=>$sAvatarPath) {
							@unlink($this->Image_GetServerPath($sAvatarPath));
						}
					}
				} else {
					$bError=true;
					$this->Message_AddError($this->Lang_Get('settings_profile_avatar_error'),$this->Lang_Get('error'));					
				}
			}
			/**
			 * Удалить аватара
			 */
			if (getRequest('avatar_delete')) {
				$this->User_DeleteAvatar($this->oUserCurrent);
				$this->oUserCurrent->setProfileAvatar(null);		
			}
			/**
			 * Загрузка фото, делаем ресайзы
			 */			
			if (isset($_FILES['foto']) and is_uploaded_file($_FILES['foto']['tmp_name'])) {				
				if ($sFileFoto=$this->User_UploadFoto($_FILES['foto'],$this->oUserCurrent)) {	
					$this->oUserCurrent->setProfileFoto($sFileFoto);			
				} else {
					$bError=true;
					$this->Message_AddError($this->Lang_Get('settings_profile_foto_error'),$this->Lang_Get('error'));
				}
			}
			/**
			 * Удалить фото
			 */
			if (isset($_REQUEST['foto_delete'])) {
				$this->User_DeleteFoto($this->oUserCurrent);
				$this->oUserCurrent->setProfileFoto(null);
			}
			/**
			 * Ставим дату последнего изменения профиля
			 */
			$this->oUserCurrent->setProfileDate(date("Y-m-d H:i:s"));
			/**
			 * Сохраняем изменения профиля
		 	*/		
			if (!$bError) {
				if ($this->User_Update($this->oUserCurrent)) {
					/**
					 * Добавляем страну
					 */
					if ($this->oUserCurrent->getProfileCountry()) {
						if (!($oCountry=$this->User_GetCountryByName($this->oUserCurrent->getProfileCountry()))) {
							$oCountry=Engine::GetEntity('User_Country');
							$oCountry->setName($this->oUserCurrent->getProfileCountry());
							$this->User_AddCountry($oCountry);
						}
						$this->User_SetCountryUser($oCountry->getId(),$this->oUserCurrent->getId());
					}
					/**
					 * Добавляем город
					 */
					if ($this->oUserCurrent->getProfileCity()) {
						if (!($oCity=$this->User_GetCityByName($this->oUserCurrent->getProfileCity()))) {
							$oCity=Engine::GetEntity('User_City');
							$oCity->setName($this->oUserCurrent->getProfileCity());
							$this->User_AddCity($oCity);
						}
						$this->User_SetCityUser($oCity->getId(),$this->oUserCurrent->getId());
					}
					
					$this->Message_AddNoticeSingle($this->Lang_Get('settings_profile_submit_ok'));
				} else {
					$this->Message_AddErrorSingle($this->Lang_Get('system_error'));
				}
			}
		}
	}	
	
	/**
	 * Выполняется при завершении работы экшена
	 *
	 */
	public function EventShutdown() {		
		/**
		 * Загружаем в шаблон необходимые переменные
		 */
		$this->Viewer_Assign('sMenuItemSelect',$this->sMenuItemSelect);
		$this->Viewer_Assign('sMenuSubItemSelect',$this->sMenuSubItemSelect);
		
		$this->Hook_Run('action_shutdown_settings');	
	}
}
?>