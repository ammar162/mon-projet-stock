<?php
require __DIR__ . '/vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

header('Content-Type: image/png');

// توليد رمز QR برابط أو نص
$result = Builder::create()
    ->writer(new PngWriter())
    ->data('https://monsite.com') // يمكنك تغيير النص أو الرابط هنا
    ->size(300)
    ->margin(10)
    ->build();

echo $result->getString();
