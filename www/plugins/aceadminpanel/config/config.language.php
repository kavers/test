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
* @File Name: config.language.php
* @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*----------------------------------------------------------------------------
*/

if (defined('ADMINLANGUAGE_VERSION')) return array();

define('ADMINLANGUAGE_VERSION', '1.2');

//define('LANG_DEFAULT', 'english');
define('LANG_DEFAULT', 'russian');
define('LANG_DEFINE', 'russian,english');

// Время (количество дней), в течение которого будет сохраняться
// выбранный язык. Если 0, то язык сохраняется только на время 
// текущей сессии
define('LANG_SAVE_DAYS', 365);


//Config::Set('router.page.language', 'PluginAceadminpanel_ActionLanguage');

$config = array();

return $config;
// EOF