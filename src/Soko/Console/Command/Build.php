<?php
/**
 * This command builds a commit.
 *
 * @package Soko
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
namespace Soko\Console\Command;

use Aviso\Notifier\GrowlNotifier;
use Aviso\Event\Event;
use Soko\Builder;
use Soko\Vcs\Commit;
use Soko\Vcs\Driver\Git as GitDriver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Parser as YamlParser;

class Build extends Command
{
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Console\Command.Command::configure()
     */
    protected function configure()
    {
        $this->setName('build')
             ->setDescription('Builds latest commit')
             ->setHelp(
                 sprintf(
					'%sBuilds latest commit%s',
                 PHP_EOL,
                 PHP_EOL
                 )
             )->addOption(
                'config',
                'c',
                InputOption::VALUE_OPTIONAL,
                'Path to config file. If none provided the file is assumed to be soko.yml in the current directory'
             )->addArgument(
                'hash',
                InputArgument::REQUIRED,
                'Hash of the commit to build'
             );
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Console\Command.Command::execute()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Load configuration file
        $configPath = $input->getOption('config');
        if (null === $configPath) {
            $configPath = $this->getDefaultConfigPath();
        }

        try {
            $config = $this->loadConfig($configPath);
        } catch (Exception $exception) {
            $output->writeln($exception->getMessage());
        }

        // Let's assume configuration file is at the root of the project local repository
        // TODO: Allow to set path to the repository in the configuration file
        $projectPath = dirname($configPath);

        // The only VCS supported is Git for now so there is no choice
        $vcs    = new GitDriver($projectPath);
        $commit = $vcs->getCommit($input->getArgument('hash'));

        $builder = new Builder($config);
        $report = $builder->buildCommit($commit);

        $event = new Event();
        $title = $report->isSuccessfull() ? 'Build succeed' : 'Build failed';
        $event->setTitle($title);

        $notifier = new GrowlNotifier();
        $notifier->handleEvent($event);
    }

    protected function getDefaultConfigPath()
    {
        return getcwd() . '/soko.yml';
    }

    protected function loadConfig($configPath)
    {
        if (!is_file($configPath) || !is_readable($configPath)) {
            throw new \InvalidArgumentException("Invalid configuration file provided ($configPath)");
        }
        $yaml = file_get_contents($configPath);

        try {
            $yamlParser = new YamlParser();
            $config = $yamlParser->parse($yaml);
        } catch (Exception $exception) {
            throw new \RuntimeException("Invalid configuration file content ($configPath)", null, $exception);
        }

        return $config;
    }
}
