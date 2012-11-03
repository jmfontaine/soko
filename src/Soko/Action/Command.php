<?php
/**
 * Copyright (c) 2012, Jean-Marc Fontaine
 * All rights reserved.
 *
 * @package Soko
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
namespace Soko\Action;

use Soko\Report;
use Symfony\Component\Process\Process;

class Command extends AbstractAction
{
    public function run($sourcePath, Report $report)
    {
        $commandLine = $this->getParameter('command_line');

        $process = new Process($commandLine, $sourcePath);
        $process->run();

        $output = $process->getOutput();
        if ($errorOutput = $process->getErrorOutput()) {
            $output .= 'Error output' . PHP_EOL . PHP_EOL . $process->getErrorOutput();
        }

        $report->addActionData($this, $process->isSuccessful(), $output);
    }
}
