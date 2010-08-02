<?php
/* $Id$ */
// vim: expandtab sw=4 ts=4 sts=4:


/**
 * phpMyAdmin Language Loading File
 */


/**
 * We need some elements of the superglobal $_SERVER array.
 */
require_once('./libraries/grab_globals.lib.php');


/**
 * Define the path to the translations directory and get some variables
 * from system arrays if 'register_globals' is set to 'off'
 */
$lang_path = 'lang/';


/**
 * All the supported languages have to be listed in the array below.
 * 1. The key must be the "official" ISO 639 language code and, if required,
 *    the dialect code. It can also contain some informations about the
 *    charset (see the Russian case).
 * 2. The first of the values associated to the key is used in a regular
 *    expression to find some keywords corresponding to the language inside two
 *    environment variables.
 *    These values contains:
 *    - the "official" ISO language code and, if required, the dialect code
 *      also ('bu' for Bulgarian, 'fr([-_][[:alpha:]]{2})?' for all French
 *      dialects, 'zh[-_]tw' for Chinese traditional...), the dialect has to 
 *      be specified as first;
 *    - the '|' character (it means 'OR');
 *    - the full language name.
 * 3. The second values associated to the key is the name of the file to load
 *    without the 'inc.php' extension.
 * 4. The last values associated to the key is the language code as defined by
 *    the RFC1766.
 *
 * Beware that the sorting order (first values associated to keys by
 * alphabetical reverse order in the array) is important: 'zh-tw' (chinese
 * traditional) must be detected before 'zh' (chinese simplified) for
 * example.
 *
 * When there are more than one charset for a language, we put the -utf-8
 * last because we need the default charset to be non-utf-8 to avoid
 * problems on MySQL < 4.1.x if AllowAnywhereRecoding is FALSE.
 *
 * For Russian, we put 1251 first, because MSIE does not accept 866
 * and users would not see anything.
 */
$available_languages = array(
    'en-utf-8'          => array('en|english',  'english-utf-8', 'en'),
    'ru-win1251'        => array('ru|russian', 'russian-windows-1251', 'ru'),
    'ru-utf-8'          => array('ru|russian', 'russian-utf-8', 'ru'),
    'uk-win1251'        => array('uk|ukrainian', 'ukrainian-windows-1251', 'uk'),
    'uk-utf-8'          => array('uk|ukrainian', 'ukrainian-utf-8', 'uk'),
);


/**
 * Analyzes some PHP environment variables to find the most probable language
 * that should be used
 *
 * @param   string   string to analyze
 * @param   integer  type of the PHP environment variable which value is $str
 *
 * @global  array    the list of available translations
 * @global  string   the retained translation keyword
 *
 * @access  private
 */
function PMA_langDetect($str = '', $envType = '')
{
    global $available_languages;
    global $lang;

    foreach ($available_languages AS $key => $value) {
        // $envType =  1 for the 'HTTP_ACCEPT_LANGUAGE' environment variable,
        //             2 for the 'HTTP_USER_AGENT' one
        $expr = $value[0];
        if (strpos($expr, '[-_]') === FALSE) {
            $expr = str_replace('|', '([-_][[:alpha:]]{2,3})?|', $expr);
        }
        if (($envType == 1 && eregi('^(' . $expr . ')(;q=[0-9]\\.[0-9])?$', $str))
            || ($envType == 2 && eregi('(\(|\[|;[[:space:]])(' . $expr . ')(;|\]|\))', $str))) {
            $lang     = $key;
            break;
        }
    }
} // end of the 'PMA_langDetect()' function


if (!isset($lang)) {
    if (isset($_GET) && !empty($_GET['lang'])) {
        $lang = $_GET['lang'];
    }
    else if (isset($_POST) && !empty($_POST['lang'])) {
        $lang = $_POST['lang'];
    }
    else if (isset($_COOKIE) && !empty($_COOKIE['pma_lang'])) {
        $lang = $_COOKIE['pma_lang'];
    }
}


