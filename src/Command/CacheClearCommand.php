<?php

namespace Donchev\Framework\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CacheClearCommand extends Command
{
    protected static $defaultName = 'cache:clear';

    public function __construct(string $name = null)
    {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = dirname(__DIR__, 2) . '/var/cache';

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
