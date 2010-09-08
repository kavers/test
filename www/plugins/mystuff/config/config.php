<?php
//address router
Config::Set('router.page.mine', 'PluginMystuff_ActionMystuff');

//database setting
$config['table']['topic_commented'] = '___db.table.prefix___topic_commented';
return $config;
?>