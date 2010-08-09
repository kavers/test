<p>
{$aLang.accesstotopic_choose_access_levels}
<select size="1" name="access_level" id="access_level">
</select>
<script type="text/javascript">
/*
	������ select ������ ������� � ����������� �� ���������� �����
	������ ��� ����������.
*/
var personalAccessLevelsKeys = [
{foreach from=$oConfig->GetValue('plugin.accesstotopic.personalBlog.accessLevels') key=name item=value name=pbKeys}
	"{$name}"{if !$smarty.foreach.pbKeys.last},{/if}
{/foreach}
];
var personalAccessLevelsTitles = [
{foreach from=$oConfig->GetValue('plugin.accesstotopic.personalBlog.accessLevels') key=name item=value name=pbTitles}
	{assign var="langKey" value=$name|string_format:"accesstotopic_access_levels_%s"}
	"{$aLang[$langKey]}"{if !$smarty.foreach.pbTitles.last},{/if}
{/foreach}
];

var collectiveAccessLevelsKeys = [
{foreach from=$oConfig->GetValue('plugin.accesstotopic.collectiveBlog.accessLevels') key=name item=value name=cbKeys}
	"{$name}"{if !$smarty.foreach.cbKeys.last},{/if}
{/foreach}
];
var collectiveAccessLevelsTitles = [
{foreach from=$oConfig->GetValue('plugin.accesstotopic.collectiveBlog.accessLevels') key=name item=value name=cbTitles}
	{assign var="langKey" value=$name|string_format:"accesstotopic_access_levels_%s"}
	"{$aLang[$langKey]}"{if !$smarty.foreach.cbTitles.last},{/if}
{/foreach}
];

{literal}
function switchAccessSelect(blogSelectId, accessLevelSelectId) {
	var blogSelect = document.getElementById(blogSelectId);
	var accessLevelSelect = document.getElementById(accessLevelSelectId);
	//������, ��� ����
	while(accessLevelSelect.length > 0) {
		accessLevelSelect.remove(0);
	} 
	//������������ ���� ������ ����� �������� 0, ���������� ���
	if(blogSelect.options[blogSelect.selectedIndex].value === '0') {
		addOptions(accessLevelSelect, personalAccessLevelsKeys, personalAccessLevelsTitles);
	} else {
		addOptions(accessLevelSelect, collectiveAccessLevelsKeys, collectiveAccessLevelsTitles);
	}
}

function addOptions(selectObject, keysArray, titlesArray) {
		var i = 0;
		while(i < keysArray.length) {
			var opt = document.createElement('option');
			opt.value = keysArray[i];
			opt.text = titlesArray[i];
			try {
				selectObject.add(opt,null);
			} catch(ex) {
				selectObject.add(opt);
			}
			i++;
		}
}

document.getElementById('blog_id').addEvent('change', function() {
	switchAccessSelect('blog_id', 'access_level');
});
switchAccessSelect('blog_id', 'access_level');
{/literal}
</script>
</p>