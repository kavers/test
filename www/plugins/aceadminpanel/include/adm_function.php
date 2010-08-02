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
* @File Name: adm_function.php
* @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*----------------------------------------------------------------------------
*/

function admHeaderLocation($sLocation) {
    if (!headers_sent()) {
//        func_header_location($sLocation);
//        header("HTTP/1.1 301 Moved Permanently");
        header('HTTP/1.1 303 See Other');
        header('Location: '.$sLocation, true);
        header('Content-type: text/html; charset=UTF-8');
    }
    echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<script language="JavaScript1.1" type="text/javascript">
<!--
location.replace("'.$sLocation.'");
//-->
</script>
<noscript>
<meta http-equiv="Refresh" content="0; URL='.$sLocation.'">
</noscript>
</head>
<body>
Redirect to <a href="'.$sLocation.'">'.$sLocation.'</a>
</body>
</html>';
    exit;

}

/**
 * Получает требуемый элемент из стека вызова
 *
 * @param <type> $nLevel    - уровень (не считая вызова самой фунции)
 * @param <type> $sElement  - искомый элемент (если не задан, то возвращается вся информация)
 * @param <type> $sMatch    - строка соотвествия с именем элемента (только начало строки)
 * @return <type>
 *
 * Если $nLevel == -1, то ищется первое совпадение
 */
function admBacktrace($nLevel=0, $sElement='', $sMatch='') {
    $aBacktrace = debug_backtrace();
    if ($nLevel < 0 && $sElement) {
        foreach ($aBacktrace as $aCaller) {
            if (isset($aCaller[$sElement])) {
                if (($sMatch=='') || ($sMatch > '' && 0===strpos($aCaller[$sElement], $sMatch)))
                    return $aCaller[$sElement];
            }
        }
        return null;
    } else {
        if (!isset($aBacktrace[++$nLevel])) return null;
        if ($sElement) {
            if (isset($aBacktrace[$nLevel][$sElement])) {
                return $aBacktrace[$nLevel][$sElement];
            } else {
                return null;
            }
        } else {
            return $aBacktrace[$nLevel];
        }
    }
}

/**
 * Преобразование пути на сервере в урл
 *
 * @param <type> $path
 */
function admPath2Url($sPath) {
    return str_replace(
            str_replace(DIRECTORY_SEPARATOR,'/',Config::Get('path.root.server')),
            Config::Get('path.root.web'),
            str_replace(DIRECTORY_SEPARATOR,'/',$sPath)
    );
}

/**
 * Преобразование урл в путь на сервере
 *
 * @return <type>
 */
function admUrl2Path($sUrl) {
    // * Delete www from path
    $sUrl = str_replace('//www.','//',$sUrl);
    $sPathWeb  = str_replace('//www.','//',Config::Get('path.root.web'));
    // * do replace
    $sUrl=str_replace($sPathWeb,Config::Get('path.root.server'),$sUrl);
    return admUrl2Path($sUrl);

}

function admFilePath($sPath) {
    if (DIRECTORY_SEPARATOR=='/')
        $sPath = str_replace('\\',DIRECTORY_SEPARATOR,$sPath);
    else
        $sPath = str_replace('/',DIRECTORY_SEPARATOR,$sPath);
    $sPath = str_replace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $sPath);
    return $sPath;
}

function admStr2Array($sStr) {
    if (is_array($sStr)) $result = $sStr;
    else $result = explode(',', str_replace(' ', '', $sStr));
    return $result;
}

function admClearDir($sDir, $bRecursive=true) {
    $result = true;
    $sDir = str_replace('\\', '/', $sDir);
    if (substr($sDir, -1)!='/') $sDir .= '/';
    if (is_dir($sDir) && ($files = glob($sDir.'*'))) {
        foreach ($files as $file) {
            // delete all files except started with 'dot'
            if (substr(basename($file), 0, 1)!='.') {
                if (is_dir($file) && $bRecursive) $result = $result && admClearDir($file, $bRecursive);
                else $result = $result && @unlink($file);
            }
        }
    }
    return $result;
}

function admClearSmartyCache() {
    $result = admClearDir(Config::Get('path.smarty.compiled'));
    $result = $result && admClearDir(Config::Get('path.smarty.cache'));
    return $result;
}

function admClearHeadfilesCache() {
    $sCacheDir  = Config::Get('path.smarty.cache')."/".Config::Get('view.skin');
    $result = admClearDir($sCacheDir);
    return $result;
}

function admClearAllCache() {
    Engine::getInstance()->Cache_Clean();
    $result = admClearSmartyCache() &&  admClearHeadfilesCache();
    return $result;
}

function admGetAllUserIp() {
    $aIp[] = $_SERVER['REMOTE_ADDR'];
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) $aIp[] = $_SERVER['HTTP_X_FORWARDED_FOR'];
    if (isset($_SERVER['HTTP_X_REAL_IP'])) $aIp[] = $_SERVER['HTTP_X_REAL_IP'];
    if (isset($_SERVER['HTTP_VIA'])) {
        if (preg_match('/\d+\.\d+\.\d+\.\d+/', $_SERVER['HTTP_VIA'], $m)) $aIp[] = $m[0];
    }
    return $aIp;
}

function admGetMajorVersion($sVersion) {
    return str_replace(',', '.', ''.floatVal($sVersion));
}

/**
 * Определение (и сохранение в куках) уникального ID посетителя сайта
 * 
 * @return <type>
 */
function admGetVisitorId() {
    if (!defined('ADM_VISITOR_ID')) {
        if (!isset($_COOKIE['visitor_id'])) {
            if (headers_sent()) {
                if (!isset($_SERVER['HTTP_USER_AGENT'])) {
                    // это точно не браузер
                    $sVisitorId = '';
                } else {
                    $sUserAgent = @$_SERVER['HTTP_USER_AGENT'];
                    $sVisitorId = md5($sUserAgent.'::'.serialize(admGetAllUserIp()));
                }
            } else {
                $sVisitorId = md5(uniqid(time()));
            }
        } else {
            $sVisitorId = $_COOKIE['visitor_id'];
        }
        if (!headers_sent()) {
            setcookie('visitor_id', $sVisitorId, time()+60*60*24*365, Config::Get('sys.cookie.path'), Config::Get('sys.cookie.host'));
        }
        define('ADM_VISITOR_ID', $sVisitorId);
    }
    return ADM_VISITOR_ID;
}

if (!defined('ADM_VISITOR_ID')) {
    admGetVisitorId();
}


// EOF