/**
 * Do the work!
 */

// compatibility with config.inc.php <= v1.80
if (!isset($cfg['Lang']) && isset($cfgLang)) {
    $cfg['Lang']        = $cfgLang;
    unset($cfgLang);
}
if (!isset($cfg['DefaultLang']) && isset($cfgDefaultLang)) {
    $cfg['DefaultLang'] = $cfgDefaultLang;
    unset($cfgLang);
}

/**
 *
 * 2004-02-15 rabus: Deactivated the code temporarily:
 *            We need to allow UTF-8 in order to be MySQL 4.1 compatible!

// Disable UTF-8 if $cfg['AllowAnywhereRecoding'] has been set to FALSE.
if (!isset($cfg['AllowAnywhereRecoding']) || !$cfg['AllowAnywhereRecoding']) {
    $available_language_files               = $available_languages;
    $available_languages                    = array();
    foreach ($available_language_files AS $tmp_lang => $tmp_lang_data) {
        if (substr($tmp_lang, -5) != 'utf-8') {
            $available_languages[$tmp_lang] = $tmp_lang_data;
        }
    } // end while
    unset($tmp_lang, $tmp_lang_data, $available_language_files);
} // end if

 *
 */

// MySQL charsets map
$mysql_charset_map = array(
    'big5'         => 'big5',
    'cp-866'       => 'cp866',
    'euc-jp'       => 'ujis',
    'euc-kr'       => 'euckr',
    'gb2312'       => 'gb2312',
    'gbk'          => 'gbk',
    'iso-8859-1'   => 'latin1',
    'iso-8859-2'   => 'latin2',
    'iso-8859-7'   => 'greek',
    'iso-8859-8'   => 'hebrew',
    'iso-8859-8-i' => 'hebrew',
    'iso-8859-9'   => 'latin5',
    'iso-8859-13'  => 'latin7',
    'iso-8859-15'  => 'latin1',
    'koi8-r'       => 'koi8r',
    'shift_jis'    => 'sjis',
    'tis-620'      => 'tis620',
    'utf-8'        => 'utf8',
    'windows-1250' => 'cp1250',
    'windows-1251' => 'cp1251',
    'windows-1252' => 'latin1',
    'windows-1256' => 'cp1256',
    'windows-1257' => 'cp1257',
);

// Lang forced
if (!empty($cfg['Lang'])) {
    $lang = $cfg['Lang'];
}

// If '$lang' is defined, ensure this is a valid translation
if (!empty($lang) && empty($available_languages[$lang])) {
    $lang = '';
}

// Language is not defined yet :
// 1. try to findout user's language by checking its HTTP_ACCEPT_LANGUAGE
//    variable
if (empty($lang) && !empty($HTTP_ACCEPT_LANGUAGE)) {
    $accepted    = explode(',', $HTTP_ACCEPT_LANGUAGE);
    $acceptedCnt = count($accepted);
    for ($i = 0; $i < $acceptedCnt && empty($lang); $i++) {
        PMA_langDetect($accepted[$i], 1);
    }
}
// 2. try to findout user's language by checking its HTTP_USER_AGENT variable
if (empty($lang) && !empty($HTTP_USER_AGENT)) {
    PMA_langDetect($HTTP_USER_AGENT, 2);
}

// 3. Didn't catch any valid lang : we use the default settings
if (empty($lang)) {
    $lang = $cfg['DefaultLang'];
}

// 4. Checks whether charset recoding should be allowed or not
$allow_recoding = FALSE; // Default fallback value
if (!isset($convcharset) || empty($convcharset)) {
    if (isset($_COOKIE['pma_charset'])) {
        $convcharset = $_COOKIE['pma_charset'];
    } else {
        $convcharset = $cfg['DefaultCharset'];
    }
}

// 5. Defines the associated filename and load the translation
$lang_file = $lang_path . $available_languages[$lang][1] . '.inc.php';
require_once('./' . $lang_file);
?>
