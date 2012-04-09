<?php
/**
 * Copyright (c) 2012, Jean-Marc Fontaine
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name PHP_ConfigReport nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL Jean-Marc Fontaine BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @package Soko
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
namespace Soko\Console\Command;

use Soko\Builder;
use Soko\Renderer\Stream as StreamRenderer;
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

        $renderer = new StreamRenderer();
        $renderer->render($report);
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
