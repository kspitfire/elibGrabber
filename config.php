<?php

/**
 * Registered modes for the util.
 */
$modes = [
    'dlib' => [
        'url_pattern' => 'http://dlib.rsl.ru/viewer/pdf?docId=%s&page=%d',
        'options' => ['bookId', 'page'],
    ],
    'elib.shpl' => [
        'url_pattern' => 'http://elib.shpl.ru/pages/%s/zooms/8',
        'options' => ['page'],
    ],
    'elib.tomsk' => [
        'url_pattern' => 'http://elib.tomsk.ru/elib/data/%s/%s.jpg',
        'options' => ['bookId', 'page'],
    ]
];
