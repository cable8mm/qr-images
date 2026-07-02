<?php

namespace Cable8mm\QrImages\Commands;

use Cable8mm\QrImages\Config;
use Cable8mm\QrImages\Configure;
use Cable8mm\QrImages\Exceptions\QrImagesRuntimeException;
use Cable8mm\QrImages\Path;
use Cable8mm\QrImages\SimpleCsv;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class SaveImage extends Command
{
    private const ORIGIN_FILE = 'SSID_QR.csv';

    protected static $defaultName = 'save-image';

    protected static $defaultDescription = 'Save QR code images from CSV file(s)';

    private QROptions $qrOptions;

    private Configure $configure;

    public function __construct()
    {
        parent::__construct('save-image');
        $this->setDescription('Save QR code images from CSV file(s)');
    }

    public function setting(string $interface): void
    {
        $eccLevel = Config::get('qr_code.eccLevel', QRCode::ECC_L);
        $version = Config::get('qr_code.version', 3);
        $quietzoneSize = Config::get('qr_code.quietzoneSize', 4);

        $this->qrOptions = new QROptions(
            [
                'eccLevel' => $eccLevel,
                'outputType' => $interface,
                'version' => $version,
                'quietzoneSize' => $quietzoneSize,
            ]
        );

        $this->configure = new Configure($interface);
    }

    protected function configure(): void
    {
        $this->addArgument('csv', InputArgument::OPTIONAL, 'CSV file name or path (supports wildcards like *.csv)');
        $this->addOption('all', null, InputOption::VALUE_NONE, 'Process all CSV files in resources directory');
        $this->addOption('file', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Specify one or more CSV files to process');
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

            // Determine which CSV files to process
            $csvFiles = $this->resolveCsvFiles($input, $output);

            if (empty($csvFiles)) {
                $output->writeln('<error>No CSV files found to process.</error>');

                return Command::FAILURE;
            }

            $output->writeln(sprintf('Found %d CSV file(s) to process.', count($csvFiles)));

            $totalNetworks = 0;

            foreach ($csvFiles as $csvFile) {
                $output->writeln(sprintf('<comment>Processing: %s</comment>', $csvFile));

                $elements = SimpleCsv::get($csvFile);
                $totalNetworks += count($elements);

                $output->writeln(sprintf('  Processing %d WiFi networks...', count($elements)));

                foreach ($elements as $element) {
                    if (! isset($element[0]) || ! isset($element[1]) || ! isset($element[2])) {
                        throw new QrImagesRuntimeException(sprintf('Invalid CSV format in %s: missing required columns', basename($csvFile)));
                    }

                    $output->writeln(sprintf('  Generating QR codes for network %d...', $element[0]));

                    (new QRCode($this->qrOptions))->render($element[1], $this->configure->getPath('5G', (int) $element[0]));

                    (new QRCode($this->qrOptions))->render($element[2], $this->configure->getPath('24G', (int) $element[0]));
                }

                $output->writeln(sprintf('  <info>Completed: %s</info>', basename($csvFile)));
            }

            $output->writeln(sprintf('<info>QR codes generated successfully! Total networks: %d</info>', $totalNetworks));

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

    private function resolveCsvFiles(InputInterface $input, OutputInterface $output): array
    {
        $csvFiles = [];

        // Check for --all flag
        if ($input->getOption('all')) {
            $csvFiles = $this->findAllCsvFiles();
            $output->writeln(sprintf('<comment>Found %d CSV file(s) in resources directory</comment>', count($csvFiles)));

            return $csvFiles;
        }

        // Check for --file options
        $fileOptions = $input->getOption('file');
        if (! empty($fileOptions)) {
            foreach ($fileOptions as $file) {
                $path = $this->resolveCsvPath($file);
                if ($path) {
                    $csvFiles[] = $path;
                }
            }

            return $csvFiles;
        }

        // Check for positional argument
        $csvArg = $input->getArgument('csv');
        if ($csvArg) {
            // Support wildcards
            if (str_contains($csvArg, '*')) {
                $csvFiles = glob($csvArg);
            } else {
                $path = $this->resolveCsvPath($csvArg);
                if ($path) {
                    $csvFiles[] = $path;
                }
            }

            return $csvFiles;
        }

        // Default: use configured CSV file
        $defaultCsv = Config::get('csv_file');
        $path = $this->resolveCsvPath($defaultCsv);
        if ($path) {
            $csvFiles[] = $path;
        }

        return $csvFiles;
    }

    private function resolveCsvPath(string $csvFile): ?string
    {
        // If absolute path, use as-is
        if (Path::isAbsolutePath($csvFile)) {
            return file_exists($csvFile) ? $csvFile : null;
        }

        // Try resources directory
        $resourcesPath = Path::resources().$csvFile;
        if (file_exists($resourcesPath)) {
            return $resourcesPath;
        }

        // Try current directory
        if (file_exists($csvFile)) {
            return $csvFile;
        }

        return null;
    }

    private function findAllCsvFiles(): array
    {
        $resourcesPath = Path::resources();
        $csvFiles = glob($resourcesPath.'*.csv');

        // Filter out test files (case-insensitive)
        return array_filter($csvFiles, function ($file) {
            return ! preg_match('/_TEST/i', basename($file));
        });
    }
}
