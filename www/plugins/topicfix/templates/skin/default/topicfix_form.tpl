<p>
<input type="checkbox" id="topic_fixed" name="topic_fixed" class="checkbox" value="1" {if $_aRequest.topic_fixed}checked{/if}/> &mdash; {$aLang.topicfix_label}
</p>
<script type="text/javascript">
{literal}
function switchAccessForFix(blogSelectId, topicFixedId) {
	var blogSelect = document.getElementById(blogSelectId);
	var topicFixed = document.getElementById(topicFixedId);
	//Чистим, что есть
	topicFixed.setAttribute('disabled','disabled');
	
	var userAdministrator = {/literal}{$oUserCurrent->isAdministrator()};
	{literal}
	var aBlogsRights = {
	{/literal}
{foreach from=$aBlogsRights key=iBlogId item=iPermission name=pbKeys}
		{$iBlogId} : {$iPermission}{if !$smarty.foreach.pbKeys.last},{/if}
{/foreach}
	{literal}
	};
	
	//проверяем права доступа
	if(userAdministrator) {
		topicFixed.removeAttribute('disabled');
	} else {
		if(aBlogsRights[blogSelect.options[blogSelect.selectedIndex].value] == 1) {
			topicFixed.removeAttribute('disabled');
		}
	}
}
document.getElementById('blog_id').addEvent('change', function() {
	switchAccessForFix('blog_id', 'topic_fixed');
});
window.onload=function() {
	switchAccessForFix('blog_id', 'topic_fixed');
};
{/literal}
</script>