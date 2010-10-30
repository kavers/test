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

/*
	����� - �������� ��������� (��. �������� �����), 
	������ ���� ���� [_A-Z][_A-Z0-9]*, �� ����� 20 �������� � �� ������ �����������.
	����� cat - ���������������
	�������� � ��������� ��������� ��������������� ��� ����������� �������������.
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

//���������� ������ � �����
$config['blockBlogCount'] = 5;

return $config;

?>