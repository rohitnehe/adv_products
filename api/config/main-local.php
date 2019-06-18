<?php

return [
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => require(__DIR__ . '/restful.php'),
            'baseUrl' => "http://".$_SERVER['HTTP_HOST']."/adv_products/",

        ]
    ],
];

        
        
