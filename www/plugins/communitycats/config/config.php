<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright В© 2008 Mzhelskiy Maxim
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

/*
	ключи - названия категорий (см. языковые файлы), 
	должны быть вида [_A-Z][_A-Z0-9]*, не более 20 символов и не должны повторяться.
	слово cat - зарезервировано
	значения у оконечных категорий зарезервированы для дальнейшего использования.
*/
$config['cats'] = array(
	'WITHOUT' => 0,
	'COMP' => array(
		'HARD' => 0,
		'SOFT' => array(
			'GAMES' => 0,
			'OFFICE' => 0,
			),
		),
	'DEVELOP' => 2,
);

Config::Set('router.page.communitycats', 'PluginCommunitycats_ActionCommunitycats');

//Количество блогов в блоке
$config['blockBlogCount'] = 5;

return $config;

?>