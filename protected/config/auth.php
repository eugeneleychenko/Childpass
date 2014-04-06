<?php

return array(
    'guest' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Guest',
        'bizRule' => null,
        'data' => null
    ),
    Role::x_admin => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Admin',
        'bizRule' => null,
        'data' => null
    ),
    Role::x_host => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Host',
        'bizRule' => null,
        'data' => null
    ),
    Role::x_intern => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Intern',
        'bizRule' => null,
        'data' => null
    ),
);