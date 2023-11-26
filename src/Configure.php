<?php

namespace Cable8mm\QrImages;

use Cable8mm\QrImages\Exceptions\QrImagesInvalidArgumentException;
use chillerlan\QRCode\Output\QROutputInterface;

class Configure
{
    private string $interface;

    private string $fileExtention;

    private const OUTPUT_PATH = '%s_%d_qrcode.%s';

    public function __construct(string $interface)
    {
        $this->interface = $interface;

        switch ($this->interface) {
            case QROutputInterface::EPS:
                $this->fileExtention = 'eps';
                break;
            case QROutputInterface::FPDF:
                $this->fileExtention = 'pdf';
                break;
            case QROutputInterface::GDIMAGE_PNG:
                $this->fileExtention = 'png';
                break;
            case QROutputInterface::MARKUP_SVG:
                $this->fileExtention = 'svg';
                break;
            default:
                throw new QrImagesInvalidArgumentException('Invalid extension. eps, pdf, png, svg are valid.');
                break;
        }
    }

    /**
     * Get a absolute path + filename.
     *
     * @param  string  $type 5G or 24G
     * @param  string|null  $num qrcode path number
     *
     * @example (new Configure(QROutputInterface::GDIMAGE_PNG))->getPath('5G', 1)
     * @example return /Users/cable8mm/Sites/qr-images/resources/export/5G_132_qrcode.png or 24G_132_qrcode.png
     */
    public function getPath(string $type, string $num): ?string
    {
        $filename = sprintf(self::OUTPUT_PATH, $type, $num, $this->fileExtention);

        return Path::export().$filename;
    }
}