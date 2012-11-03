<?php
/**
 * @package Soko
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
namespace Soko\Vcs\Driver;

interface DriverInterface
{
    public function exportFullContentToDir($dir, $hash);

    public function getCommit($id);

    public function setProjectPath($path);
}
