<?php
/**
 * @package Soko
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
namespace Soko\Vcs\Driver;

/**
 * VCS driver factory
 */
class Factory
{
    public static function getDriver($name, $projectPath)
    {
        $className = '\\Soko\\Vcs\\Driver\\' . ucfirst(strtolower($name));
        $driver    = new $className($projectPath);

        return $driver;
    }
}
