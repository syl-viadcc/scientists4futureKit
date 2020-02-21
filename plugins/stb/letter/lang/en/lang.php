<?php return [
    'plugin' => [
        'name' => 'Letter',
        'description' => 'Plugin to collect subscriptions to the public letter.',
        'form' => [
            'name' => 'Name',
            'orcid_id' => 'ORCID ID',
            'email' => 'Email',
            'institution' => 'Institution',
            'rank' => 'Professional category',
            'academic_rank' => 'Professional category',
        ],
        'subscriptions' => 'Subscriptions',
        'verifyemail' => [
            'success' => 'Congratulations! Your emial address is now verified and thus your letter assignment is valid!',
            'already_verified' => 'Thank you! You have already verified your email address.',
            'fail' => 'It is not possible to verify your email address!',
            'no_subscription' => 'Ups! Something went wrong, your subscription doesn\'t exits or is already verified! Please check the public list of subscribers.',
        ],
    ],
    'settings' => [
        'section_recaptcha_label' => 'reCAPTCHA Settings',
        'section_recaptcha_comments' => 'Show or Hide reCAPTCHA on contact us form',
        'recaptcha_label' => 'reCAPTCHA',
        'recaptcha_comment' => 'Display reCAPTCHA widget on the form',
        'site_key_label' => 'Site Key',
        'site_key_comment' => 'Your site key provided by google',
        'secret_key_label' => 'Secret Key',
        'secret_key_comment' => 'Your secret key provided by google',
        'section_email_verification_label' => 'Email Verification Settings',
        'section_email_verification_comments' => 'Enable or Disable email verification.',
        'email_verification_label' => 'Email Verification',
        'email_verification_comment' => 'Email to use in the \'FROM\': header.',
        'email_verification_from' => 'Email Address',
    ],
];