<?php

return array(
    'error' => 'home/error',
    'login' => 'home/login',
    'logout' => 'home/logout',
    'user/login' => 'user/login',
    'user/create' => 'user/create',
    'user/update' => 'user/update',
    'user/delete' => 'user/delete',
    'user/<type:\w+>' => 'user/list',
    'act/create' => 'act/create',
    'act/update' => 'act/update',
    'act/delete' => 'act/delete',
    'act/disinfectAll' => 'act/disinfectAll',
    'act/fix' => 'act/fix',
    'act/<type:\w+>' => 'act/list',
    'archive/error' => 'archive/error',
    'archive/update' => 'archive/update',
    'archive/fix' => 'archive/fix',
    'archive/<type:\w+>' => 'archive/list',
    '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
    '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
);
