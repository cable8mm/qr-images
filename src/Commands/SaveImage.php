<?php

namespace Cable8mm\QrImages\Commands;

use Cable8mm\QrImages\Configure;
use Cable8mm\QrImages\Exceptions\QrImagesRuntimeException;
use Cable8mm\QrImages\Path;
use Cable8mm\QrImages\SimpleCsv;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class SaveImage extends Command
{
    private const ORIGIN_FILE = 'SSID_QR.csv';

    protected static $defaultName = 'save-image';

    protected static $defaultDescription = 'Save images';

    private QROptions $qrOptions;

    private Configure $configure;

    public function __construct()
    {
        parent::__construct('save-image');
        $this->setDescription('Save images');
    }

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
        try {
            $helper = $this->getHelper('question');
            $question = new ChoiceQuestion(
                'Please select export type.',
                Configure::$qrcodeTypes,
            );

            $question->setErrorMessage('Type %s is invalid.');

            $type = $helper->ask($input, $output, $question);

            $this->setting($type);

            $output->writeln('You have just selected: '.$type);

            $elements = SimpleCsv::get(Path::resources().self::ORIGIN_FILE);

            $output->writeln(sprintf('Processing %d WiFi networks...', count($elements)));

            foreach ($elements as $element) {
                if (! isset($element[0]) || ! isset($element[1]) || ! isset($element[2])) {
                    throw new QrImagesRuntimeException('Invalid CSV format: missing required columns');
                }

                $output->writeln(sprintf('Generating QR codes for network %d...', $element[0]));

                (new QRCode($this->qrOptions))->render($element[1], $this->configure->getPath('5G', (int) $element[0]));

                (new QRCode($this->qrOptions))->render($element[2], $this->configure->getPath('24G', (int) $element[0]));
            }

            $output->writeln('<info>QR codes generated successfully!</info>');

            return Command::SUCCESS;
        } catch (QrImagesRuntimeException $e) {
            $output->writeln(sprintf('<error>Error: %s</error>', $e->getMessage()));

            return Command::FAILURE;
        } catch (\InvalidArgumentException $e) {
            $output->writeln(sprintf('<error>Invalid argument: %s</error>', $e->getMessage()));

            return Command::FAILURE;
        } catch (\Exception $e) {
            $output->writeln(sprintf('<error>Unexpected error: %s</error>', $e->getMessage()));

            return Command::FAILURE;
        }
    }
}
