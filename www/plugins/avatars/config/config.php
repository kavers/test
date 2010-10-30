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

//Если истинно, то для решения об отправлении уведомления на прокомментированные топики
//используется тоже поле БД, что и на свои топики 
$config['unionCommentsNotification'] = false;

//Единая ли настройка для коментариев в сообществах, или отдельно для каждого
$config['oneSettingForBlogsComments'] = true;

//Минамальное допустимое время в секундах между двумя запросами к пользователю
$config['requestPeriod'] = 24 * 60 * 60;

Config::Set('router.page.request', 'PluginNotification_ActionNotification');
return $config;
?>