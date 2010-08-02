{include file='header.tpl' menu="blog"}

{*literal}
<script>
function submitTags(sTag) {		
	window.location=DIR_WEB_ROOT+'/tag/'+sTag+'/';
	return false;
}
</script>
{/literal}

	&nbsp;&nbsp;
	<form action="" method="GET" onsubmit="return submitTags(this.tag.value);">
		<img src="{cfg name='path.static.skin'}/images/tagcloud.gif" border="0" style="margin-left: 13px;">&nbsp;
		<input type="text" name="tag" value="{$sTag|escape:'html'}" class="tags-input" >
	</form>

<br>
*}
{capture name="blog_title"}
            <img src="{cfg name='path.static.skin'}/img/arrow.gif" width="7" height="12" alt="arrow" title="arrow" class="arrow"/>
            <a href="#111" class="link"><h1>Тег "{$sTag|escape:'html'}"</h1></a>
{/capture}
	{include file='topic_list.tpl' page_type="tag" blog_title=$smarty.capture.blog_title}



{include file='footer.tpl'}
