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
* @File Name: config.route.php
* @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*----------------------------------------------------------------------------
*/

/**
 * Настройки роутинга страниц модуля
 * Определяет какой экшен должен запускаться при определенном УРЛе
 */

define('ROUTE_PAGE_MYSEARCH', 'mysearch');

return array(
	'page' => array(		
		ROUTE_PAGE_MYSEARCH => 'ActionMysearch',
	),	
);

?>