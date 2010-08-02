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
* @File Name: config.php
* @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*----------------------------------------------------------------------------
*/

if (!class_exists('Config')) die('Hacking attempt!');

if (defined('ACEADMINPANEL_VERSION')) return array();

define('ACEADMINPANEL_VERSION', '1.4-dev');
define('ACEADMINPANEL_VERSION_BUILD', '109');

$config=array('version'=>ACEADMINPANEL_VERSION.'.'.ACEADMINPANEL_VERSION_BUILD);

/***
 * Проверка URL действий администратора
 * Если задано, то проверяется URL действия администратора.
 * Этот параметр увеличивает безопасность. 
 */
$config['check_url'] = false;

/*** 
 * Использовать "выплывающую" иконку меню
 */
$config['icon_menu'] = true;

/***
 * Разрешить админу голосовать несколько раз
 */
$config['admin_many_votes'] = true;

// определение таблиц
Config::Set('db.table.adminset', '___db.table.prefix___adminset');
Config::Set('db.table.adminban', '___db.table.prefix___adminban');
Config::Set('db.table.adminips', '___db.table.prefix___adminips');

define('ROUTE_PAGE_ADMIN', 'admin');

/***
 * Поддержка старого именования классов
 */
//define('OLD_CLASS_LOADER', true);

return $config;
// EOF