<?php
/**
 * @package Soko
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
namespace Soko\Vcs\Driver;

use Soko\Vcs\Commit;
use Soko\Vcs\Driver\DriverInterface;
use Symfony\Component\Process\Process;

/**
 * Abstract VCS driver.
 *
 * This class can be used as a base for VCS specific drivers.
 */
abstract class AbstractDriver implements DriverInterface
{
    /**
     * Path to the project
     *
     * @var string
     */
    protected $projectPath;

    /**
     * Executes a command an returns a Process Symfony Component instance.
     *
     * @param string $command Command to run
     * @param string $workingDir Working directory for command
     *
     * @return \Symfony\Component\Process\Process Process instance to allow full access to the result
     * @throws \RuntimeException When the command fails
     */
    protected function executeCommand($command, $workingDir = null)
    {
        $process = new Process($command);
        $process->setTimeout(3600);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }

        return $process;
    }

    /**
     * Class constructor
     *
     * @param string $projectPath Path to the project
     */
    public function __construct($projectPath)
    {
        $this->setProjectPath($projectPath);
    }

    /**
     * Returns a commit.
     *
     * @param string $hash Hash of the commit
     *
     * @return \Soko\Vcs\Commit Commit
     */
    public function getCommit($hash)
    {
        return new Commit($this, $hash);
    }

    /**
     * Returns the path to the project.
     *
     * @return string Project path
     */
    public function getProjectPath()
    {
        return $this->projectPath;
    }

    /**
     * Defines path to the project
     *
     * @param $path Path to the project
     *
     * @return \Soko\Vcs\Driver\AbstractDriver The current instance of this class to allow method call chaining
     */
    public function setProjectPath($path)
    {
        $this->projectPath = $path;

        return $this;
    }
}
