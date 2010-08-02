{include file='header.tpl' menu='blog'}


{capture name="blog_title"}
    <img src="{cfg name='path.static.skin'}/img/arrow.gif" width="7" height="12" alt="arrow" title="arrow" class="arrow"/>
    <a href="{router page='blogs'}" class="link"><h1>Сообщества</h1></a>
{/capture}
{include file='topic_list.tpl' blog_title=$smarty.capture.blog_title}


{include file='footer.tpl'}

