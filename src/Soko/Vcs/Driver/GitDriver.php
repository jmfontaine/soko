<?php
/**
 * @package Soko
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
namespace Soko\Vcs\Driver;

class GitDriver extends AbstractDriver
{
    public function exportFullContentToDir($dir, $commitHash)
    {
        $projectPath = $this->getProjectPath();

        $command = sprintf(
            '(cd %s && git archive --format=tar %s) | (cd %s && tar xf -)',
            $projectPath,
            $commitHash,
            $dir
        );
        $process = $this->executeCommand($command);

        if (!$process->isSuccessful()) {
            throw new \RuntimeException("Could not export commit content to directory ($dir)");
        }
    }
}
