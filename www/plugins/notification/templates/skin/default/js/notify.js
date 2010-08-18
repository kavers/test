function sendRequest(recepientId) {
	JsHttpRequest.query(
		'POST /request',
		{ userId: recipientId, security_ls_key: LIVESTREET_SECURITY_KEY },
		function(result, errors) {
			if (!result) {
				msgErrorBox.alert('Error','Please try again later');
				return;
			}
			msgErrorBox.alert('Result',result.sRequestResultText);
		},
		true
	);
}