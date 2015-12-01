<?php
return array(
    User::GUEST_ROLE => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Guest',
        'children' => array(),
        'bizRule' => null,
        'data' => null
    ),
    User::MANAGER_ROLE => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Manager',
        'children' => array(
            'guest'
        ),
        'bizRule' => null,
        'data' => null
    ),
    User::WATCHER_ROLE => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Watcher',
        'children' => array(
            'guest'
        ),
        'bizRule' => null,
        'data' => null
    ),
    User::ADMIN_ROLE => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Administrator',
        'children' => array(
            User::MANAGER_ROLE,
            User::WATCHER_ROLE,
        ),
        'bizRule' => null,
        'data' => null
    ),
);