<?php

return [
    'homepage' => [
        'path' => '/',
        'method' => 'get',
        'action' => 'Index::index'
    ],

    'tp1-get' => [
        'path' => '/tp1',
        'method' => 'get',
        'action' => 'Tp1::get'
    ],

    'tp1-post' => [
        'path' => '/tp1',
        'method' => 'post',
        'action' => 'Tp1::post'
    ],

    'tp2-get' => [
        'path' => '/tp2',
        'method' => 'get',
        'action' => 'Tp2::get'
    ],

    'tp2-post' => [
        'path' => '/tp2',
        'method' => 'post',
        'action' => 'Tp2::post'
    ],

    'tp3-get' => [
        'path' => '/tp3',
        'method' => 'get',
        'action' => 'Tp3::get'
    ],

    'tp3-post' => [
        'path' => '/tp3',
        'method' => 'post',
        'action' => 'Tp3::post'
    ],

    'tp4-get' => [
        'path' => '/tp4',
        'method' => 'get',
        'action' => 'Tp4::get'
    ],

    'tp4-post' => [
        'path' => '/tp4',
        'method' => 'post',
        'action' => 'Tp4::post'
    ]
];