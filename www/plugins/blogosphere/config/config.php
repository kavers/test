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
//Список возможных фильтров
$config['filters'] = array(
	array('titleIndex' => 'blogosphere_filter_all', 'type' => 'all'),
	array('titleIndex' => 'blogosphere_filter_popular_topics', 'type' => 'popularTopics'),
	array('titleIndex' => 'blogosphere_filter_popular_users', 'type' => 'popularUsers'),
	//array('titleIndex' => 'blogosphere_filter_celebrity', 'type' => 'celebrity'),
	array('titleIndex' => 'blogosphere_filter_community', 'type' => 'community'),
	//array('titleIndex' => 'blogosphere_filter_recommended', 'type' => 'recommended'),
	array('titleIndex' => 'my_stuff', 'type' => 'friends', 'function' => 'PluginMystuff_ModuleMystuff_GetTopicsForBlogosphere', 'forRegistered' => 1),
);

Config::Set('router.page.blogosphere', 'PluginBlogosphere_ActionBlogosphere');

//За какой период выводить топики("текущее время в секундах" - это число)
$config['period'] = 12 * 3600;
//Интервал между отметками на линии времени в секундах
$config['interval'] = 6200;

//Минимальный кол-во комментов, выше которого топик считается популярным
$config['popularTopicMinComment'] = 10;
//Число пользователей, которые беруться с вершины списка юзеров отсортированных по числу друзей, популярные пользователи
$config['popularUsersCount'] = 20;

return $config;
?>