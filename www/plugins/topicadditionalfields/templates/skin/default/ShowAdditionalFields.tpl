{if $oTopic}
	{assign var="moodKey" value=$oTopic->getMoodName()|string_format:"topicadditionalfields_topic_mood_%s"}
	{if $oTopic->getCurrentPlace()}{$aLang.topicadditionalfields_currentplacefield} <strong>{$oTopic->getCurrentPlace()}</strong>&nbsp;&nbsp;{/if} 
	{if $oTopic->getMoodName()}{$aLang.topicadditionalfields_moodfield} <strong>{$aLang[$moodKey]}</strong>&nbsp;&nbsp;{/if} 
	{if $oTopic->getNowListening()}{$aLang.topicadditionalfields_nowlisteningfield} <strong>{$oTopic->getNowListening()}</strong>{/if}
{/if}