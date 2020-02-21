<?php return [
	'custom' => [
		'name' => [
			'required' => 'We need to know your name.',
		],
		'email' => [
			'required' => 'Email address is required.',
			'email' => 'Please provide a valid email address.',
            'unique_verified' => 'There is already a subscription with email address :attribute. Thank you!',
            'unique_not_verified' => 'An entry with an email address :attribute already exists, but it is not verified. Please click on the validation link in the email we sent you.'
		],
        'orcid_id' => [
            'required' => 'ORCID ID is required.',
            'unique' => 'Assignment with ORCID ID <orcid_id> already exists!',
            'regex' => 'ORCID ID must be in a format: 0000-0001-2345-6789!',
            'not_in' => 'This ORCID ID is an example, it is not valid!',
            'orcid_ping' => 'ORCID ID: <orcid_id> does not exist!'
        ],
		'reCAPTCHA' => [
			'required' => 'Human test fail, please check reCAPTCHA checkbox'
		],
		'gdpr' => [
			'required' => 'You have to authorize the collection and use of your data'
		]
	],
	'recapcha' => [
		'flash-message'=>'reCAPTCHA (service that protects our website from spam and abuse) failed human validation.',
		'missing-input-secret'=>'The secret parameter is missing.',
		'invalid-input-secret'=>'The secret parameter is invalid or malformed.',
		'missing-input-response'=>'The response parameter is missing.',
		'invalid-input-response'=>'The response parameter is invalid or malformed.',
		'bad-request'=>'The request is invalid or malformed.',
		'timeout-or-duplicate'=>'Timeout or Duplicate reCAPTCHA request.',
		'invalid-keys'=>'reCAPTCHA invalid keys.'
	]
];