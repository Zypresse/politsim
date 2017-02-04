<?php

return [
    'translations' => [
        'app*' => [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@app/messages',
            'sourceLanguage' => 'en-US',
            'fileMap' => [
                'app' => 'app.php',
                'app/notification' => 'notification.php',
                'app/bills' => 'bills.php',
                'app/agencies' => 'agencies.php',
            ],
        ],
    ],
];
