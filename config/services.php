<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, SparkPost and others. This file provides a sane default
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */
    'fcm' => [
        'key' => 'AAAAtVkgdp8:APA91bFTauOfZ67XhnFryZ6U_5HV3RETvzdOPxT7Q-Rqp1gCZMhgMynhDFpICaXjW3Q2raxcDtz7SSN4WachHBfd9GplW2bu3uFwssB0D8G_0jBVlFBdt9QdYF3zK7NDZUhUAg0vcl67'
    ],

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'google' => [
        'client_id' =>env('GOOGLE_APP_ID',''),
        'client_secret' => env('GOOGLE_APP_SECRET',''),
        'redirect' => 'https://blupikhd.com/auth/google/callback',
    ],

    'twitter' => [
        'client_id' => env('TWITTER_APP_ID'),
        'client_secret' => env('TWITTER_APP_SECRET'),
        'redirect' => 'https://https://blupikhd.com/auth/twitter/callback',
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_APP_ID'),
        'client_secret' => env('FACEBOOK_APP_SECRET'),
        'redirect' => 'https://blupikhd.com/auth/facebook/callback',
    ],
];
