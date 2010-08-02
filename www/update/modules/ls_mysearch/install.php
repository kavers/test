<?php
/*---------------------------------------------------------------------------
* @Module Name: MySearch
* @Module Id: ls_mysearch
* @Module URI: http://livestreet.ru/addons/74/
* @Description: Simple Search via MySQL (without Sphinx) for LiveStreet
* @Version: 1.1.34
* @Author: aVadim
* @Author URI: 
* @LiveStreet Version: 0.3.1
* @File Name: install.php
* @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*----------------------------------------------------------------------------
*/

function SeekInstallFile($dir) {

  $aList=glob($dir.'/*.xml');

  if ($aList) {
    $module = ReadInstallFile($aList[0]);
    return $module;
  }
  return false;
}

function ReadInstallFile($file) {

  $module['filename'] = basename($file);
  $module['xmldata'] = file_get_contents($module['filename']);
  $module['xml'] = new SimpleXMLElement($module['xmldata']);

  return $module;
}

function outHTML($body) {

  echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html>
		<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<title></title>
		</head>
		<body><div style="margin-left: 40px;">'.$body.'</div></body>
	</html>
	';
  exit;
}

function getInfo() {
  GLOBAL $module;

  if (!$module) return '';

  $info='';
  $info.='<i>';
  $info.=''.INSTALL_SCREEN_LINE.'<br />';
  $info.='* Module Name: '.$module['xml']->name.'<br />';
  $info.='* Version: '.$module['xml']->version.'<br />';
  $info.='* LiveStreet Version: '.$module['xml']->lsversion.'<br />';
  $info.='* Description: '.$module['xml']->description.'<br />';
  $info.='* Author: '.$module['xml']->author.'<br />';
  $info.=''.INSTALL_SCREEN_LINE.'<br />';
  $info.='</i>';

  return $info;
}

function checkRequirements() {
  $result = true;

  $status['php_version'] = (version_compare(PHP_VERSION, '5.0') < 0)?false:true;
  $status['mb_string'] = (!function_exists('mb_substr'))?false:true;

  foreach ($status as $item) $result = $result && $item;
  
  return array('result'=>$result, 'status'=>$status);
}

function outConfirm($module) {

  if (!$module) {
    $body='<p>Installation not found</p>';
  } else {
    $info=getInfo();

    $body='<p>'.$info.'</p>';
    $check = checkRequirements();
    if ($check['result'])
      $body .=
        '<form>'.
        '<p>Install file: <input type="text" name="install_file" value="'.$module['filename'].'" readonly />'.
        '<p>Do you want continue?</p>'.
        '<p><input type="submit" name="confirm_install" value=" YES " /></form>';
    else {
      $msg = '<p><b>ATTENTION!</b></p>';
      if (!$check['status']['php_version']) $msg .= 'You need <b>PHP 5.0</b> or more (current version is '.PHP_VERSION.')<br />';
      if (!$check['status']['mb_string']) $msg .= 'You need <b>mbstring</b> extension<br />';
      $body .= $msg . 
        '<p>LiveStreet cannot work correctly in this environment</p>' .
        '<p>Installation was cancelled</p>' .
        ':-(';
    }
  }
  outHTML($body);
}

function outResult($bOk, $sErrMsg) {
  GLOBAL $aStat;

  if ($bOk) {
    $sLink=DIR_WEB_ROOT.'/admin/';
    $sMessage='<p>Module installed successfully</p><p>'.INSTALL_SCREEN_LINE.'<br />';
  }
  else {
    $sMessage='<p>Error occured during installation of module</p><p>'.$sErrMsg.'</p>';
  }

  $body='<p>'.$sMessage.'</p>';

  $info=getInfo();

  $info='<p>'.$info.'</p>'.
      '<p>Current date: '.date('Y-m-d H:i:s').'<br />';
  if ($aStat['deleted']) $info.='Deleted files: '.$aStat['deleted'].'<br />';
  if ($aStat['copied']) $info.='Copied files: '.$aStat['copied'].'<br />';
  $body=$info.'<br>'.$body;

  outHTML($body);
}

