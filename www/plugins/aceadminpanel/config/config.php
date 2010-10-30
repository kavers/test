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

//таблицы шаблонов
Config::Set('db.table.templates', '___db.table.prefix___templates');
Config::Set('db.table.tplusers', '___db.table.prefix___tplusers');
Config::Set('db.table.tplfav', '___db.table.prefix___tplfav');
//таблицы виджетов
Config::Set('db.table.widgets', '___db.table.prefix___widgets');
Config::Set('db.table.widusers', '___db.table.prefix___widusers');
Config::Set('db.table.widfav', '___db.table.prefix___widfav');
Config::Set('db.table.widproducers', '___db.table.prefix___widproducers');
//таблицы украшений
Config::Set('db.table.decor', '___db.table.prefix___decor');
Config::Set('db.table.decusers', '___db.table.prefix___decusers');
Config::Set('db.table.decfav', '___db.table.prefix___decfav');

/**
 * Категории шаблонов (текстовые данные редактируются в lang файле плагина админпанели)
 */
$config['tplcats']=array(
    'nature',
    'techno'
);

/**
 * категории виджетов (текстовые данные редактируются в lang файле плагина админпанели)
 */
$config['widcats']=array(
    'nature',
    'techno'
);

/**
 * категории украшений (текстовые данные редактируются в lang файле плагина админпанели)
 */
$config['deccats']=array(
    'nature',
    'techno'
);

/**
 * категории украшений (текстовые данные редактируются в lang файле плагина админпанели)
 */
$config['decpositions']=array(
    'bodytop',
    'bodyfoot',
    'sidebartop',
    'sidebarfoot'
);

/***
 * Поддержка старого именования классов
 */
//define('OLD_CLASS_LOADER', true);

return $config;
// EOF