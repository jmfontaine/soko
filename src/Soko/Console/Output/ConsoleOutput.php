<?php
/**
 * @package Soko
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
namespace Soko\Console\Output;

use Symfony\Component\Console\Output\ConsoleOutput as BaseOutput;

class ConsoleOutput extends BaseOutput
{
    public function writelnVerbose($messages, $type = 0)
    {
        if (self::VERBOSITY_VERBOSE === $this->getVerbosity()) {
            $this->writeVerbose($messages, true, $type);
        }
    }

    public function writeVerbose($messages, $newline = false, $type = 0)
    {
        if (self::VERBOSITY_VERBOSE === $this->getVerbosity()) {
            $this->write($messages, $newline, $type);
        }
    }
}
