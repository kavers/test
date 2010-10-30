<tr>
	<td class="var">{$aLang.usercats_blog_category}:</td>
	<td>
		<a href="#" onclick="AdminEdit('user_profile_blog_cat'); return false;"><img src="{$sWebPluginSkin}/images/edit.gif" alt="edit" /></a>
	</td>
	<td class="adm_field">
		<div id="v_user_profile_blog_cat">
			{assign var="langKey" value=$oUserBlog->getCategoryName()|string_format:"communitycats_category_%s"}
			{$aLang[$langKey]}
		</div>
		<div  id="e_user_profile_blog_cat" style="display:none;">
			<select class="adm_edit" id="profile_blog_cat" name="profile_blog_cat">
				{$sBlogCatsOptions}
			</select>
		</div>
	</td>
</tr>