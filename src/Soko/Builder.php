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

class Builder
{
    private $actions = array();

    protected function deleteDir($path)
    {
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

    protected function getBuildDir(Commit $commit)
    {
        return sys_get_temp_dir() . '/soko/' . $commit->getHash();
    }

    protected function prepareBuildData(Commit $commit, $buildDir)
    {
        $this->prepareBuildDir($buildDir);
        $commit->exportFullContentToDir($buildDir);
    }

    protected function prepareBuildDir($path)
    {
        if (file_exists($path)) {
            $this->deleteDir($path);
        }
        if (!@mkdir($path, 0700, true)) {
            throw new \RuntimeException("Could not create build directory ($path)");
        }
    }

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
