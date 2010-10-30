<p>
{$aLang.accesstotopic_choose_access_levels}
<select size="1" name="access_level" id="accesstotopic">
</select>
<script type="text/javascript">
/*
	Меняем select уровня доступа в зависимости от выбранного блога
	личный или сообщество.
*/
var personalAccessLevelsKeys = [
{foreach from=$personalAccessLevels key=name item=value name=pbKeys}
	"{$name}"{if !$smarty.foreach.pbKeys.last},{/if}
{/foreach}
];
var personalAccessLevelsTitles = [
{foreach from=$personalAccessLevels key=name item=value name=pbTitles}
	{assign var="langKey" value=$name|string_format:"accesstotopic_access_levels_%s"}
	"{$aLang[$langKey]}"{if !$smarty.foreach.pbTitles.last},{/if}
{/foreach}
];

var collectiveAccessLevelsKeys = [
{foreach from=$collectiveAccessLevels key=name item=value name=cbKeys}
	"{$name}"{if !$smarty.foreach.cbKeys.last},{/if}
{/foreach}
];
var collectiveAccessLevelsTitles = [
{foreach from=$collectiveAccessLevels key=name item=value name=cbTitles}
	{assign var="langKey" value=$name|string_format:"accesstotopic_access_levels_%s"}
	"{$aLang[$langKey]}"{if !$smarty.foreach.cbTitles.last},{/if}
{/foreach}
];
var selectedAccessLevel = "{$_aRequest.access_level}";
</script>
</p>