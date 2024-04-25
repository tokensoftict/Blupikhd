<?php
return [
    'client_id' => env('PAYPAL_CLIENT_ID','AXzjE3sdOQNgpEXH_RNDkcQSpJ4o_roCKRNjaU9CYtPZT-YcHvhIFBrYSDp-O0jeq4rli-hpVnsM1zwQ'),
    'secret' => env('PAYPAL_SECRET','EI6rRDAmElu_72Xcxkh2LjNPXXXPRQgQXfaxblR2FUk0kDGWiKBi8Dh07Y4lYswR_2fWDiFChODY1P9z'),
    'settings' => array(
        'mode' => env('PAYPAL_MODE','sandbox'),
        'http.ConnectionTimeOut' => 30,
        'log.LogEnabled' => true,
        'log.FileName' => storage_path() . '/logs/paypal.log',
        'log.LogLevel' => 'ERROR'
    ),
];
