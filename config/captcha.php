<?php

return [

    'characters' => '23456789',

    'default'   => [
        'length'    => 5,
        'width'     => 200,
        'height'    => 36,
        'quality'   => 100,
    ],

    'flat'   => [
        'length'    => 6,
        'width'     => 160,
        'height'    => 46,
        'quality'   => 100,
        'lines'     => 0,
        'bgImage'   => false,
        'sensitive' => true,
        'bgColor'   => '#ecf2f4',
        'fontColors'=> ['#000'],
        'contrast'  => 0,
        // 'angle'     => 100,
        // 'sharpen'   => 100,
    ],

    'mini'   => [
        'length'    => 3,
        'width'     => 60,
        'height'    => 32,
    ],

    'inverse'   => [
        'length'    => 5,
        'width'     => 120,
        'height'    => 36,
        'quality'   => 100,
        'sensitive' => true,
        'angle'     => 12,
        'sharpen'   => 10,
        'blur'      => 0,
        'lines'     => 0,
        'invert'    => true,
        'contrast'  => 0,
    ]

];
