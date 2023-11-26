<?php

declare(strict_types=1);

use Cable8mm\QrImages\Configure;
use Cable8mm\QrImages\Path;
use chillerlan\QRCode\Output\QROutputInterface;
use PHPUnit\Framework\TestCase;

final class ConfigureTest extends TestCase
{
    public function test_can_make_the_instant(): void
    {
        $options = [
            QROutputInterface::EPS,
            QROutputInterface::FPDF,
            QROutputInterface::GDIMAGE_PNG,
            QROutputInterface::MARKUP_SVG,
        ];

        foreach ($options as $option) {
            $configure = new Configure($option);

            $this->assertInstanceOf(Configure::class, $configure, 'Assert a instance is now creating.');
        }
    }

    public function test_is_return_value_of_getPath_method_collect(): void
    {
        $expect = Path::resources().'export/5G_1_qrcode.png';

        $actual = (new Configure(QROutputInterface::GDIMAGE_PNG))->getPath('5G', 1);

        $this->assertEquals($expect, $actual);

        $expect = Path::resources().'export/5G_1_qrcode.eps';

        $actual = (new Configure(QROutputInterface::EPS))->getPath('5G', 1);

        $this->assertEquals($expect, $actual);

        $expect = Path::resources().'export/5G_1_qrcode.svg';

        $actual = (new Configure(QROutputInterface::MARKUP_SVG))->getPath('5G', 1);

        $this->assertEquals($expect, $actual);

        $expect = Path::resources().'export/5G_1_qrcode.pdf';

        $actual = (new Configure(QROutputInterface::FPDF))->getPath('5G', 1);

        $this->assertEquals($expect, $actual);
    }
}
