<?php

return array(
    'error' => 'home/error',
    'login' => 'home/login',
    'logout' => 'home/logout',
    'user/create' => 'user/create',
    'user/update' => 'user/update',
    'user/delete' => 'user/delete',
    'user/<type:\w+>' => 'user/list',
    'act/create' => 'act/create',
    'act/update' => 'act/update',
    'act/delete' => 'act/delete',
    'act/fix' => 'act/fix',
    'act/<type:\w+>' => 'act/list',
    'archive/<type:\w+>' => 'archive/list',
    '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
    '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
);
