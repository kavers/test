/* Поддержка ajax отправок писем друзьям */
function ajaxSendTopicToFriend(topicId) {
	var sBlock = jQuery("#sendtofriend");
	//Проверяем корреткность email
	var email = sBlock.find(":input[name='email']").eq(0);
	if(email.length == 0) {
		msgErrorBox.alert('Error!', 'Sorry, but this form couldn\'t work.');
		sBlock.hide();
		return;
	}
	//Проверяем сообщение
	var message = sBlock.find(":input[name='message']").eq(0);
	if(message.length == 0) {
		msgErrorBox.alert('Error!', 'Sorry, but this form couldn\'t work.');
		sBlock.hide();
		return;
	}
	//Делаем запрос
	jQuery.ajax({
		"url": "/sendtofriend",
		"data": {
			"security_ls_key": LIVESTREET_SECURITY_KEY,
			"email": email.val(), 
			"message": message.val(),
			"topicId": topicId 
		},
		"dataType": "json",
		"error": function(XMLHttpRequest, textStatus) {
			msgErrorBox.alert('Error', 'Please try again later');
		},
		"success": function(data, textStatus) {
			if(data.bStateError) {
				msgErrorBox.alert(data.sMsgTitle, data.sMsg);
			} else {
				msgNoticeBox.alert(data.sMsgTitle, data.sMsg);
				sBlock.children(".message").hide();
			}
		}
	});
};