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

$config['personalBlog']['accessLevels'] = array(
	'FOR_ALL' => 0,
	'FOR_REGISTERED' => 1,
	'FOR_FRIENDS' => 2,
	'FOR_TWOSIDE_FRIENDS' => 3,
	'FOR_OWNER_ONLY' => 4
	);
	
$config['collectiveBlog']['accessLevels'] = array(
	'FOR_ALL' => 100,
	'FOR_REGISTERED' => 101,
	'FOR_COLLECTIVE' => 102
);

return $config;
?>