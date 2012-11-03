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
use Soko\Vcs\Driver\GitDriver;
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
            exit(1);
        }

        // Let's assume configuration file is at the root of the project local repository
        // TODO: Allow to set path to the repository in the configuration file
        $projectPath = dirname($configPath);

        // The only VCS supported is Git for now so there is no choice
        // TODO: Support more VCS
        $vcs    = new GitDriver($projectPath);
        $commit = $vcs->getCommit($input->getArgument('hash'));

        // Build commit
        $builder = new Builder($config);
        $report = $builder->buildCommit($commit);

        // Prepare event to be broadcasted
        $event = new Event();
        $title = $report->isSuccessfull() ? 'Build succeed' : 'Build failed';
        $event->setTitle($title);

        // Broadcast report
        // TODO: Allow to define notifiers in the configuration file
        $notifier = new GrowlNotifier();
        $notifier->handleEvent($event);
    }

    /**
     * Returns the default configuration file path.
     *
     * This configuration file is named "soko.yml" and is supposed to be in current directory.
     *
     * @return string Default configuration file path
     */
    protected function getDefaultConfigPath()
    {
        return getcwd() . '/soko.yml';
    }

    /**
     * Loads configuration file.
     *
     * @param $configPath Path to configuration file
     *
     * @return array The configuration settings
     * @throws \InvalidArgumentException When configuration file can not be read
     * @throws \RuntimeException When configuration faile is not a valid YAML file
     */
    protected function loadConfig($configPath)
    {
        // KLUDGE: We have to manually check if the path leads to a readable file to avoid a warning
        // when using file_get_content(). This warning could be avoided by using the silence operator (@)
        // but this is bad practice
        // (cf. http://derickrethans.nl/five-reasons-why-the-shutop-operator-should-be-avoided.html).
        if (!is_file($configPath) || !is_readable($configPath)) {
            throw new \InvalidArgumentException("Invalid configuration file provided ($configPath)");
        }
        $yaml = file_get_contents($configPath);

        try {
            $yamlParser = new YamlParser();
            $config = $yamlParser->parse($yaml);
        } catch (Exception $exception) {
            throw new \RuntimeException("Invalid configuration file content ($configPath)", 0, $exception);
        }

        return $config;
    }
}