function MsgError($msg) {
  outResult(false, $msg);
}

function RemoveFile($file) {
  clearstatcache();
  if (file_exists($file)) {
    chmod($file, 0755);
    if(!unlink($file)) {
      MsgError('Cannot delete file <b>'.$file.'</b>');
    }
    return true;
  }
  return false;
}

function CopyFile($src, $dst) {
  GLOBAL $aStat;

  //$dst=str_replace('/templates/skin/default/', '/templates/skin/'.SITE_SKIN.'/', $dst);
  $dst=str_replace('/templates/skin/new/', '/templates/skin/'.SITE_SKIN.'/', $dst);
  $src=str_replace('\\', '/', $src);
  $dst=str_replace('\\', '/', $dst);

  $dir=dirname($dst);
  $dir=substr($dir, strlen(DIR_SERVER_ROOT));
  $parts=explode('/', $dir);
  $dir=$parts[0];
  for($i=1;$i<sizeof($parts);$i++) {
    $dir.='/'.$parts[$i];
    if (!is_dir(DIR_SERVER_ROOT.$dir)) {if (!mkdir(DIR_SERVER_ROOT.$dir)) MsgError('Cannot make dir <b>'.DIR_SERVER_ROOT.$dir.'</b>');}
  }


  RemoveFile($dst);
  $result=@copy($src, $dst);
  if (!$result) {
    $permissions = 0;
    if ($permissions = fileperms(dirname($dst))) {
      if (@chmod(dirname($dst), 0755)) {
        $result=copy($src, $dst);
        chmod(dirname($dst), $permissions);
      }
    }
  }

  return $result;
}

function cmdRemoveFile($data) {
  GLOBAL $aStat;

  $file = DIR_SERVER_ROOT.'/'.$data['name'];
  if (RemoveFile($file)) $aStat['deleted']+=1;
}

function cmdCopyFile($data) {
  GLOBAL $aStat;

  $sDscFile=DIR_SERVER_ROOT.'/'.$data['destination'];
  $sDscFile=str_replace('/templates/skin/default/', '/templates/skin/'.SITE_SKIN.'/', $sDscFile);
  if (substr($sDscFile, -1) != '/') $sDscFile.='/';
  $sDscFile.=basename($data['name']);

  $sSrcFile=DIR_INSTALL.'/'.$data['name'];
  if(!CopyFile($sSrcFile, $sDscFile)) {
    MsgError('Cannot copy file to <b>'.$sDscFile.'</b>.<br/> Please check permissions.');
  }
  $aStat['copied']+=1;
}

/*****************************************************************************/

GLOBAL $aStat;
GLOBAL $module;

define('INSTALL_SCREEN_LINE', '**********************************************************************');

$bOk = true;
$sErrMsg='';

clearstatcache();
$sCurrentDir=dirname(__FILE__);
$sConfigDir=$sCurrentDir;
$module=SeekInstallFile($sCurrentDir);

while (is_dir($sConfigDir) && !is_dir($sConfigDir.'/config')) {
  $sConfigDir=realpath($sConfigDir.'/../');
}

if (file_exists($sConfigDir.'/config/config.php')) {
  include_once($sConfigDir.'/config/config.php');
} else {
  $bOk = true;
  $sErrMsg='ERROR: File "config.php" not found';
}

$aStat['deleted']=0;
$aStat['copied']=0;

define('DIR_INSTALL', $sCurrentDir.'/install');

if (!isset($_REQUEST['confirm_install'])) outConfirm($module);

$sInstallFile=$_REQUEST['install_file'];
$sInstallFile=$sCurrentDir.'/'.$sInstallFile;

$module=ReadInstallFile($sInstallFile);

if ($module) {
  foreach ($module['xml']->removefile as $data) {
    cmdRemoveFile($data);
  }

  foreach ($module['xml']->copyfile as $data) {
    cmdCopyFile($data);
  }
}

outResult($bOk, $sErrMsg);

// EOF