<tr>
	<td class="var">{$aLang.usercats_user_category}:</td>
	<td>
		<a href="#" onclick="AdminEdit('user_profile_user_cat'); return false;"><img src="{$sWebPluginSkin}/images/edit.gif" alt="edit" /></a>
	</td>
	<td class="adm_field">
		<div id="v_user_profile_user_cat">
			{assign var="langKey" value=$oUserProfile->getCategoryName()|string_format:"usercats_category_%s"}
			{$aLang[$langKey]}
		</div>
		<div  id="e_user_profile_user_cat" style="display:none;">
			<select class="adm_edit" id="profile_user_cat" name="profile_user_cat">
				{$sUserCatsOptions}
			</select>
		</div>
	</td>
</tr>