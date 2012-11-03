<?php
/**
 * Soko console application
 *
 * This is where everything starts.
 *
 * @package Soko
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
namespace Soko\Console;

use Soko\Console\Command\Build as BuildCommand;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Soko application.
 */
class Application extends BaseApplication
{
    /**
     * Class constructor.
     */
    public function __construct()
    {
    	parent::__construct('Soko by Jean-Marc Fontaine', '0.1-dev');


        $definition = array(
            new InputArgument('command', InputArgument::REQUIRED, 'The command to execute'),

            new InputOption('--help',    '-h', InputOption::VALUE_NONE, 'Display this help message.'),
            new InputOption('--quiet',   '-q', InputOption::VALUE_NONE, 'Do not output any message.'),
            new InputOption('--verbose', '-v', InputOption::VALUE_NONE, 'Increase verbosity of messages.'),
            new InputOption('--version', '-V', InputOption::VALUE_NONE, 'Display this application version.'),
            new InputOption('--ansi',    '',   InputOption::VALUE_NONE, 'Force ANSI output.'),
            new InputOption('--no-ansi', '',   InputOption::VALUE_NONE, 'Disable ANSI output.'),
        );

        $this->getDefinition()
             ->setDefinition($definition);
    }

    /**
     * Runs the current application.
     *
     * @param InputInterface  $input  An Input instance
     * @param OutputInterface $output An Output instance
     *
     * @return integer 0 if everything went fine, or an error code
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->registerCommands();

        return parent::doRun($input, $output);
    }

    protected function registerCommands()
    {
        $this->addCommands(
    	    array(
    	        new BuildCommand()
	        )
		);
    }
}
