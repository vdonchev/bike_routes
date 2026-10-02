<?php

namespace Donchev\Framework\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'cache:clear')]
class CacheClearCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = dirname(__DIR__, 3) . '/var/cache';

        $this->delete($path, $path);

        $output->writeln('<fg=green>==> Cache cleared!</>');

        return Command::SUCCESS;
    }

    private function delete(string $dir, string $rootDir): void
    {
        foreach (glob($dir . '/*') as $file) {
            if (is_dir($file)) {
                $this->delete($file, $rootDir);
            } else {
                unlink($file);
            }
        }

        if ($dir != $rootDir) {
            rmdir($dir);
        }
    }
}
