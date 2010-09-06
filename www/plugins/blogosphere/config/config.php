<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright � 2008 Mzhelskiy Maxim
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
//������ ��������� ��������
$config['filters'] = array(
	array('titleIndex' => 'blogosphere_filter_all', 'type' => 'all'),
	array('titleIndex' => 'blogosphere_filter_popular_topics', 'type' => 'popularTopics'),
	array('titleIndex' => 'blogosphere_filter_popular_users', 'type' => 'popularUsers'),
	//array('titleIndex' => 'blogosphere_filter_celebrity', 'type' => 'celebrity'),
	array('titleIndex' => 'blogosphere_filter_community', 'type' => 'community'),
	//array('titleIndex' => 'blogosphere_filter_recommended', 'type' => 'recommended'),
);

Config::Set('router.page.blogosphere', 'PluginBlogosphere_ActionBlogosphere');

//�� ����� ������ �������� ������("������� ����� � ��������" - ��� �����)
$config['period'] = 12 * 3600;
//�������� ����� ��������� �� ����� ������� � ��������
$config['interval'] = 6200;

//����������� �������, ���� �������� ����� ��������� ����������
$config['popularTopicMinRating']=0;
//����������� �������, ���� �������� ������������ ��������� ����������
$config['popularUsersMinRating']=0;

return $config;
?>