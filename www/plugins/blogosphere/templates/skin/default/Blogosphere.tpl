<!---   Блогосфера    -->
<ul id="blogosphere" class="blogosphere">
<!-- Список видео -->
	<li class="block3 dark_green">
		<div class="title"><a href="#" class="link"><h1>{$aLang.blogosphere_block_title}</h1></a></div>
		<ul class="gradient" id="pluginBlogosphereFilters">
			{foreach from=$oConfig->GetValue('plugin.blogosphere.filters') item=filter name=filters}
				{if $oUserCurrent || !$filter.forRegistered}
			<li{if $smarty.foreach.filters.first} class="first3"{/if}><a href="#" name="{$filter.type}">{$aLang[$filter.titleIndex]}</a></li>
				{/if}
			{/foreach}
		</ul>
		<div class="block_content">
			<div class="blog_line">
				<a name="#" class="prev" style="cursor:pointer"><img src="{cfg name='path.static.skin'}/img/wp_left.png" width="25" height="129" alt="{$aLang.blogosphere_block_left}" title="{$aLang.blogosphere_block_left}"/></a>
				<div class="blogs_cloud" style="overflow: hidden">
					<div class="blogs_items" style="width:2670px;">
						<div class="item" style="display:none">
							<a href="#" class="avatar"><img src="{cfg name='path.static.skin'}/img/avatar.gif" width="40" height="40" alt="avatar" title="avatar"/></a>
							<a href="#" class="theme">#</a>
							<a href="#" class="nickname">#</a>
							{*<div class="rating red">#</div>*}
						</div>
					</div>
				</div>
				<a name="#" class="next" style="cursor:pointer"><img src="{cfg name='path.static.skin'}/img/wp_right.png" width="25" height="129" alt="{$aLang.blogosphere_block_right}" title="{$aLang.blogosphere_block_right}"/></a>
			</div>
			<ul class="time_scroll" style="position:relative;">
				<li class="left"><a name="#" style="cursor:pointer"><img src="{cfg name='path.static.skin'}/img/left_end.gif" width="13" height="13" alt="{$aLang.blogosphere_block_left}" title="{$aLang.blogosphere_block_left}"/></a></li>
				{foreach from=$aBlogosphere.aTimeStamp item=timeStamp}
				<li>{$timeStamp|date_format:"%H:%M"}</li>
				{/foreach}
				<li class="right"><a name="#" style="cursor:pointer"><img src="{cfg name='path.static.skin'}/img/right_end.gif" width="13" height="13" alt="{$aLang.blogosphere_block_right}" title="{$aLang.blogosphere_block_right}"/></a></li>
				<li class="act" style="background-color: #FF7200; opacity: 0.25; cursor:pointer; filter:alpha(opacity=50);">&nbsp;</li>
			</ul>
			<div class="block_bottom5"></div>
		</div>
	</li>
<!-- Список видео -->
	<script type="text/javascript">
		var pluginBlogosphereConfig = new Object();
		pluginBlogosphereConfig.securityKey = "{$LIVESTREET_SECURITY_KEY}";
		pluginBlogosphereConfig.timeStart = {$aBlogosphere.timeStart};
		pluginBlogosphereConfig.timeEnd = {$aBlogosphere.timeEnd};
	</script>
</ul>