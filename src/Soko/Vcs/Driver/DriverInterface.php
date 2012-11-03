<?php
/**
 * @package Soko
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
namespace Soko\Vcs\Driver;

/**
 * Interface for VCS drivers
 */
interface DriverInterface
{
    /**
     * Exports the full content of the commit into a directory
     *
     * @param string $dir  Directory into which the content should be exported
     * @param string $hash Hash of the commit
     *
     * @return \Soko\Vcs\Driver\DriverInterface The current instance of this class to allow method call chaining
     */
    public function exportFullContentToDir($dir, $hash);

    /**
     * Returns a commit.
     *
     * @param string $hash Hash of the commit
     *
     * @return \Soko\Vcs\Commit Commit
     */
    public function getCommit($id);

    /**
     * Returns the path to the project.
     *
     * @return string Project path
     */
    public function getProjectPath();

    /**
     * Defines path to the project
     *
     * @param $path Path to the project
     *
     * @return \Soko\Vcs\Driver\DriverInterface The current instance of this class to allow method call chaining
     */
    public function setProjectPath($path);
}
