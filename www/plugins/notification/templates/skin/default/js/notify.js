function ajaxSendRequestToUser(obj, recipientId) {
	var jObj = jQuery(obj);
	jQuery.ajax({
				"url": "/request",
				"data": {"security_ls_key": LIVESTREET_SECURITY_KEY, "userId": recipientId},
				"dataType": "json",
				"error": function(XMLHttpRequest, textStatus) {
					msgErrorBox.alert('Error', 'Please try again later');
				},
				"success": function(data, textStatus) {
					if(data.bStateError) {
						msgErrorBox.alert(data.sMsgTitle, data.sMsg);
					} else {
						msgNoticeBox.alert(data.sMsgTitle, data.sMsg);
						jObj.remove();
					}
				}
		}
	);
}