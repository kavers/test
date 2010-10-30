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
	(�������� ����������-������������), ����� cat - ���������������
	�������� � ��������� ��������� ��������������� ��� ����������� �������������.
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

//���������� ��������� ��-���������, ��� ������ � ����� ������������ �� ����������
$config['blockUserCount'] = 5;
//���������� ��������� ��-���������, ��� ������ � ����� ������� ������������ �� ����������
$config['blockTopicUserCount'] = 5;

Config::Set('router.page.usercats', 'PluginUsercats_ActionUsercats');

return $config;

?>