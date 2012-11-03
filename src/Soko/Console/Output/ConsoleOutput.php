<?php
/**
 * @package Soko
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
namespace Soko\Console\Output;

use Symfony\Component\Console\Output\ConsoleOutput as BaseOutput;

/**
 * Custom console output.
 *
 * It allows to easily manage verbose and non verbose displays without the code knowing what the current setting is.
 * Symfony ConsoleOutput class is supposed to handle this but I did not find how to do that.
 *
 */
class ConsoleOutput extends BaseOutput
{
    /**
     * Writes a message to the output if verbose mode is enabled and adds a newline at the end.
     *
     * @param string|array $messages The message as an array of lines of a single string
     * @param integer      $type     The type of output
     *
     * @return void
     */
    public function writelnVerbose($messages, $type = 0)
    {
        if (self::VERBOSITY_VERBOSE === $this->getVerbosity()) {
            $this->writeVerbose($messages, true, $type);
        }
    }

    /**
     * Writes a message to the output if verbose mode is enabled.
     *
     * @param string|array $messages The message as an array of lines of a single string
     * @param Boolean      $newline  Whether to add a newline or not
     * @param integer      $type     The type of output
     *
     * return void
     */
    public function writeVerbose($messages, $newline = false, $type = 0)
    {
        if (self::VERBOSITY_VERBOSE === $this->getVerbosity()) {
            $this->write($messages, $newline, $type);
        }
    }
}
