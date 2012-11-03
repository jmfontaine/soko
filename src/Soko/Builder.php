<?php
/**
 * @package Soko
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
namespace Soko;

use Soko\Action\ActionFactory;
use Soko\Vcs\Commit;

/**
 * This is Soko orchestra conductor.
 *
 * This class manages all the steps required to build a commit.
 */
class Builder
{
    /**
     * Actions to perform on the commit
     *
     * @var array
     */
    private $actions = array();

    /**
     * Recusively deletes a directory.
     *
     * @param string $path Path of the directory to delete
     */
    protected function deleteDir($path)
    {
        // TODO: Improve this method

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach($iterator as $item) {
            if($item->isDir()) {
                rmdir($item->getPathName());
            } else {
                unlink($item->getPathName());
            }
        }

        rmdir($path);
    }

    /**
     * Returns the path to the build directory.
     *
     * The path has this form: <SYSTEM TEMP DIRECTORY>/soko/<COMMIT HASH>.
     *
     * @param \Soko\Vcs\Commit $commit Commit instance
     *
     * @return string Build directory path
     */
    protected function getBuildDir(Commit $commit)
    {
        return sys_get_temp_dir() . '/soko/' . $commit->getHash();
    }

    /**
     * Prepares commit build.
     *
     * This includes creating the build directory and exported commit content into it.
     *
     * @param \Soko\Vcs\Commit $commit   Commit instance
     * @param string           $buildDir Build directory path
     */
    protected function prepareBuildData(Commit $commit, $buildDir)
    {
        $this->prepareBuildDir($buildDir);
        $commit->exportFullContentToDir($buildDir);
    }

    /**
     * Prepares the build directory.
     *
     * @param string $path Path of the build dir
     *
     * @return \Soko\Builder The current instance of this class to allow method call chaining
     * @throws \RuntimeException When the build directory can not be created
     */
    protected function prepareBuildDir($path)
    {
        if (file_exists($path)) {
            $this->deleteDir($path);
        }
        if (!@mkdir($path, 0700, true)) {
            throw new \RuntimeException("Could not create build directory ($path)");
        }

        return $this;
    }

    /**
     * Class constructor
     *
     * @param array $config
     */
    public function __construct(array $config = null)
    {
        if (null !== $config) {
            $this->loadConfig($config);
        }
    }

    public function buildCommit(Commit $commit)
    {
        $buildDir = $this->getBuildDir($commit);
        $this->prepareBuildData($commit, $buildDir);

        $report = new Report;
        foreach ($this->actions as $action) {
            $action->run($buildDir, $report);
        }

        return $report;
    }

    public function loadActions(array $actions)
    {
        foreach ($actions as $name => $parameters) {
            $this->actions[] = ActionFactory::createAction($name, $parameters);
        }
    }

    public function loadConfig(array $config)
    {
        if (array_key_exists('actions', $config)) {
            $this->loadActions($config['actions']);
        }
    }
}
