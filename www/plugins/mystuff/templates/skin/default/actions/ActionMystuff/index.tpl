{include file='header.tpl'}

{capture name="blog_title"}
    <img src="{cfg name='path.static.skin'}/img/arrow.gif" width="7" height="12" alt="arrow" title="arrow" class="arrow"/>
    <a href="{router page='people'}" class="link"><h1>Личные блоги</h1></a>
    <img src="{cfg name='path.static.skin'}/img/arrow.gif" width="7" height="12" alt="arrow" title="arrow" class="arrow"/>
    <a href="{router page='my'}{$oUserProfile->getLogin()}/" class="link"><h1>{$oUserProfile->getLogin()}</h1></a>
	<img src="{cfg name='path.static.skin'}/img/arrow.gif" width="7" height="12" alt="arrow" title="arrow" class="arrow"/>
    <a href="" class="link"><h1>Записи друзей</h1></a>
{/capture}
{include file='topic_list.tpl' blog_title=$smarty.capture.blog_title}

{include file='footer.tpl'}