<?php

use chillerlan\QRCode\QRCode;

return [
    'csv_file' => 'SSID_QR.csv',

    'qr_code' => [
        'eccLevel' => QRCode::ECC_L,
        'version' => 3,
        'quietzoneSize' => 4,
    ],

    'paths' => [
        'resources' => 'resources',
        'export' => 'resources/export',
        'images' => 'resources/images',
    ],
];
