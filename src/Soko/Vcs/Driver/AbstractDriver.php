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

abstract class AbstractDriver implements DriverInterface
{
    protected $projectPath;

    /**
     * @param $command
     * @return \Symfony\Component\Process\Process
     * @throws \RuntimeException
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

    public function __construct($projectPath)
    {
        $this->setProjectPath($projectPath);
    }

    public function getCommit($hash)
    {
        return new Commit($this, $hash);
    }

    public function getProjectPath()
    {
        return $this->projectPath;
    }

    public function setProjectPath($path)
    {
        $this->projectPath = $path;

        return $this;
    }
}
