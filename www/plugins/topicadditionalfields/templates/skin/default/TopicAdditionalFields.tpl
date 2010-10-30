<p>
	{$aLang.topicadditionalfields_nowlisteningfield}
	<input type="text" name="now_listening" id="now_listening" value="{$_aRequest.now_listening}"><br />
	{$aLang.topicadditionalfields_currentplacefield}
	<input type="text" name="current_place" id="current_place" value="{$_aRequest.current_place}"><br />
	{$aLang.topicadditionalfields_moodfield}<br />
	<select name="mood" id="mood">
	{foreach key=mood item=value from=$oConfig->Get('plugin.topicadditionalfields.topicMood')}
		<option value="{$value}" {if $_aRequest.mood == $value}selected{/if}>{assign var="langKey" value=$mood|string_format:"topicadditionalfields_topic_mood_%s"}
	{$aLang[$langKey]}</option>
	{/foreach}
	</select>
</p>