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
