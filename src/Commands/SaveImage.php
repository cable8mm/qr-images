<?php

namespace Cable8mm\QrImages\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SaveImage extends Command
{
    protected static $defaultName = 'save-image';

    protected static $defaultDescription = 'Save images';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->success(getcwd());

        return Command::SUCCESS;
    }
}
