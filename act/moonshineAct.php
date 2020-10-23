<?php

switch($request['a']){
	case 'sendSubmission':
		$mail = $request['email'];
		$name = $request['name'];
		$message = $request['message'];

		if(!$name){
			$return = standardMessage(translate("Please enter your name"));
			break;
		}
		if(!$mail){
			$return = standardMessage(translate("Please enter your email"));
			break;
		}
		if(!$message){
			$return = standardMessage(translate("Please enter a message"));
			break;
		}

		$mailArray = getEmailArray(
			'info@moonshine-web.com',
			getConfig("SUBMISSION_MAIL_TO"),
			true,
			getConfig("SUBMISSION_MAIL_SUBJECT"),
			getHTMLContent("submissionEmail", 'en_US'),
			[
				'email' => $mail,
				'name' => $name,
				'message' => $message
			]
		);
		$userMailArray = getEmailArray(
			'noreply@moonshine-web.com',
			$mail,
			true,
			translate("Submission confirmation - Moonshine"),
			getHTMLContent("submissionEmailUser"),
			[
				'name' => $name
			]
		);

		if(sendEmailArray([$mailArray, $userMailArray])){
			$return = standardMessage(translate("Your submission has been sent. Stay tuned!"), false);
			$return['js'] =
				"
					$('#submissionForm input,textarea').not('input[type=submit]').val('');
				";
		}else{
			$return = standardMessage(translate("There was an error sending the submission"));
		}
		break;
}