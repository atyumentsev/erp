<?php
use app\models\User;
$userModel = new User();

return [
    'admin' => [
        'id'                => 100,
        'username'          => 'admin',
        'name'              => 'admin',
        'password_hash'     => User::getSaltedPassword('admin'),
        'access_token'      => '100-token',
        'status'            => User::STATUS_ACTIVE,
        'created_at'        => time(),
        'updated_at'        => time(),
    ],
    'demo' => [
        'id'                => 101,
        'username'          => 'demo',
        'name'              => 'demo',
        'password_hash'     => User::getSaltedPassword('demo'),
        'access_token'      => '101-token',
        'status'            => User::STATUS_ACTIVE,
        'created_at'        => time(),
        'updated_at'        => time(),
    ],
];
