<li id="sendtofriend"><a href="" class="more_menu2 close"><img src="{cfg name='path.static.skin'}/img/icon4.png" width="44" height="44" alt="{$aLang.sendtofriend_send_to_friend}" title="{$aLang.sendtofriend_send_to_friend}" />{$aLang.sendtofriend_send_to_friend}</a>
	<ul class="all_menu message">
		<li><strong>{$aLang.sendtofriend_email}</strong></li>
		<li><input type="text" name="email" /></li>
		<li><strong>{$aLang.sendtofriend_message}</strong></li>
		<li><textarea name="message"></textarea></li>
		<li><input type="submit" class="submit" value=""  onclick="ajaxSendTopicToFriend({$oTopic->getId()}); return false;"/></li>
	</ul>
</li>