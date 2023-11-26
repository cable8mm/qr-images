<?php

namespace Cable8mm\QrImages\Commands;

use Cable8mm\QrImages\Configure;
use Cable8mm\QrImages\Path;
use Cable8mm\QrImages\SimpleCsv;
use chillerlan\QRCode\Output\QROutputInterface;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class SaveImage extends Command
{
    private const ORIGIN_FILE = 'SSID_QR_TEST.csv';

    protected static $defaultName = 'save-image';

    protected static $defaultDescription = 'Save images';

    private QROptions $qrOptions;

    private Configure $configure;

    public function setting(string $interface): void
    {
        $this->qrOptions = new QROptions(
            [
                'eccLevel' => QRCode::ECC_L,
                'outputType' => $interface,
                'version' => 3,
            ]
        );

        $this->configure = new Configure($interface);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion(
            'Please select expende type.',
            // choices can also be PHP objects that implement __toString() method
            [
                QROutputInterface::EPS,
                QROutputInterface::GDIMAGE_PNG,
                QROutputInterface::MARKUP_SVG,
                QROutputInterface::GDIMAGE_GIF,
            ],
            0
        );

        $question->setErrorMessage('Type %s is invalid.');

        $type = $helper->ask($input, $output, $question);

        $this->setting($type);

        $output->writeln('You have just selected: '.$type);

        $elements = SimpleCsv::get(Path::resources().self::ORIGIN_FILE);

        foreach ($elements as $element) {
            (new QRCode($this->qrOptions))->render($element[1], $this->configure->getPath('5G', $element[0]));

            (new QRCode($this->qrOptions))->render($element[2], $this->configure->getPath('24G', (int) $element[0]));
        }

        return Command::SUCCESS;
    }
}
