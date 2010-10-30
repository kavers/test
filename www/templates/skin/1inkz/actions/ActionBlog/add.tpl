

{if $sEvent=='add'}
	{include file='header.tpl' menu='topic_action' showWhiteBack=true}
{else}
	{include file='header.tpl' menu='blog_edit' showWhiteBack=true}
{/if}
<li id="video_player" class="block2 green">
<div class="title">
		{if $sEvent=='add'}
			<h1>{$aLang.blog_create}</h1>
		{else}
			<h1>{$aLang.blog_admin}:</h1> <a class="link" href="{router page='blog'}{$oBlogEdit->getUrl()}/"><h1>{$oBlogEdit->getTitle()}</h1></a>
		{/if}
</div>
        <div class="block_content">
          <div id="text">
          
        <div class="right_text settings" style="width:100%">
          
		<form action="" method="POST" enctype="multipart/form-data">
			{hook run='form_add_blog_begin'}
			<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" /> 
				
			<p>{$aLang.blog_create_title}:<br />
			<input type="text" id="blog_title" name="blog_title" value="{$_aRequest.blog_title}" class="line" /><br />
			<strong>{$aLang.blog_create_title_notice}</strong></p>

			<p>{$aLang.blog_create_url}:<br />
			<input type="text" id="blog_url" name="blog_url" value="{$_aRequest.blog_url}" class="line" {if $_aRequest.blog_id}disabled{/if} /><br />
			<strong>{$aLang.blog_create_url_notice}</strong></p>
						
			<p>{$aLang.blog_create_type}:<br />
			<select name="blog_type" id="blog_type" onChange="">
				<option value="open" {if $_aRequest.blog_type=='open'}selected{/if}>{$aLang.blog_create_type_open}</option>
				<option value="close" {if $_aRequest.blog_type=='close'}selected{/if}>{$aLang.blog_create_type_close}</option>
			</select><br />
			<strong>{$aLang.blog_create_type_open_notice}</strong></p>
			
			{hook run='html_pluginCommunitycats_form'}

			<p>{$aLang.blog_create_description}:<br />
			<textarea name="blog_description" id="blog_description" rows="20" style="width:100%" class="area">{$_aRequest.blog_description}</textarea><br />
			<strong>{$aLang.blog_create_description_notice}</strong></p>
			{*
			<p><label for="blog_limit_rating_topic">{$aLang.blog_create_rating}:</label><br />
            *}
			<input type="hidden" id="blog_limit_rating_topic" name="blog_limit_rating_topic" value="{$_aRequest.blog_limit_rating_topic}" class="w100p" />
            {*<br />
			<strong>{$aLang.blog_create_rating_notice}</strong></p>
			*}	
			<p>
			{if $oBlogEdit and $oBlogEdit->getAvatar()}
				<img src="{$oBlogEdit->getAvatarPath(48)}" />
				<img src="{$oBlogEdit->getAvatarPath(24)}" />
				<label for="avatar_delete"><input type="checkbox" id="avatar_delete" name="avatar_delete" value="on"> &mdash; {$aLang.blog_create_avatar_delete}</label><br /><br />
			{/if}
			{$aLang.blog_create_avatar}:<br />
			<input type="file" name="avatar" id="avatar"></p>
					
			{hook run='form_add_blog_end'}		
			<p><input type="submit" name="submit_blog_add" value="{$aLang.blog_create_submit}">						
			<input type="hidden" name="blog_id" value="{$_aRequest.blog_id}"></p>
			
		</form>
        </div>
        </div>
        <div class="block_bottom3"></div>
        </div>
</li>
{include file='footer.tpl'}