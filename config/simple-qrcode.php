<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default QR Code Writer
    |--------------------------------------------------------------------------
    |
    | This option controls the default QR Code writer that will be used
    | to generate QR codes. You may specify any of the writers that are
    | supported by the BaconQrCode library.
    |
    */

    'default' => env('QR_CODE_WRITER', 'svg'),

    /*
    |--------------------------------------------------------------------------
    | QR Code Writers
    |--------------------------------------------------------------------------
    |
    | Here you may configure the writers that are available for your
    | application. You may even configure multiple writers for the same
    | format to provide different configurations.
    |
    */

    'writers' => [
        'svg' => [
            'driver' => 'svg',
            'options' => [],
        ],

        'png' => [
            'driver' => 'png',
            'options' => [],
        ],

        'eps' => [
            'driver' => 'eps',
            'options' => [],
        ],

        'pdf' => [
            'driver' => 'pdf',
            'options' => [],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default QR Code Size
    |--------------------------------------------------------------------------
    |
    | This option controls the default size of generated QR codes.
    |
    */

    'size' => 300,

    /*
    |--------------------------------------------------------------------------
    | Default QR Code Margin
    |--------------------------------------------------------------------------
    |
    | This option controls the default margin around generated QR codes.
    |
    */

    'margin' => 2,

    /*
    |--------------------------------------------------------------------------
    | Default QR Code Error Correction Level
    |--------------------------------------------------------------------------
    |
    | This option controls the default error correction level for generated
    | QR codes. Available options are: L, M, Q, H
    |
    */

    'error_correction' => 'M',
];
