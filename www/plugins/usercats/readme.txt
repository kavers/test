Плагин User's Categories.

Разработан для проекта 1in.kz.

Для установки в необходимом вам месте одного из шаблонов добавьте
select выбора категории персонального блога {hook run='html_pluginUsercats_blog_form'}
select выбора категории пользователя {hook run='html_pluginUsercats_user_form'}
информация о категории {hook run='html_pluginCommunitycats_category'}
"Хлебные крошки" {hook run='html_pluginUsercats_bread_crumbs'}
Каталог категорий - {hook run='html_pluginUsercats_catalog'}
Каталог пользователей по категориям {hook run='html_pluginUsercats_users_catalog' sType='CELEBRITY' iCount='5'}
Каталог топиков пользователей по категориям {hook run='html_pluginUsercats_topics_catalog' sType='CELEBRITY' iCount='5'}

Список категорий редактируется в конфиге. Категория 0 - категория по-умолчанию.

Использует модифицированный ACEAdmin. Для полноценной работы ACEAdmin должен быть ктивирован после данного плагина.
