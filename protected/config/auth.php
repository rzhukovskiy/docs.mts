<?php
return array(
    User::GUEST_ROLE => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Guest',
        'children' => array(),
        'bizRule' => null,
        'data' => null
    ),
    User::PARTNER_ROLE => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Manager',
        'children' => array(
            'guest'
        ),
        'bizRule' => null,
        'data' => null
    ),
    User::CLIENT_ROLE => array(
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
            User::PARTNER_ROLE,
            User::CLIENT_ROLE,
        ),
        'bizRule' => null,
        'data' => null
    ),
);