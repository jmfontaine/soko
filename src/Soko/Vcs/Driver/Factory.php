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
    /**
     * Instanciate and returns a VCS driver
     * @param string $name        VCS driver name
     * @param string $projectPath Path to the project
     *
     * @return \Soko\Vcs\Driver\DriverInterface Instance of the VCS driver
     */
    public static function getDriver($name, $projectPath)
    {
        // TODO: Check if driver is registered
        $className = '\\Soko\\Vcs\\Driver\\' . ucfirst(strtolower($name)) . 'Driver';
        $driver    = new $className($projectPath);

        return $driver;
    }
}
