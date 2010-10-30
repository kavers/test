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
	(возможны подмассивы-подкатегории), слово cat - зарезервировано
	значения у оконечных категорий зарезервированы для дальнейшего использования.
*/
$config['cats'] = array(
	'WITHOUT' => 0,
	'EXPERT' => array(
		'CLEVER' => 10,
		'FUN' => 11,
		'SICKENER' => 12
	),
	'CELEBRITY' => array(
		'ARTIST' => 2,
		'MUSICIAN' => 3,
		'IDIOT' => 4
	),
);

//Количество элементов по-умолчанию, для вывода в блоки пользователй по категориям
$config['blockUserCount'] = 5;
//Количество элементов по-умолчанию, для вывода в блоки топиков пользователй по категориям
$config['blockTopicUserCount'] = 5;

Config::Set('router.page.usercats', 'PluginUsercats_ActionUsercats');

return $config;

?>